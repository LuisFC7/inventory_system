<?php

namespace App\Http\Services;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserService{

    public function createUser(array $data): Users{

        return Users::create([
            'user_name' => $data['name'],
            'user_last_name' => $data['lastname'],
            'user_email' => $data['email'],
            'user_department' => $data['department'],
            'user_creation' => Carbon::now(),
            'user_modification' => Carbon::now(),
            'user_tag' => $data['username'],
            'user_status' => 1,
            'user_password' => $data['password'],
        ]);

    }


}