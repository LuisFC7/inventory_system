<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller{

    protected $userService;

    public function __construct(UserService $userService){
        $this -> userService = $userService;
    }

    // This controller create an user with rules
    public function registerUser(Request $request){

        $validator = Validator::make($request -> all(), [
            'name' => 'required|string|max: 255',
            'lastname' => 'required|string|max: 255',
            'username' => 'required|string|max: 255',
            'password' => [
                            'required',
                            'string',
                            'min:8',
                            'max:255',
                            'confirmed',
                            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
                        ],
            'email' => 'required|string|max: 255',
            'department' => 'required|string|max: 255',
                    ]);

        if($validato->fails()){
            return response()  -> json([
                'success' =>  false,
                'errors' => $validator->errors()
            ], 422);
        }
    }


}