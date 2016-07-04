<?php

namespace App\Http\Controllers\Admin;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

use Validator,
    Input,
    Redirect,
    DB,
    Session;

class AdminDashboardController extends controller {


    function index() {
       
        return view('admin/dashboard/dashboard');
    }


}
