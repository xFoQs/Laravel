<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard() {

        $users = User::all();
        return view('admin.dashboard',['users' => $users]);
    }


    public function players(){
        return view('players');
    }

}
