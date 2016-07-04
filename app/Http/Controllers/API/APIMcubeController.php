<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\UserInterface;
use Repositories\InquiryInterface;
use Repositories\CallCenterInterface;

use App\Models\User;
use App\Models\Project;
use Input, DB;
class APIMcubeController extends Controller
{
    
    public $userRepo;
    public $inquiryRepo;
    public $callCenterRepo;
    public function __construct(Request $request, UserInterface $userRepo, InquiryInterface $inquiryRepo, CallCenterInterface $callCenterRepo) {
        $this->userRepo = $userRepo;
        $this->inquiryRepo = $inquiryRepo;
        $this->callCenterRepo = $callCenterRepo;
    }
    
    public function getMcubeCalls() {
        
        //callcenter_raw_data
        DB::table('callcenter_raw_data')->insert(array('raw_data' => json_encode(Input::all())));
        $data = array('callid' => '', 'gid' => '', 'callfrom' => '', 'callto' => '', 'dialstatus' => '');
        $userArray = array('mobile' => '', 'status' => 1);
        $inquiryArray = array('status' => 1);
        $inputString = json_encode(Input::all());//'{"data":"{\"callid\":\"97693680331446119637\",\"calid\":\"129\",\"refid\":\"129\",\"bid\":\"Njc1\",\"gid\":\"Luxor\",\"assignto\":\"Ravindra\",\"eid\":\"Ravindra\",\"source\":\"Sangam\",\"landingnumber\":\"7666706000\",\"hid\":\"97693680331446119638\",\"callfrom\":\"9769368033\",\"callto\":\"9820156980\",\"starttime\":\"2015-10-29 17:24:07\",\"endtime\":\"2015-10-29 17:24:11\",\"pulse\":\"0\",\"callername\":\"\",\"callerbusiness\":\"\",\"calleraddress\":\"\",\"remark\":\"\",\"sms_content\":\"\",\"caller_email\":\"\",\"status\":\"1\",\"keyword\":\"\",\"filename\":\"\",\"exefeedback\":\"\",\"custfeedback\":\"\",\"dialstatus\":\"CANCEL\",\"rate\":\"0\",\"callback\":\"0\",\"leadid\":\"0\",\"tktid\":\"0\",\"last_modified\":\"0000-00-00 00:00:00\",\"lead\":\"\",\"suptkt\":\"\",\"businessname\":\"KBJ Developers\",\"asto\":\"2\",\"duration\":\"0\",\"empnumber\":\"9820156980\",\"empid\":\"2\",\"geid\":\"1\",\"grid\":\"2\",\"empemail\":\"ravindra.nagilla@sangamrealty.com\",\"region\":\"MU\",\"apikey\":\"\"}"}';
        if ($inputString) {
            $inputString = json_decode($inputString, true);
            if (isset($inputString['data'])) {
                $inputString = json_decode($inputString['data']);
                foreach ($data as $k2 => $v) {
                    foreach ($inputString as $k1 => $item) {
                        if ($k2 == $k1) $data[$k1] = $item;
                    }
                }
                $projectId = 1;
                $project = Project::where('name', trim($data['gid']))->first();
                if ($project) $projectId = $project->id;
                
                $userArray['mobile'] = $this->userRepo->mobile = $data['callto'];
                $userArray['user_group'] = $this->userRepo->type = 'sales_agent';
                $getCCUser = $this->userRepo->getUserByAttribute();
                if ($getCCUser) {
                    $ccuserId = $getCCUser->id;
                } 
                else {
                    $this->userRepo->dataArray = $userArray;
                    $ccuserId = $this->userRepo->createUser();
                }
                
                $userArray['mobile'] = $this->userRepo->mobile = $data['callfrom'];
                $userArray['user_group'] = $this->userRepo->type = 'customer';
                $getCCCustomers = $this->userRepo->getUserByAttribute();
                if ($getCCCustomers) {
                    $ccCustomerId = $getCCCustomers->id;
                } 
                else {
                    $this->userRepo->dataArray = $userArray;
                    $ccCustomerId = $this->userRepo->createUser();
                }
                $this->callCenterRepo->callId = $data['callid'];
                $this->callCenterRepo->from = $data['callfrom'];
                $this->callCenterRepo->to = $data['callto'];
                $getCCinquiry = $this->callCenterRepo->getInquiryByAttribute();
                $inquiryArray['created_by'] = $ccuserId;
                $inquiryArray['user_id'] = $ccCustomerId;
                $inquiryArray['project_id'] = $this->callCenterRepo->projectId = $projectId;
                $inquiryArray['status'] = 7;
                $inquiryArray['contact_source_id'] = 8;
                $inquiryArray['cc_callid'] = $data['callid'];
                $this->callCenterRepo->inquiryArray = $inquiryArray;
                if ($getCCinquiry) {
                    $this->callCenterRepo->inquiryId = $getCCinquiry->id;
                    $this->callCenterRepo->updateInquiry();
                } 
                else {
                    $agentInquiry = $this->callCenterRepo->checkInquiryByProjectCustomerAgent();

                    
                    if ($agentInquiry) {
                        $this->callCenterRepo->inquiryId = $agentInquiry->enquiry_id;
                        $this->callCenterRepo->updateInquiry();
                    } 
                    else {
                        $this->callCenterRepo->createInquiry();
                    }
                }
            }else{
                echo "Invalid data";
            }
        }else{
            echo "Invalid data";
        }
    }
    
    public function createUser() {
    }
    
    public function createInquiry() {
    }
    
    public function updateInquiry() {
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
