<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
                'item_fecha_modificacion',
                'item_activity'
            ])
            ->where('item_activity', 1)
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

    public function indexUpdateForm(Request $request){
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
                'item_fecha_modificacion',
                'item_activity'
            ])
            ->where('item_activity', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('item_activo_fijo', 'LIKE', "%{$search}%")
                    ->orWhere('item_nombre', 'LIKE', "%{$search}%");
                });
            })
            ->latest('item_fecha_modificacion')
            ->paginate(10)
            ->appends(['search' => $search]); // conserva el texto en la paginación

        return view('items.updateInventory', compact('items', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.addInventory');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'item_activo_fijo' => 'required|string|max:50|unique:items',
            'item_nombre' => 'required|string|max:255',
            'item_tag' => 'required|string|max:255',
            'item_descripcion' => 'required|string|max:500',
            'item_size' => 'nullable|string|max:255',
            'item_origen' => 'required|string|max:255',
            'item_destino' => 'required|string|max:255',
            'item_fecha_entrada' => 'required|date',
            'item_fecha_salida' => 'nullable|date',
            'item_observaciones' => 'nullable|string',
            'item_status' => 'required|string|max:255',
        ], [
            'item_activo_fijo.required' => 'El campo Activo Fijo es obligatorio.',
            'item_activo_fijo.unique' => 'Este Activo Fijo ya existe en el sistema.',
            'item_activo_fijo.max' => 'El Activo Fijo no debe exceder los 50 caracteres.',
            'item_nombre.required' => 'El nombre del ítem es obligatorio.',
            'item_tag.required' => 'El tag es obligatorio.',
            'item_descripcion.required' => 'La descripción es obligatoria.',
            'item_origen.required' => 'El origen es obligatorio.',
            'item_destino.required' => 'El destino es obligatorio.',
            'item_fecha_entrada.required' => 'La fecha de entrada es obligatoria.',
            'item_fecha_entrada.date' => 'La fecha de entrada debe ser una fecha válida.',
            'item_fecha_salida.date' => 'La fecha de salida debe ser una fecha válida.',
            'item_status.required' => 'El estado es obligatorio.',
            
        ]);

        $validated['item_fecha_entrada'] = Carbon::parse($request->item_fecha_entrada)
            ->setTime(now()->hour, now()->minute);
        
        $user = Auth::user();
        
        $item = new Item($validated);
        $item->item_user_id = $user->user_id;
        $item->item_fecha_modificacion = now();
        $item -> item_activity=1;
        $item -> item_user_modifica_id = $user->user_id;
        
        $item->save();

        return redirect()->route('items.index')->with('success', 'Item agregado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        // $this->authorize('view', $item);

        $item->load('user', 'userModifier');
        
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
                'item_fecha_modificacion',
                'item_user_modifica_id'
            ]), [
                'user_name' => $item->user ? $item->user->user_name : 'N/A',
                'user_last_name' => $item->user ? $item->user->user_last_name : 'N/A',
                'user_name_modifier' => $item->userModifier ?->user_name ?? 'N/A',
                'user_last_name_modifier' => $item->userModifier ?->user_last_name ?? 'N/A',
            ])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        // $this->authorize('update', $item);
        
        return view('items.editInventory', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        // $this->authorize('update', $item);

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

        $user = Auth::user();

        $item->fill($validated);
        $item->item_fecha_modificacion = now();
        $item -> item_user_modifica_id = $user->user_id;
        $item->save();

        return redirect()->route('items.indexUpdateForm')->with('success', 'Item actualizado exitosamente.');
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


    // REPORT GENERATION FUNCTIONALITY
        // Mostrar formulario de reportes
    public function showReportForm(){
        $users = User::select(['user_id', 'user_name', 'user_last_name'])->get();
        return view('items.prueba', compact('users'));
    }

    // Generar reporte
    public function generateReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:DISPONIBLE,DETENIDA,TRABAJANDO,POR SALIR,COMPRAS',
            'user_id' => 'nullable|integer|exists:users,user_id',
            'location' => 'nullable|string|in:origin,destination',
            'location_value' => 'nullable|string|max:255',
            'format' => 'required|string|in:pdf,excel'
        ]);

        $query = Item::with('user');

        // Filtro por rango de fechas
        if ($request->date_range && $request->start_date && $request->end_date) {
            $dateField = match($request->date_range) {
                'entry_date' => 'item_fecha_entrada',
                'modification_date' => 'item_fecha_modificacion',
                'exit_date' => 'item_fecha_salida',
                default => 'item_fecha_modificacion'
            };

            $query->whereBetween($dateField, [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('item_status', $request->status);
        }

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('item_user_id', $request->user_id);
        }

        // Filtro por ubicación
        if ($request->filled('location') && $request->filled('location_value')) {
            $locationField = $request->location === 'origin' ? 'item_origen' : 'item_destino';
            $query->where($locationField, 'LIKE', '%' . $request->location_value . '%');
        }

        $items = $query->orderBy('item_fecha_modificacion', 'desc')->get();

        // Generar el reporte según el formato solicitado
        if ($request->format === 'pdf') {
            return $this->generatePdfReport($items);
        } else {
            return $this->generateExcelReport($items);
        }
    }

    // Generar PDF
    private function generatePdfReport($items)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('items.reportPdf', [
            'items' => $items,
            'title' => 'Reporte de Inventario',
            'date' => now()->format('d/m/Y H:i:s')
        ]);
        
        return $pdf->download('reporte-inventario-' . now()->format('YmdHis') . '.pdf');
    }

    // Generar Excel
    private function generateExcelReport($items)
    {
        return \Excel::download(new class($items) implements \FromCollection, \WithHeadings, \WithTitle {
            private $items;

            public function __construct($items)
            {
                $this->items = $items;
            }

            public function collection()
            {
                return $this->items->map(function ($item) {
                    return [
                        'Activo Fijo' => $item->item_activo_fijo,
                        'Nombre' => $item->item_nombre,
                        'Descripción' => $item->item_descripcion,
                        'Tamaño' => $item->item_size,
                        'Origen' => $item->item_origen,
                        'Destino' => $item->item_destino,
                        'Fecha Entrada' => $item->item_fecha_entrada->format('d/m/Y'),
                        'Fecha Salida' => $item->item_fecha_salida ? $item->item_fecha_salida->format('d/m/Y') : 'N/A',
                        'Estado' => $item->item_status,
                        'Usuario' => $item->user ? $item->user->user_name . ' ' . $item->user->user_last_name : 'N/A',
                        'Última Modificación' => $item->item_fecha_modificacion->format('d/m/Y H:i:s')
                    ];
                });
            }

            public function headings(): array
            {
                return [
                    'Activo Fijo',
                    'Nombre',
                    'Descripción',
                    'Tamaño',
                    'Origen',
                    'Destino',
                    'Fecha Entrada',
                    'Fecha Salida',
                    'Estado',
                    'Usuario',
                    'Última Modificación'
                ];
            }

            public function title(): string
            {
                return 'Reporte Inventario';
            }
        }, 'reporte-inventario-' . now()->format('YmdHis') . '.xlsx');
    }
}