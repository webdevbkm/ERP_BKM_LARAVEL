<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar semua pelanggan.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('telepon', 'like', '%' . $request->search . '%');
        }

        $customers = $query->latest()->paginate(15);
        return view('masters.customers.index', compact('customers'));
    }

    /**
     * Menampilkan form untuk membuat pelanggan baru.
     */
    public function create()
    {
        return view('masters.customers.create');
    }

    /**
     * Menyimpan pelanggan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|unique:customers,telepon',
            'alamat' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('master.customers.index')->with('success', 'Pelanggan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pelanggan dan riwayat poinnya.
     */
    public function show(Customer $customer)
    {
        // Pastikan relasi 'pointHistories' ada di model Customer Anda
        $customer->load(['pointHistories' => function ($query) {
            $query->latest();
        }]);
        return view('masters.customers.show', compact('customer'));
    }

    public function searchByPhone(Request $request)
    {
        $request->validate(['phone' => 'required|string']);
        
        $customer = Customer::where('telepon', $request->phone)->first();

        if ($customer) {
            return response()->json($customer);
        }

        return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
    }

    // Anda bisa menambahkan method edit, update, dan destroy nanti.
}