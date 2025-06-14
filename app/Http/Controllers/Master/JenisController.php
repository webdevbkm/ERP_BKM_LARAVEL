<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use Illuminate\Http\Request;

class JenisController extends Controller
{
    /**
     * Menampilkan daftar semua jenis.
     */
    public function index()
    {
        $jenisItems = Jenis::latest()->paginate(10);
        return view('masters.jenis.index', compact('jenisItems'));
    }

    /**
     * Menampilkan form untuk membuat jenis baru.
     */
    public function create()
    {
        return view('masters.jenis.create');
    }

    /**
     * Menyimpan jenis baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:jenis,nama|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Jenis::create($request->all());

        return redirect()->route('jenis.index')
                         ->with('success', 'Data jenis baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit jenis.
     */
    public function edit(Jenis $jeni)
    {
        return view('masters.jenis.edit', ['jenis' => $jeni]);
    }

    /**
     * Memperbarui data jenis di database.
     */
    public function update(Request $request, Jenis $jeni)
    {
        $request->validate([
            'nama' => 'required|string|unique:jenis,nama,' . $jeni->id . '|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $jeni->update($request->all());

        return redirect()->route('jenis.index')
                         ->with('success', 'Data jenis berhasil diperbarui.');
    }

    /**
     * Menghapus data jenis dari database.
     */
    public function destroy(Jenis $jeni)
    {
        $jeni->delete();

        return redirect()->route('jenis.index')
                         ->with('success', 'Data jenis berhasil dihapus.');
    }
}
