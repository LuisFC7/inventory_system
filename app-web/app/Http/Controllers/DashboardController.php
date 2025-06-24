<?php

namespace App\Http\Controllers;

use App\Http\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->middleware('auth'); // Protege todas las rutas del controlador
        $this->dashboardService = $dashboardService;
    }

    /**
     * Obtiene la información del usuario en formato JSON
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo(Request $request){
        try {
            
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            
            $email = $request->input('email');

            
            $userInfo = $this->dashboardService->getUserInfo($email);
            
            return response()->json([
                'success' => true,
                'data' => $userInfo
            ], 200); 
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Error de validación (email faltante o inválido)
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422); // Código HTTP 422 (datos inválidos)
            
        } catch (\Exception $e) {
            // Otros errores (ej: usuario no encontrado)
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}