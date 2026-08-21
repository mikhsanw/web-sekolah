<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Kelulusan;
use App\Models\KelulusanPeriode;
use App\Services\BerkasExcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Modul Data Kelulusan — termasuk impor dan ekspor Excel.
 */
class KelulusanController extends Controller
{
    /** Judul kolom berkas Excel impor/ekspor. */
    protected const KOLOM = [
        'nisn' => 'NISN',
        'nis' => 'NIS',
        'nama' => 'Nama',
        'tanggal_lahir' => 'Tanggal Lahir',
        'kelas' => 'Kelas',
        'status' => 'Status',
        'keterangan' => 'Keterangan',
        'tanggal_pengambilan' => 'Tanggal Pengambilan',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = $request->user();

            $data = $this->model::with('periode')
                ->when($request->filled('periode'), fn ($query) => $query->where('kelulusan_periode_id', $request->query('periode')))
                ->orderBy('nama')
                ->get();

            return datatables()->of($data)
                ->addColumn('periode_nama', fn ($data) => $data->periode?->tahun_ajaran ?? '-')
                ->addColumn('status_label', fn ($data) => $data->nama_status)
                ->addColumn('action', function ($data) use ($user) {
                    $button = '';
                    $button .= '<button type="button" class="btn-action btn btn-sm btn-light-primary" data-title="Detail" data-action="show" data-url="'.$this->url.'" data-id="'.$data->id.'" title="Tampilkan"><i class="fa fa-eye text-info"></i></button>';
                    if ($user->hasRole('Super Admin') || $user->hasPermissionTo($this->code.' edit')) {
                        $button .= '<a type="button" class="btn btn-sm btn-light-warning btn-action" data-title="Edit" data-action="edit" data-url="'.$this->url.'" data-id="'.$data->id.'" title="Edit"> <i class="fa fa-edit text-warning"></i> </a> ';
                    }
                    if ($user->hasRole('Super Admin') || $user->hasPermissionTo($this->code.' delete')) {
                        $button .= '<button type="button" class="btn-action btn btn-sm btn-light-danger" data-title="Delete" data-action="delete" data-url="'.$this->url.'" data-id="'.$data->id.'" title="Delete"> <i class="fa fa-trash text-danger"></i> </button>';
                    }

                    return "<div class='btn-group'>".$button.'</div>';
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make();
        }

        return view($this->view.'.index', ['periodeList' => $this->periodeList()]);
    }

    public function create()
    {
        return view($this->view.'.form', ['periodeList' => $this->periodeList()]);
    }

    public function store(Request $request)
    {
        $isi = $request->validate($this->aturan());

        if ($data = $this->model::create($isi)) {
            $response = ['status' => true, 'message' => 'Data berhasil disimpan'];
        }

        return response()->json($response ?? ['status' => false, 'message' => 'Data gagal disimpan']);
    }

    public function show($id)
    {
        $data = $this->model::with('periode')->findOrFail($id);

        return view($this->view.'.show', compact('data'));
    }

    public function edit($id)
    {
        $data = $this->model::findOrFail($id);

        return view($this->view.'.form', ['data' => $data, 'periodeList' => $this->periodeList()]);
    }

    public function update(Request $request, $id)
    {
        $data = $this->model::findOrFail($id);
        $isi = $request->validate($this->aturan($id));

        if ($data->update($isi)) {
            $response = ['status' => true, 'message' => 'Data berhasil disimpan'];
        }

        return response()->json($response ?? ['status' => false, 'message' => 'Data gagal disimpan']);
    }

    public function delete($id)
    {
        $data = $this->model::findOrFail($id);

        return view($this->view.'.delete', compact('data'));
    }

    public function destroy($id)
    {
        $data = $this->model::findOrFail($id);

        if ($data->delete()) {
            $response = ['status' => true, 'message' => 'Data berhasil dihapus'];
        }

        return response()->json($response ?? ['status' => false, 'message' => 'Data gagal dihapus']);
    }

    /* ------------------------------------------------------------------
     | Impor / Ekspor Excel
     ------------------------------------------------------------------ */

    public function formImport()
    {
        return view($this->view.'.import', [
            'periodeList' => $this->periodeList(),
            'kolom' => self::KOLOM,
        ]);
    }

    /**
     * Unduh template Excel berisi judul kolom dan satu baris contoh.
     */
    public function template()
    {
        return BerkasExcel::unduh(
            'template-import-kelulusan.xlsx',
            array_values(self::KOLOM),
            [[
                '0051234567', '2021001', 'Nama Siswa Contoh', '2010-05-17', 'IX A',
                'lulus', 'Selamat dan sukses', '2026-06-10',
            ]],
            'Kelulusan'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'kelulusan_periode_id' => ['required', 'exists:kelulusan_periodes,id'],
            'berkas' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:5120'],
            'timpa' => ['nullable', 'boolean'],
        ], [], [
            'kelulusan_periode_id' => 'periode kelulusan',
            'berkas' => 'berkas Excel',
        ]);

        try {
            $baris = BerkasExcel::baca($request->file('berkas'));
        } catch (\Throwable $e) {
            return back()->withInput()->with('galat', 'Berkas tidak dapat dibaca: '.$e->getMessage());
        }

        if (empty($baris)) {
            return back()->withInput()->with('galat', 'Berkas tidak berisi data.');
        }

        $periodeId = $request->input('kelulusan_periode_id');
        $timpa = $request->boolean('timpa');
        $statusSah = array_keys(config('sekolah.enum.status_kelulusan'));

        $baru = 0;
        $diperbarui = 0;
        $dilewati = 0;
        $galat = [];

        DB::transaction(function () use ($baris, $periodeId, $timpa, $statusSah, &$baru, &$diperbarui, &$dilewati, &$galat) {
            foreach ($baris as $nomor => $isi) {
                $nomorBaris = $nomor + 2;

                $status = strtolower(str_replace([' ', '-'], '_', (string) ($isi['status'] ?? 'lulus')));
                if (! in_array($status, $statusSah, true)) {
                    $status = in_array($status, ['tidak', 'tdk', 'tidak_lulus', 'gagal'], true) ? 'tidak_lulus' : 'lulus';
                }

                $data = [
                    'nisn' => (string) ($isi['nisn'] ?? ''),
                    'nis' => $isi['nis'] ?? null,
                    'nama' => $isi['nama'] ?? null,
                    'tanggal_lahir' => BerkasExcel::keTanggal($isi['tanggal_lahir'] ?? null),
                    'kelas' => $isi['kelas'] ?? null,
                    'status' => $status,
                    'keterangan' => $isi['keterangan'] ?? null,
                    'tanggal_pengambilan' => BerkasExcel::keTanggal($isi['tanggal_pengambilan'] ?? null),
                ];

                $validator = Validator::make($data, [
                    'nisn' => ['required', 'string', 'max:20'],
                    'nama' => ['required', 'string', 'max:191'],
                    'tanggal_lahir' => ['required', 'date'],
                ]);

                if ($validator->fails()) {
                    $galat[] = 'Baris '.$nomorBaris.': '.implode(' ', $validator->errors()->all());
                    $dilewati++;

                    continue;
                }

                $adaData = Kelulusan::where('kelulusan_periode_id', $periodeId)
                    ->where('nisn', $data['nisn'])
                    ->first();

                if ($adaData) {
                    if (! $timpa) {
                        $dilewati++;

                        continue;
                    }

                    $adaData->update($data);
                    $diperbarui++;

                    continue;
                }

                Kelulusan::create(array_merge($data, ['kelulusan_periode_id' => $periodeId]));
                $baru++;
            }
        });

        return back()->with('sukses', "Impor selesai: {$baru} data baru, {$diperbarui} diperbarui, {$dilewati} dilewati.")
            ->with('galatImport', array_slice($galat, 0, 25));
    }

