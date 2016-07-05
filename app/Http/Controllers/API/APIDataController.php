<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Repositories\DashboardInterface;
use Repositories\InquiryInterface;
use DB;
use App\Models\Addressbook;
class APIDataController extends Controller
{
    
    public function __construct() {
       
        $this->middleware('auth.token');
    }
    
    public function index() {
        $projects = Addressbook::all()->toJson();
        return response($projects, 200)->header('Content-Type', 'application/json');
    }

    
   
}
