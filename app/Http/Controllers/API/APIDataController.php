<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Repositories\DashboardInterface;
use Repositories\InquiryInterface;
use DB;
use App\Models\Project;
class APIDataController extends Controller
{
    public $dashboardRepo;
    public $inquiryRepo;
    public function __construct(DashboardInterface $dashboardRepo,InquiryInterface $inquiryRepo) {
        $this->dashboardRepo = $dashboardRepo;
        $this->inquiryRepo = $inquiryRepo;
        $this->middleware('auth.token');
    }
    
    public function index() {
        $projects = Project::all()->toJson();
        return response($projects, 200)->header('Content-Type', 'application/json');
    }

    public function getInquriyCount(){
        $data = $this->dashboardRepo->getInquiryCountByProject();
        return (new Response($data, 200))->header('Content-Type', 'application/json');
    }
    public function getSourceCount(){
        $data = $this->inquiryRepo->getInquiryCountBySource();
        return (new Response($data, 200))->header('Content-Type', 'application/json');
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        //
        
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        //
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        
        //
        
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
        //
        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
        //
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
        //
        
    }
}