    public function export(Request $request)
    {
        $data = $this->model::with('periode')
            ->when($request->filled('periode'), fn ($query) => $query->where('kelulusan_periode_id', $request->query('periode')))
            ->orderBy('nama')
            ->get();

        $baris = $data->map(fn (Kelulusan $item) => [
            $item->nisn,
            $item->nis,
            $item->nama,
            $item->tanggal_lahir?->format('Y-m-d'),
            $item->kelas,
            $item->status,
            $item->keterangan,
            $item->tanggal_pengambilan?->format('Y-m-d'),
        ]);

        return BerkasExcel::unduh(
            'data-kelulusan-'.now()->format('Ymd-His').'.xlsx',
            array_values(self::KOLOM),
            $baris,
            'Kelulusan'
        );
    }

    /* ------------------------------------------------------------------ */

    /**
     * @return array<string, string>
     */
    protected function periodeList(): array
    {
        return KelulusanPeriode::orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran', 'id')
            ->toArray();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function aturan(?string $id = null): array
    {
        return [
            'kelulusan_periode_id' => ['required', 'exists:kelulusan_periodes,id'],
            'nisn' => [
                'required', 'string', 'max:20',
                Rule::unique('kelulusans', 'nisn')
                    ->where('kelulusan_periode_id', request('kelulusan_periode_id'))
                    ->whereNull('deleted_at')
                    ->ignore($id),
            ],
            'nis' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:191'],
            'tanggal_lahir' => ['required', 'date'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:'.implode(',', array_keys(config('sekolah.enum.status_kelulusan')))],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'tanggal_pengambilan' => ['nullable', 'date'],
        ];
    }
}
