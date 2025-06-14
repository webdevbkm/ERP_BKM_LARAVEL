<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kadar;
use Illuminate\Http\Request;

class KadarController extends Controller
{
    /**
     * Menampilkan daftar semua kadar.
     */
    public function index()
    {
        $kadars = Kadar::latest()->paginate(10);
        return view('masters.kadar.index', compact('kadars'));
    }

    /**
     * Menampilkan form untuk membuat kadar baru.
     */
    public function create()
    {
        return view('masters.kadar.create');
    }

    /**
     * Menyimpan kadar baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kadars,nama|max:255',
            'deskripsi' => 'nullable|string',
            'harga_per_gram' => 'required|numeric|min:0',
        ]);

        Kadar::create($request->all());

        return redirect()->route('kadar.index')
                         ->with('success', 'Data kadar baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit kadar yang ada.
     */
    public function edit(Kadar $kadar)
    {
        // Fungsi ini akan digunakan saat tombol "Edit" diklik
        return view('masters.kadar.edit', compact('kadar'));
    }

    /**
     * Memperbarui data kadar di database.
     */
    public function update(Request $request, Kadar $kadar)
    {
        $request->validate([
            'nama' => 'required|string|unique:kadars,nama,' . $kadar->id . '|max:255',
            'deskripsi' => 'nullable|string',
            'harga_per_gram' => 'required|numeric|min:0',
        ]);

        $kadar->update($request->all());

        return redirect()->route('kadar.index')
                         ->with('success', 'Data kadar berhasil diperbarui.');
    }

    /**
     * Menghapus data kadar dari database.
     */
    public function destroy(Kadar $kadar)
    {
        $kadar->delete();

        return redirect()->route('kadar.index')
                         ->with('success', 'Data kadar berhasil dihapus.');
    }
}
