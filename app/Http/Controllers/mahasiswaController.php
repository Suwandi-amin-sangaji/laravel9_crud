<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class mahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahBaris = 3;
        if (strlen($katakunci)) {
            $data = mahasiswa::where('nim', 'like', "%$katakunci%")
                ->orWhere('nama', 'like', "%$katakunci%")
                ->orWhere('jurusan', 'like', "%$katakunci%")->paginate($jumlahBaris);
        } else {
            $data = mahasiswa::orderBy('nim', 'DESC')->paginate($jumlahBaris);
        }

        return view('mahasiswa.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Membuat Session Flash
        // Session::flash('nim', $request->nim);
        // Validation Form
        $request->validate([
            'nim' => 'required|numeric|unique:mahasiswa,nim',
            'nama' => 'required',
            'jurusan' => 'required'
        ], [
            'nim.required' => 'Nim Wajib Diisi',
            'nama.required' => 'Nama Wajib Diisi',
            'jurusan.required' => 'Jurusan Wajib Diisi',
        ]);
        // Menangkap Isi File Yanga ada di Form Created
        $data = [
            'nim' => $request->nim,
            'nama' => $request->nama,
            'jurusan' => $request->jurusan
        ];
        // Memasukkan Data KE database
        mahasiswa::insert($data);
        return redirect()->to('mahasiswa')->with('success', 'Data Berhasil Di simpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = mahasiswa::where('nim', $id)->first();
        return view('mahasiswa.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'jurusan' => 'required'
        ], [
            'nama.required' => 'Nama Wajib Diisi',
            'jurusan.required' => 'Jurusan Wajib Diisi',
        ]);
        // Menangkap Isi File Yanga ada di Form Created
        $data = [
            'nama' => $request->nama,
            'jurusan' => $request->jurusan
        ];
        // Memasukkan Data KE database
        mahasiswa::where('nim', $id)->update($data);
        return redirect()->to('mahasiswa')->with('success', 'Data Berhasil Di update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        mahasiswa::where('nim', $id)->delete();
        return redirect()->to('mahasiswa')->with('success', 'Data Berhasil Di Hapus');
    }
}