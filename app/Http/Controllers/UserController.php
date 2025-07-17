<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Rules\UserRules;

class UserController extends Controller{

    protected $userService;

    public function __construct(UserService $userService){
        $this -> userService = $userService;
    }

    // This controller create an user with rules
    public function registerUser(Request $request){
        
        $allowedFields = UserRules::allowedFields();

        $receivedFields = array_keys($request->all());
        $extraFields = array_diff($receivedFields, $allowedFields);
        
        if (!empty($extraFields)) {
            return response()->json([
                'success' => false,
                'error' => 'Campos no permitidos',
                'extra_fields' => array_values($extraFields)
            ], 422);
        }

        $validator = Validator::make($request->all(), UserRules::registerRules());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            
            $filteredData = $request->only($allowedFields);
            $user = $this->userService->createUser($filteredData);
            
            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => [
                    'username' => $user->user_tag,
                    'email' => $user->user_email,
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}