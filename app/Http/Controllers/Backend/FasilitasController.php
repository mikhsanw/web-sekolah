<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Modul Sarana Prasarana — website sekolah.
 */
class FasilitasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = $request->user();
            $data = $this->model::orderBy('urutan')->orderBy('nama')->get();

            return datatables()->of($data)
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

        return view($this->view.'.index');
    }

    public function create()
    {
        return view($this->view.'.form');
    }

    public function store(Request $request)
    {
        $isi = $request->validate($this->aturan());
        $isi['aktif'] = $request->boolean('aktif');

        if ($data = $this->model::create($isi)) {
            if ($request->hasFile('gambar')) {
                $data->simpanBerkas($request->file('gambar'), 'gambar', $this->code);
            }
            $response = ['status' => true, 'message' => 'Data berhasil disimpan'];
        }

        return response()->json($response ?? ['status' => false, 'message' => 'Data gagal disimpan']);
    }

    public function show($id)
    {
        $data = $this->model::findOrFail($id);

        return view($this->view.'.show', compact('data'));
    }

    public function edit($id)
    {
        $data = $this->model::findOrFail($id);

        return view($this->view.'.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $isi = $request->validate($this->aturan());
        $isi['aktif'] = $request->boolean('aktif');

        $data = $this->model::findOrFail($id);

        if ($data->update($isi)) {
            if ($request->hasFile('gambar')) {
                $data->simpanBerkas($request->file('gambar'), 'gambar', $this->code);
            }
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
     * @return array<string, array<int, string>>
     */
    protected function aturan(): array
    {
        return [
            'nama' => ['required', 'string', 'max:191'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'urutan' => ['nullable', 'integer', 'min:0'],
            'aktif' => ['boolean'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
