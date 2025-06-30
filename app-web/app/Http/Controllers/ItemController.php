<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $search = $request->input('search');

        $items = Item::with('user')
            ->select([
                'item_id',
                'item_activo_fijo',
                'item_nombre',
                'item_tag',
                'item_descripcion',
                'item_size',
                'item_origen',
                'item_destino',
                'item_fecha_entrada',
                'item_fecha_salida',
                'item_observaciones',
                'item_status',
                'item_user_id',
                'item_fecha_modificacion'
            ])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('item_activo_fijo', 'LIKE', "%{$search}%")
                    ->orWhere('item_nombre', 'LIKE', "%{$search}%");
                });
            })
            ->latest('item_fecha_modificacion')
            ->paginate(10)
            ->appends(['search' => $search]); // conserva el texto en la paginación

        return view('items.readInventory', compact('items', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_activo_fijo' => 'required|string|max:50|unique:items',
            'item_nombre' => 'required|string|max:255',
            'item_tag' => 'nullable|string|max:255',
            'item_descripcion' => 'nullable|string|max:500',
            'item_size' => 'nullable|string|max:255',
            'item_origen' => 'nullable|string|max:255',
            'item_destino' => 'nullable|string|max:255',
            'item_fecha_entrada' => 'required|date',
            'item_fecha_salida' => 'nullable|date',
            'item_observaciones' => 'nullable|string',
            'item_status' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $item = new Item($validated);
        $item->item_user_id = $user->user_id;
        $item->item_fecha_modificacion = now();
        $item->save();

        return redirect()->route('items.index')->with('success', 'Item creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        // $this->authorize('view', $item);

        $item->load('user');
        
        return view('items.detailInventory', [
            'item' => array_merge($item->only([
                'item_activo_fijo',
                'item_nombre',
                'item_tag',
                'item_descripcion',
                'item_size',
                'item_origen',
                'item_destino',
                'item_fecha_entrada',
                'item_fecha_salida',
                'item_observaciones',
                'item_status',
                'item_fecha_modificacion'
            ]), [
                'user_name' => $item->user ? $item->user->user_name : 'N/A',
                'user_last_name' => $item->user ? $item->user->user_last_name : 'N/A'
            ])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $this->authorize('update', $item);
        
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $validated = $request->validate([
            'item_activo_fijo' => 'required|string|max:50|unique:items,item_activo_fijo,' . $item->item_id . ',item_id',
            'item_nombre' => 'required|string|max:255',
            'item_tag' => 'nullable|string|max:255',
            'item_descripcion' => 'nullable|string|max:500',
            'item_size' => 'nullable|string|max:255',
            'item_origen' => 'nullable|string|max:255',
            'item_destino' => 'nullable|string|max:255',
            'item_fecha_entrada' => 'required|date',
            'item_fecha_salida' => 'nullable|date',
            'item_observaciones' => 'nullable|string',
            'item_status' => 'required|string|max:255',
        ]);

        $item->fill($validated);
        $item->item_fecha_modificacion = now();
        $item->save();

        return redirect()->route('items.index')->with('success', 'Item actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);
        
        $item->delete();
        
        return redirect()->route('items.index')->with('success', 'Item eliminado exitosamente.');
    }
}