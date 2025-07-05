<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ReportController extends Controller
{
    /**
     * Muestra el formulario para generar reportes
     */
    public function showReportForm(){
        $users = User::select(['user_id', 'user_name', 'user_last_name'])->get();
        return view('items.reportsInventory', compact('users'));
    }

    /**
     * Genera el reporte en el formato solicitado
     */
    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:DISPONIBLE,DETENIDA,TRABAJANDO,POR SALIR,COMPRAS',
            'user_id' => 'nullable|integer|exists:users,user_id',
            'location' => 'nullable|string|in:origin,destination',
            'location_value' => 'nullable|string|max:255',
            'format' => 'required|string|in:pdf,excel'
        ]);

        $query = $this->buildReportQuery($validated);
        $items = $query->orderBy('item_fecha_entrada', 'desc')->get();

        return $this->exportReport($items, $validated['format']);
    }

    /**
     * Construye la consulta basada en los filtros
     */
    protected function buildReportQuery(array $filters){
        $query = Item::with('user', 'userModifier');

        // Filtro por rango de fechas
        if (!empty($filters['date_range'])) {
            $dateField = $this->getDateField($filters['date_range']);
            
            // Caso 1: Solo start_date está presente
            if (!empty($filters['start_date']) && empty($filters['end_date'])) {
                $query->where($dateField, '>=', $filters['start_date'] . ' 00:00:00');
            }
            // Caso 2: Solo end_date está presente
            elseif (empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->where($dateField, '<=', $filters['end_date'] . ' 23:59:59');
            }
            // Caso 3: Ambos están presentes
            elseif (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->whereBetween($dateField, [
                    $filters['start_date'] . ' 00:00:00',
                    $filters['end_date'] . ' 23:59:59'
                ]);
            }
        }

        // Filtro por estado
        if (!empty($filters['status'])) {
            $query->where('item_status', $filters['status']);
        }

        // Filtro por usuario
        if (!empty($filters['user_id'])) {
            $query->where('item_user_id', $filters['user_id']);
        }

        // Filtro por usuario que modificó
        if (!empty($filters['modified_user_id'])) {
            $query->where('item_user_modifica_id', $filters['modified_user_id']);
        }

        // Filtro por ubicación (aunque dijiste que lo quitarías, lo dejo por si acaso)
        if (!empty($filters['location']) && !empty($filters['location_value'])) {
            $locationField = $filters['location'] === 'origin' ? 'item_origen' : 'item_destino';
            $query->where($locationField, 'LIKE', '%' . $filters['location_value'] . '%');
        }

        return $query;
    }

    /**
     * Obtiene el campo de fecha correspondiente
     */
    protected function getDateField(string $dateRange): string
    {
        return match($dateRange) {
            'entry_date' => 'item_fecha_entrada',
            'modification_date' => 'item_fecha_modificacion',
            'exit_date' => 'item_fecha_salida',
            default => 'item_fecha_entrada'
        };
    }

    /**
     * Exporta el reporte en el formato solicitado
     */
    protected function exportReport($items, string $format)
    {
        if ($format === 'pdf') {
            return $this->generatePdfReport($items);
        }

        return $this->generateExcelReport($items);
    }

    /**
     * Genera el reporte en PDF
     */
    protected function generatePdfReport($items)
    {
        // Transformar los items a un formato consistente
        $formattedItems = $items->map(function ($item) {
            // Convertir a objeto si es array
            $item = is_array($item) ? (object)$item : $item;
            
            return [
                'item_activo_fijo' => $item->item_activo_fijo ?? 'N/A',
                'item_nombre' => $item->item_nombre ?? 'N/A',
                'item_tag' => $item->item_tag ?? 'N/A',
                'item_descripcion' => $item->item_descripcion ?? 'N/A',
                'item_size' => $item->item_size ?? 'N/A',
                'item_origen' => $item->item_origen ?? 'N/A',
                'item_destino' => $item->item_destino ?? 'N/A',
                'item_fecha_entrada' => isset($item->item_fecha_entrada) ? 
                    (is_string($item->item_fecha_entrada) ? 
                        \Carbon\Carbon::parse($item->item_fecha_entrada)->format('d/m/Y') : 
                        $item->item_fecha_entrada->format('d/m/Y')) : 'N/A',
                'item_fecha_salida' => isset($item->item_fecha_salida) ? 
                    (is_string($item->item_fecha_salida) ? 
                        \Carbon\Carbon::parse($item->item_fecha_salida)->format('d/m/Y') : 
                        $item->item_fecha_salida->format('d/m/Y')) : 'N/A',
                'item_status' => $item->item_status ?? 'N/A',
                'user' => isset($item->user) ? [
                    'user_name' => $item->user->user_name ?? '',
                    'user_last_name' => $item->user->user_last_name ?? ''
                ] : null,
                'user_modifica' => isset($item->userModifier) ? [
                    'user_name' => $item->userModifier->user_name ?? '',
                    'user_last_name' => $item->userModifier->user_last_name ?? ''
                ] : null,
                'item_fecha_modificacion' => isset($item->item_fecha_modificacion) ? 
                    (is_string($item->item_fecha_modificacion) ? 
                        \Carbon\Carbon::parse($item->item_fecha_modificacion)->format('d/m/Y H:i') : 
                        $item->item_fecha_modificacion->format('d/m/Y H:i')) : 'N/A'
            ];
        });

        $pdf = Pdf::loadView('items.reportPdf', [
            'items' => $formattedItems,
            'title' => 'Reporte de Inventario',
            'date' => now()->format('d/m/Y H:i:s'),
            'logoPath' => asset('icons/warehouse-stock-svgrepo-com.svg')
            
        ]);
        
        $filename = 'Reporte Inventario-' . now()->format('YmdHis') . '.pdf'; 
        return $pdf->setPaper('a4', 'landscape')
          ->setOption('enable_html5_parser', true)
          ->setOption('isPhpEnabled', true)
          ->setOption('isRemoteEnabled', true)
          ->setOption('defaultFont', 'Inter')
          ->setOption('dpi', 150)
          ->download($filename);
    }

    /**
     * Genera el reporte en Excel
     */
    protected function generateExcelReport($items)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte Inventario');

        // Encabezados
        $headers = [
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

        // Insertar encabezados
        $sheet->fromArray($headers, null, 'A1');

        // 👉 Aplicar color al encabezado (fila 1)
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];

        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

        // 👉 Ajustar ancho automático de columnas
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Insertar datos
        $row = 2;
        foreach ($items as $item) {
            $sheet->fromArray([
                $item->item_activo_fijo,
                $item->item_nombre,
                $item->item_descripcion,
                $item->item_size,
                $item->item_origen,
                $item->item_destino,
                optional($item->item_fecha_entrada)->format('d/m/Y'),
                optional($item->item_fecha_salida)->format('d/m/Y') ?? 'N/A',
                $item->item_status,
                $item->user ? $item->user->user_name . ' ' . $item->user->user_last_name : 'N/A',
                optional($item->item_fecha_modificacion)->format('d/m/Y H:i:s'),
            ], null, 'A' . $row);
            $row++;
        }

        // Guardar a archivo temporal y enviar descarga
        $filename = 'Reporte Inventario-' . now()->format('YmdHis') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }


    public function getFilteredItems(Request $request){
        
        if ($request->isMethod('get')) {
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'nullable|string|in:DISPONIBLE,DETENIDA,TRABAJANDO,POR SALIR,COMPRAS',
                'user_id' => 'nullable|integer|exists:users,user_id',
                'modified_user_id' => 'nullable|integer|exists:users,user_id',
                'location' => 'nullable|string|in:origin,destination',
                'location_value' => 'nullable|string|max:255',
                'date_range' => 'nullable|string|in:entry_date,modification_date,exit_date',
                'page' => 'nullable|integer'
            ]);
        } else {
            
            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'nullable|string|in:DISPONIBLE,DETENIDA,TRABAJANDO,POR SALIR,COMPRAS',
                'user_id' => 'nullable|integer|exists:users,user_id',
                'modified_user_id' => 'nullable|integer|exists:users,user_id',
                'location' => 'nullable|string|in:origin,destination',
                'location_value' => 'nullable|string|max:255',
                'date_range' => 'nullable|string|in:entry_date,modification_date,exit_date'
            ]);
        }

        $query = $this->buildReportQuery($validated);
        $items = $query->orderBy('item_fecha_modificacion', 'desc')->paginate(15);

        return view('items._preview_table', compact('items'));
    }
}