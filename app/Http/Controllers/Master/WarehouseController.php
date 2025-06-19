<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(10);
        return view('masters.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name',
            'code' => 'required|string|max:50|unique:warehouses,code',
            'address' => 'nullable|string',
        ]);

        Warehouse::create($request->all());

        // **FIX:** Changed route to 'master.warehouses.index'
        return redirect()->route('master.warehouses.index')
                         ->with('success', 'Gudang baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('masters.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name,' . $warehouse->id,
            'code' => 'required|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $warehouse->update($data);

        // **FIX:** Changed route to 'master.warehouses.index'
        return redirect()->route('master.warehouses.index')
                         ->with('success', 'Data gudang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        // **FIX:** Changed route to 'master.warehouses.index'
        return redirect()->route('master.warehouses.index')
                         ->with('success', 'Data gudang berhasil dihapus.');
    }
}
