<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SpmbPendaftar;
use App\Models\SpmbPeriode;
use App\Services\BerkasExcel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Modul Pendaftar SPMB — verifikasi berkas dan ekspor data ke Excel.
 */
class SpmbController extends Controller
{
    protected const KOLOM = [
        'no_pendaftaran' => 'No Pendaftaran',
        'asal_sekolah' => 'Asal Sekolah',
        'nisn' => 'NISN',
        'nik' => 'NIK',
        'nama' => 'Nama',
        'jenis_kelamin' => 'Jenis Kelamin',
        'agama' => 'Agama',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'alamat' => 'Alamat',
        'no_hp' => 'No HP',
        'nama_orang_tua' => 'Orang Tua/Wali',
        'status' => 'Status',
        'terdaftar_pada' => 'Waktu Pendaftaran',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = $request->user();

            $data = $this->model::with('periode')
                ->when($request->filled('periode'), fn ($query) => $query->where('spmb_periode_id', $request->query('periode')))
                ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
                ->orderByDesc('terdaftar_pada')
                ->get();

            return datatables()->of($data)
                ->addColumn('status_label', fn ($data) => '<span class="badge badge-light-'.$data->warna_status.'">'.$data->nama_status.'</span>')
                ->addColumn('periode_nama', fn ($data) => $data->periode?->tahun_ajaran ?? '-')
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
                ->rawColumns(['action', 'status_label'])
                ->make();
        }

        return view($this->view.'.index', [
            'periodeList' => $this->periodeList(),
            'rekap' => $this->rekap($request->query('periode')),
        ]);
    }

    public function create()
    {
        return view($this->view.'.form', ['periodeList' => $this->periodeList()]);
    }

    public function store(Request $request)
    {
        $isi = $request->validate($this->aturan());

        $periode = SpmbPeriode::findOrFail($isi['spmb_periode_id']);

        $isi['no_pendaftaran'] = SpmbPendaftar::buatNomorPendaftaran($periode);
        $isi['terdaftar_pada'] = now();

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

    /**
     * Unduh seluruh pendaftar (opsional difilter periode/status) sebagai Excel.
     */
    public function export(Request $request)
    {
        $data = $this->model::with('periode')
            ->when($request->filled('periode'), fn ($query) => $query->where('spmb_periode_id', $request->query('periode')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->orderBy('no_pendaftaran')
            ->get();

        $jenisKelamin = config('sekolah.enum.jenis_kelamin');
        $statusSpmb = config('sekolah.enum.status_spmb');

        $baris = $data->map(fn (SpmbPendaftar $item) => [
            $item->no_pendaftaran,
            $item->asal_sekolah,
            $item->nisn,
            $item->nik,
            $item->nama,
            $jenisKelamin[$item->jenis_kelamin] ?? $item->jenis_kelamin,
            $item->agama,
            $item->tempat_lahir,
            $item->tanggal_lahir?->format('Y-m-d'),
            $item->alamat,
            $item->no_hp,
            $item->nama_orang_tua,
            $statusSpmb[$item->status] ?? $item->status,
            $item->terdaftar_pada?->format('Y-m-d H:i'),
        ]);

        return BerkasExcel::unduh(
            'pendaftar-spmb-'.now()->format('Ymd-His').'.xlsx',
            array_values(self::KOLOM),
            $baris,
            'Pendaftar SPMB'
        );
    }

    /**
     * @return array<string, string>
     */
    protected function periodeList(): array
    {
        return SpmbPeriode::orderByDesc('tahun_ajaran')->pluck('tahun_ajaran', 'id')->toArray();
    }

    /**
     * @return array<string, int>
     */
    protected function rekap(?string $periode = null): array
    {
        $dasar = $this->model::query()
            ->when($periode, fn ($query) => $query->where('spmb_periode_id', $periode));

        $rekap = ['total' => (clone $dasar)->count()];

        foreach (array_keys(config('sekolah.enum.status_spmb')) as $status) {
            $rekap[$status] = (clone $dasar)->where('status', $status)->count();
        }

        return $rekap;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function aturan(?string $id = null): array
    {
        return [
            'spmb_periode_id' => ['required', 'exists:spmb_periodes,id'],
            'nisn' => [
                'required', 'string', 'digits_between:8,12',
                Rule::unique('spmb_pendaftars', 'nisn')
                    ->where('spmb_periode_id', request('spmb_periode_id'))
                    ->whereNull('deleted_at')
                    ->ignore($id),
            ],
            'nik' => ['required', 'string', 'digits:16'],
            'nama' => ['required', 'string', 'max:191'],
            'jenis_kelamin' => ['required', Rule::in(array_keys(config('sekolah.enum.jenis_kelamin')))],
            'agama' => ['required', Rule::in(array_keys(config('sekolah.enum.agama')))],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'alamat' => ['required', 'string', 'max:500'],
            'no_hp' => ['required', 'string', 'max:20'],
            'asal_sekolah' => ['required', 'string', 'max:150'],
            'nama_orang_tua' => ['nullable', 'string', 'max:150'],
            'status' => ['required', Rule::in(array_keys(config('sekolah.enum.status_spmb')))],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
