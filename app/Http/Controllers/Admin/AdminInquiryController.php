<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Support\Facades\Request;
use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\InquiryInterface;
use Repositories\GeneralInterface;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Project;
use App\Models\InquiryUser;
use App\Models\Followups;
use App\Models\CallDetails;
use Validator,
    Input,
    Redirect,
    DB,
    Session,
    Response,
    Mail,
    URL;
use Log;

class AdminInquiryController extends Controller {

    protected $inquiryRepo;
    protected $generalRepo;

    public function __construct(InquiryInterface $inquiryRepo, GeneralInterface $generalRepo) {
        $this->inquiryRepo = $inquiryRepo;
        $this->generalRepo = $generalRepo;
        $this->middleware('auth');
       // date_default_timezone_set('asia/calcutta');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
        $project = Input::get('project');
        $this->inquiryRepo->campaignId = Input::get('campaign');
        $salesAgent = Input::get('salesAgent');
        $salesManager = Input::get('salesManager');
        $customer = Input::get('customer');
        $customerType = Input::get('customerType');
        $from = (Input::get('from') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('from')))) : '');
        $to = (Input::get('to') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('to')))) : '');
        $inquiryStatus = Input::get('inquiryStatus');
        $inquirySource = Input::get('inquirySource');
        $export = Input::get('export');
        $enq_id = (Input::get('id')) ? Input::get('id') : '';
        if (Session::get('userGroup') == "sales_agent") {
            $salesAgent = Session::get('userId');
        }
        if (Session::get('userGroup') == "sales_manager") {
            $salesManager = Session::get('userId');
        }
        $primary = Input::get('primaryStatus');
        $parts = explode('-', $primary);
        $cat = ($parts[0] == 'pri')?$parts[1] : '';
        $primaryStatus = ($parts[0] == 'sec')?$parts[1] : '';
        // for sangam client//
        $this->inquiryRepo->customerType = $customerType;
        $this->inquiryRepo->brokerId = Input::get('brokerId');
        //*******************//
        $data = $this->inquiryRepo->getData('', '', $project, $from, $to, $customer, $salesAgent, $salesManager, $inquirySource, $inquiryStatus, '', '', '', '', $enq_id, $primaryStatus, $cat);
        $inquiryCount = 0;//$this->inquiryRepo->getData('', '', $project, $from, $to, $customer, $salesAgent, $salesManager, $inquirySource, $inquiryStatus, '', '', '', 'count');
        $template = $this->generalRepo->templateData();
        $smsTemplate = $this->generalRepo->smsTemplateData();   
        $Attachments = $this->generalRepo->AttachmentData();
        //aecho '<pre>'; print_r($data); exit;
        if ($export == 1) {
            // $this->export($data, $from, $to);
        }
        if (count($data) == 0) {
            Session::flash('save', 'Sorry, no results found for your search.');
        } else {
            Session::flash('save', '');
        }
        return view('admin/Inquiry/InquiryListingView', ['data' => $data, 'inquiryCount' => $inquiryCount, 'template' => $template, 'attachment' => $Attachments, 'sms_template' => $smsTemplate]);
    }

    public function export() {
        $excelArray = array();
        $project = Input::get('project');
        $this->inquiryRepo->campaignId = Input::get('campaign');
        $salesAgent = Input::get('salesAgent');
        $salesManager = Input::get('salesManager');
        $customer = Input::get('customer');
        $customerType = Input::get('customerType');
        $from = (Input::get('from') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('from')))) : '');
        $to = (Input::get('to') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('to')))) : '');
        $inquiryStatus = Input::get('inquiryStatus');
        $inquirySource = Input::get('inquirySource');
        $export = Input::get('export');
        if (Session::get('userGroup') == "sales_agent") {
            $salesAgent = Session::get('userId');
        }
        if (Session::get('userGroup') == "sales_manager") {
            $salesManager = Session::get('userId');
        }
        $data = $this->inquiryRepo->getData('', '', $project, $from, $to, $customer, $salesAgent, $salesManager, $inquirySource, $inquiryStatus);
        if ($data) {
            foreach ($data as $k => $v) {
                $excelArray[$k]['Name'] = @$v->user->first_name . ' ' . @$v->user->last_name;
                $mobile = (Session::get('userGroup')=='director') ? $v->user->mobile : str_pad(substr($v->user->mobile, -4), strlen($v->user->mobile), '*', STR_PAD_LEFT);
                $excelArray[$k]['Mobile'] = @$mobile;
                $excelArray[$k]['Email'] = @$v->user->email;
                $excelArray[$k]['InquiryStatus'] = @$v->InquiryStatus->name;
                $excelArray[$k]['Remark'] = @$v->operator_remarks;
                $excelArray[$k]['SalesAgent'] = @$v->InquiryUser->User->first_name . ' ' . @$v->InquiryUser->User->last_name;
                $excelArray[$k]['Medium'] = @$v->InquirySource->name;
                $excelArray[$k]['InquiryDate'] = date('d-m-Y', strtotime($v->created_at));
                $excelArray[$k]['SalesManager'] = @$v->SalesManager->first_name . ' ' . @$v->SalesManager->last_name;
            }
        }
        //$root = $_SERVER['DOCUMENT_ROOT'] . '/leadCrm/Reports/';
        $root = $_SERVER['DOCUMENT_ROOT'] . '/cb/Reports/';
        $filename = $root . 'InquiryReport_' . date('d-m-Y') . '.csv';
        $handle = fopen($filename, 'w+');

        fputcsv($handle, array('InquiryDate', 'Name', 'Mobile', 'Email', 'InquiryStatus', 'Remark', 'SalesAgent', 'Medium'));
        foreach ($excelArray as $row) {
            fputcsv($handle, array($row['InquiryDate'], $row['Name'], $row['Mobile'], $row['Email'], $row['InquiryStatus'], $row['Remark'], $row['SalesAgent'], $row['Medium']));
        }
        //fclose($handle);
        $headers = array(
            'Content-Type' => 'application/csv'
//            'Content-Disposition' => 'attachement',
//            'filename' => $filename,
        );

        return Response::download($filename, 'InquiryReport_' . date('d-m-Y') . '.csv', $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return view('admin/Inquiry/addInquiryView');
    }
    public function editEnq() {

        $inquiryId = Request::segment(2);
        $Inquiry = Inquiry::find($inquiryId);
        return view('admin/Inquiry/editInquiryView', ['Inquiry' => $Inquiry]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function checkMobile()
    {
        $rules['mobile'] = 'required|unique:users|regex:/^[\d\-\+\s,]+$/';
        $validator = Validator::make(Input::all(), $rules);
        $userInfo = User::where('mobile',Input::get('mobile'))->first();
        if(!empty($userInfo))
        {    
            $json_array['error'] = 'error';
            $json_array['first_name'] = $userInfo->first_name;
            $json_array['last_name'] = $userInfo->last_name;
            $json_array['id'] = $userInfo->id;
            echo json_encode($json_array);
        }
        
    }
    public function store(Request $request) {
        $mob_flag = 0;
        $inquiry = new Inquiry;
        $user = new User;
        $rules = array();
        $rules = array(
            'first_name' => 'required',
            'mobile' => 'required',
            'status' => 'required'
        );
        /*if (Input::get('quickInquiry') == 0) {
            $rules['project'] = 'required';
        }*/
        if (Input::get('user_id')) {
            $rules['mobile'] = 'required';
            if (Input::get('quickInquiry') == 0)
                $rules['operator_remark'] = 'required';
        } /*else {
            $rules['mobile'] = 'required|unique:users|regex:/^[\d\-\+\s,]+$/';
        }*/
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messaage = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '_err'] = $messaage->first($key);
            }
           
        } else {
            if (!Input::get('user_id')) {
                $save_user = new User();
                $save_user->created_by = session('userId');
                $save_user->user_group = 'customer';
            } else {
                $save_user = User::find(Input::get('user_id'));
                $save_user->updated_by = session('userId');
            }
            $save_user->first_name = Input::get('first_name');
            $save_user->last_name = Input::get('last_name');
            $save_user->mobile = Input::get('mobile');
            $save_user->status = 1;
            $save_user->email = Input::get('email');
            $save_user->address = Input::get('address');
            $save_user->city = Input::get('city');
            $save_user->pin_code = Input::get('pin_code');


            if (!Input::get('user_id')) {
                $save_user->save();
                $userId = $save_user->id;
            } else {
                $success_update = $save_user->update();
                $userId = Input::get('user_id');
            }
            if (Input::get('enquiry_type_new') == 'new_enquiry') {
                Input::merge(array('enquiry_id' => ''));
            }
            if (!Input::get('enquiry_id')) {
                $inquiry = new Inquiry();
                $inquiry->created_by = session('userId');
            } else {
                $inquiry = Inquiry::find(Input::get('enquiry_id'));
                $inquiry->updated_by = session('userId');
                $inquiry->is_new = 0;
                $inquiry->follow_up_count = (int) Input::get('follow_up_count') + 1;
            }
            $inquiry->user_id = $userId;
            $inquiry->project_id = Input::get('project');
            $inquiry->campaign_id = Input::get('campaign');
            $inquiry->building_id = Input::get('building');
            $inquiry->flat_id = Input::get('flat');
            if (Input::get('quickInquiry') == 0) {
                $inquiry->contact_source_id = Input::get('contact_source_id');
            } else {
                $inquiry->contact_source_id = 13;
            }
            $inquiry->customer_type = Input::get('customer_type');
            $inquiry->agent_id = Input::get('agent_id');
            $inquiry->zone = Input::get('zone');
            $inquiry->states = Input::get('state');
            $inquiry->city = Input::get('city');
            $inquiry->budget = Input::get('budget');
            $inquiry->fund_source_id = Input::get('fund_source_id');
            $inquiry->customer_source_id = Input::get('customer_source_id');
            $inquiry->is_interested_parking = Input::get('is_interested_parking');
            $inquiry->designated_sales_manager = Input::get('designated_sales_manager');
            $inquiry->status = Input::get('status');
            $inquiry->flat_type = Input::get('flat_type');
            $inquiry->enquiry_type = Input::get('inquiry_type');
            $inquiry->site_visit_done = Input::get('site_visit_planned');
            $inquiry->site_visit_done_flag = Input::get('site_visit_done_flag');
            $inquiry->site_visit_with = Input::get('site_visit_with');
            $inquiry->no_of_members = Input::get('no_of_members'); 
            $inquiry->booking_planned = Input::get('booking_planned');
            $inquiry->booking_done = Input::get('booking_done');
            $inquiry->site_visit_date = ( Input::get('site_visit_date') != '') ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', Input::get('site_visit_date')))) : '';
            $inquiry->booking_date = (Input::get('booking_date') != '') ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', Input::get('booking_date')))) : '';
            $inquiry->next_followup_date = ((Input::get('next_followup_date') != '') ? date('Y-m-d H:i:s', strtotime(Input::get('next_followup_date'))) : '');
            $remark = array();
            $remark['remark'] = Input::get('operator_remark');
            $remark['date'] = date('d-M-Y H:i:s');
            $remark['by'] = session('userName');
            $remark['status'] = $inquiry->InquiryStatus->name;
            $arr = json_encode($remark);
            $inquiry->operator_remark = $arr;
            $hidden_site_visit_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', Input::get('hidden_site_visit_date'))));
            $hidden_booking_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', Input::get('hidden_booking_date'))));
            $inquiry->interested_projects = json_encode(Input::get('interested_projects'));
            $inquiry->created_by = session('userId');
            if (!Input::get('enquiry_id')) {
                $inquiry->save();
                $inquiryId = $inquiry->id;
                //for sangam-crm
//                $msg = 'Sangam Lifespaces thanks you for visiting us @ MCHI. We will soon be in touch regarding your enquiry. For more info pls call 07666706000';
//                $this->generalRepo->sendSms(Input::get('mobile'), $msg);
            } else {
                $success_update = $inquiry->update();
                $inquiryId = Input::get('enquiry_id');
            }
            if(Input::get('status')==6)
            {
                $data = Inquiry::where('flat_id',Input::get('flat'))->where('id','!=',$inquiryId)->get();
                foreach($data as $inq)
                {
                    $remark = '##' . date('d-M-Y H:i') . ' : ' . session('userName') . ' (' . ucwords(str_replace('_', ' ', session('userGroup'))) . ')' . ' : ' . $inquiry->InquiryStatus->name. ' : Tentatively Booked Flat '.Input::get('flat').' is Booked by '.Input::get('first_name').' '.Input::get('last_name').' on '.date('d-M-Y H:i:s').$inq->operator_remark;
                    Inquiry::where('id',$inq->id)->update(array('flat_id'=>0,'operator_remark'=>$remark));
                }
            }
            
            //===========================Insetion of site visit followup====================
            if(Input::get('site_visit_planned') == 1 && Input::get('site_visit_date')!='' && Input::get('site_visit_date')!=Input::get('hidden_site_visit_date'))
            {
                DB::table('followups')->where(array('enq_id'=>$inquiryId, 'followup_type'=>'site visit'))->update(array('is_active'=>0, 'remark' => 'Site visit date changed'));
                DB::table('followups')->insert(array('enq_id'=>$inquiryId, 'followup_type'=>'site visit', 'followup_date'=>Input::get('site_visit_date'), '_with'=>Input::get('site_visit_with'), 'no_of_members'=>Input::get('no_of_members'), 'status'=>Input::get('status'), 'is_active'=>1, 'remark' => 'Site visit planned'));
            }
            else if(Input::get('site_visit_done_flag') == 1 && Input::get('site_visit_actual_date')!='')
            {
                DB::table('followups')->where(array('enq_id'=>$inquiryId, 'followup_type'=>'site visit','is_active'=>1))->update(array('actual_date'=>date('Y-m-d H:i:s',strtotime(Input::get('site_visit_actual_date'))), 'is_active'=>0, 'remark'=> 'Site visit done'));
            }
            else if(Input::get('site_visit_planned') == 2)
            {
                DB::table('followups')->where(array('enq_id'=>$inquiryId, 'followup_type'=>'site visit','is_active'=>1))->update(array('is_active'=>0, 'remark'=> 'Site visit cancelled'));
            }
            //===========================Insertion of booking followup========================
            if(Input::get('booking_planned') == 1 && Input::get('booking_date')!='' && Input::get('booking_date')!=Input::get('hidden_booking_date'))
            {
                DB::table('followups')->where(array('enq_id'=>$inquiryId, 'followup_type'=>'booking'))->update(array('is_active'=>0));
                DB::table('followups')->insert(array('enq_id'=>$inquiryId, 'followup_type'=>'booking', 'followup_date'=>Input::get('booking_date'), 'status'=>Input::get('status'), 'is_active'=>1, 'remark'=>'Booking planned'));
            }
            else if(Input::get('booking_done') == 1 && Input::get('actual_booking_date')!='')
            {
                DB::table('followups')->where(array('enq_id'=>$inquiryId, 'followup_type'=>'booking','is_active'=>1))->update(array('actual_date'=>date('Y-m-d H:i:s',strtotime(Input::get('actual_booking_date'))), 'is_active'=>0, 'remark'=>'Booking done'));
            }
            else if(Input::get('booking_planned') == 2)
            {
                DB::table('followups')->where(array('enq_id'=>$inquiryId, 'followup_type'=>'booking','is_active'=>1))->update(array('is_active'=>0, 'Remark'=>'Booking cancelled'));
            }
            
            //========================Insertion of normail followup entry=======================
            if((Input::get('site_visit_planned') == 0 || Input::get('site_visit_planned') == '') && (Input::get('site_visit_done_flag') == '' || Input::get('site_visit_done_flag') == 0) && (Input::get('booking_planned') == 0 || Input::get('booking_planned') == '') && (Input::get('booking_done') == 0 || Input::get('booking_done') == ''))
            {
                if(Input::get('next_followup_date') != '' && Input::get('next_followup_date') != Input::get('hidden_next_followup_date'))
                {
                    DB::table('followups')->where(array('enq_id'=>$inquiryId, 'followup_type'=>'normal followup', 'is_active' => 1))->update(array('is_active'=>0));
                    DB::table('followups')->insert(array('enq_id'=>$inquiryId, 'followup_type'=>'normal followup', 'followup_date'=>Input::get('next_followup_date'), 'status'=>Input::get('status'), 'is_active'=>1, 'remark' => Input::get('operator_remark')));
                }
                else
                { 
                    DB::table('followups')->where(array('enq_id'=>$inquiryId, 'followup_type'=>'normal followup', 'is_active' => 1))->update(array('is_active'=>0));
                }
            }
            
            $InquiryEmail = array();
            $InquiryArray = Inquiry::find($inquiryId);
//            echo URL::to('/') . '/public/admin/ProjectStorage/' . @$InquiryArray->Project->broucher;
//            die;
//            if(Input::get('project')){
//            $InquiryEmail['email'] = Input::get('email');
//            $InquiryEmail['Project'] = @$InquiryArray->Project->name;
//            $InquiryEmail['ProjectId'] = @$InquiryArray->project_id;
//            $InquiryEmail['Broucher'] = URL::to('/') . '/public/admin/ProjectStorage/' . @$InquiryArray->Project->broucher;
//            $InquiryEmail['BroucherName'] = @$InquiryArray->Project->broucher;
//            $InquiryEmail['User'] = @$InquiryArray->User->first_name . ' ' . @$inquiryId->User->last_name;
//            $this->generalRepo->sendEmail($InquiryEmail);
//            }

            $this->inquiryRepo->flatId = Input::get('hidden_flat_id');
            $this->inquiryRepo->flatStatus = Input::get('status');
            $this->inquiryRepo->ChangeFlatStatus();
            DB::table('enquiry_users')->where('enquiry_id', $inquiryId)->delete();
            if (!empty(Input::get('sales_agent'))) {
                foreach (Input::get('sales_agent') as $k => $v) {
                    DB::table('enquiry_users')->insert(
                            array('user_id' => $v, 'enquiry_id' => $inquiryId)
                    );
                }
            }
            else
            {
                DB::table('enquiry_users')->insert(
                            array('user_id' => session('userId'), 'enquiry_id' => $inquiryId)
                        );
            }
            $json_array['error'] = 'success';
        }

        echo json_encode($json_array);
    }
    public function updateInq(Request $request)
    { 
        if(Input::get('cust_info') == 1)
        {
            $rules = array(
                'first_name' => 'required',
                'mobile' => 'required'
            );
        }
        else
        {
            $rules = array(
                'operator_remark' => 'required'
            );
        }
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messaage = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '_err'] = $messaage->first($key);
            }
           
        } else {
            if(Input::get('cust_info') == 1)
            {
                //user info update
                $save_user = User::find(Input::get('user_id'));
                $save_user->updated_by = session('userId');
                $save_user->first_name = Input::get('first_name');
                $save_user->last_name = Input::get('last_name');
                $save_user->mobile = Input::get('mobile');
                $save_user->status = 1;
                $save_user->email = Input::get('email');
                $save_user->address = Input::get('address');
                $save_user->city = Input::get('city');
                $save_user->pin_code = Input::get('pin_code');
                $success_update = $save_user->update();
                $userId = Input::get('user_id');
                
                //Inquiry info update
                $inquiry = Inquiry::find(Input::get('enquiry_id'));
                $inquiry->updated_by = session('userId');
                $inquiry->is_new = 0;
                $inquiry->follow_up_count = (int) Input::get('follow_up_count') + 1;
                $inquiry->user_id = $userId;
                $inquiry->project_id = Input::get('project');
                $inquiry->campaign_id = Input::get('campaign');
                $inquiry->building_id = Input::get('building');
                $inquiry->flat_id = Input::get('flat');
                $inquiry->contact_source_id = Input::get('contact_source_id');
                $inquiry->customer_type = Input::get('customer_type');
                $inquiry->agent_id = Input::get('agent_id');
                $inquiry->zone = Input::get('zone');
                $inquiry->states = Input::get('state');
                $inquiry->city = Input::get('city');
                $inquiry->budget = Input::get('budget');
                $inquiry->fund_source_id = Input::get('fund_source_id');
                $success_update = $inquiry->update();
                $inquiryId = Input::get('enquiry_id');
                $this->inquiryRepo->flatId = Input::get('hidden_flat_id');
                $this->inquiryRepo->flatStatus = Input::get('status');
                $this->inquiryRepo->ChangeFlatStatus();
                $json_array['name'] = $save_user->first_name.' '.$save_user->last_name;
                $json_array['mob_email'] = $save_user->email.' / '.$save_user->mobile;
                $json_array['project'] = (Input::get('project'))?$inquiry->Project->name:'-';
                $json_array['budget'] = (Input::get('budget'))?$inquiry->Budget->name:'-';
                $json_array['source'] = (Input::get('contact_source_id'))?$inquiry->InquirySource->name:'-';
                $json_array['error'] = 'success';
            }
            else
            {
                $inquiry = Inquiry::find(Input::get('enquiry_id'));
                $remark = array();
                $remark['remark'] = Input::get('operator_remark');
                $remark['date'] = date('d-M-Y H:i:s');
                $remark['by'] = session('userName');
                $remark['status'] = $inquiry->InquiryStatus->name;
                $arr = json_encode($remark);
                $inquiry->operator_remark = $arr.','.Input::get('hidden_operator_remarks');
                $inquiry->next_followup_date = ((Input::get('next_followup_date') != '') ? date('Y-m-d H:i:s', strtotime(Input::get('next_followup_date'))) : '');
                $inquiry->status = Input::get('status');
                $inquiry->update();
                $json_array['error'] = 'success';
                $json_array['redirect'] = 'yes';
            }
        
        }
        echo json_encode($json_array);
    }
    public function getMiscalls() {
        $data = CallDetails::where('dialstatus','Not Answered')->paginate(10);
        $data->setPath('miscalls');
        return view('admin/Inquiry/MiscallListingView')->with('data',$data);
    }

    public function getProjectPreview() {
        return view('admin/ProjectPreview/ProjectPreview');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    public function ajaxInquiry() {
        $json_array['error'] = 'error';
        $enquiry_id = Input::get('enquiry_id');
        if ($enquiry_id) {
            $Inquiry = $this->inquiryRepo->getData($enquiry_id);
//            dd($Inquiry);
            $json_array['error'] = 'success';
            return View('admin.Inquiry.ajaxInquiryDetailView', ['Inquiry' => $Inquiry]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    function getUserData($id = '') {
        $query = User::select("users.*");
        if (isset($_GET['term']) && $_GET['term'] != '') {
            $keyword = urlencode($_GET['term']);
            $query->where('users.user_group', 'customer');
            $query->whereRaw("users.first_name LIKE '%" . $keyword . "%' OR users.last_name LIKE '%" . $keyword . "%' OR users.mobile LIKE '%" . $keyword . "%'");
        }
        $user = $query->get();
        return $user;
    }

    public function getFollowups() {
        $project = Input::get('project');
        $source = Input::get('source');
        $customer = Input::get('customer');
        $page = Input::get('page', 1);
        $paginate = 2;
        $typeCondition = Input::get('followup_type');
        $inquirySource = Input::get('inquirySource');
        $followupDate = (Input::get('followup_date') ? date('Y-d-m', strtotime(Input::get('followup_date'))) : '');
        $id = Input::get('id');
        //echo '<pre>'; print_r($followup); exit;
        if (Session::get('userGroup') == 'sales_agent') {
            $salesAgent = Session::get('userId');
        } else {
            $salesAgent = Input::get('salesAgent');
        }
        $followup = $this->inquiryRepo->getFollowup($typeCondition, $inquirySource, $project, $followupDate, $customer, $salesAgent, $id);
        $followupCount = count($followup);

        if (count($followup) == 0) {
            Session::flash('save', 'Sorry, no results found for your search.');
        } else {
            Session::flash('save', '');
        }
        return view('admin/Inquiry/FollowupListingView', ['followup' => $followup, 'typeCondition' => $typeCondition, 'followupCount' => $followupCount]);
    }

    function searchUser() {
        $json_array = array();
        $data = $this->getUserData();
        if ($data) {
            foreach ($data as $key => $value) {
                $json_array[] = array(
                    'label' => $value->first_name . ' (' . $value->mobile . ')',
                    'id' => $value->id,
                    'value' => $value->first_name,
                    'first_name' => $value->first_name,
                    'last_name' => $value->last_name,
                    'email' => $value->email,
                    'mobile' => $value->mobile,
                    'address' => $value->address,
                    'address' => $value->state,
                );
            }
        }
        echo json_encode($json_array);
    }

    public function sendSms() {
        $msg = Input::get('sms_text');
        $mobNo = Input::get('mobile');
        $template = Input::get('template');
        $name = Input::get('name');
        //if ($msg && $mobNo) {
            $this->generalRepo->sendSms($mobNo, $name, $template);
            $json_array['error'] = 'success';
            Session::flash('save', 'Sms sent successfully.');
        /*} else {
            $json_array['error'] = 'error';
        }*/
        echo json_encode($json_array);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    public function ajaxCity() {
        $stateId = Input::get('state_id');
        $city = DB::table('city')->where('state_id', $stateId)->orderBy('name', 'ASC')->get();
        $json_array['city'] = $city;
        echo json_encode($json_array);
    }

    function ajaxProjectUsers() {
        $project_id = Input::get('project_id');
        $json_array['error'] = 'error';
        if ($project_id && is_numeric($project_id)) {
            $json_array['error'] = 'success';
            $json_array['users'] = $this->projectUser($project_id);
        }
        echo json_encode($json_array);
    }

    function projectUser($project_id) {
        $result = array();
        if ($project_id && is_numeric($project_id)) {
            $json_array['error'] = 'sucess';
            $query = DB::table('users')
                    ->select('users.id', 'users.first_name', 'users.last_name')
                    ->join('user_projects', 'users.id', '=', 'user_projects.user_id')
                    ->where('user_projects.project_id', $project_id)
                    ->where('users.status', 1)
                    ->where('users.enable_flag', 1)
                    ->where('users.user_group', 'sales_agent');
            $result = $query->get();
        }

        return $result;
    }

    function ajaxBuildings() {
        $projectId = Input::get('projectId');
        $buildings = DB::table('buildings')->where('project_id', $projectId)->get();

        $json_array['buildings'] = $buildings;
        echo json_encode($json_array);
    }
    function ajaxCampaigns() {
        $dt = date('Y-m-d H:i:s');
        $projectId = Input::get('projectId');
        $query = DB::table('campaign_projects')
                ->select('campaign_projects.campaign_id','campaigns.name')
                ->join('campaigns','campaigns.id','=','campaign_projects.campaign_id')
                ->where('campaigns.from_date','<=',$dt)
                ->where('campaigns.to_date','>=',$dt)
                ->where('campaigns.status',1)
                ->where('campaign_projects.project_id',$projectId);
        $result = $query->get();
        echo json_encode($result);
    }

    function ajaxFlats() {
        $building_id = Input::get('building_id');
        $flats = DB::table('flats')
                ->select('flats.*', 'flat_status.name')
                ->join('flat_status', 'flats.status', '=', 'flat_status.id')
                ->where('flats.building_id', $building_id)
                ->whereRaw('(flats.status=1 OR  flats.status=3 OR flats.status=4)')
                ->orderBy('flats.id', 'ASC')
                ->get();
        $json_array['flats'] = $flats;
        echo json_encode($json_array);
    }
    function ajaxFlatType() {
        $flat_id = Input::get('flat_id');
        $flats = DB::table('flats')
                ->select('flats.*', 'flat_status.name')
                ->join('flat_status', 'flats.status', '=', 'flat_status.id')
                ->where('flats.id', $flat_id)
                ->whereRaw('(flats.status=1 OR  flats.status=3)')
                ->orderBy('flats.id', 'ASC')
                ->get();
        echo json_encode($flats);
    }
    function transferInquiry() {
        $inquiryId = Input::get('enquiry_id');
        $userId = Input::get('user_id');

        if ($inquiryId) {
            foreach ($inquiryId as $ek => $ev) {
                DB::table('enquiry_users')->where('enquiry_id', $ev)->delete();
                if ($userId != 0) {
                    DB::table('enquiry_users')->insert(
                            array('user_id' => $userId, 'enquiry_id' => $ev)
                    );
                }
            }
        }
        Session::flash('save', 'Inquiry Transfered successfully.');
        echo 1;
    }

    public function bulkUpload() {

        if (Input::hasFile('file')) {
            $file = Input::file('file');
            $name = time() . '-' . $file->getClientOriginalName();
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/leadCrm/public/admin/inquiryUploads/';
            //chmod($filePath, 0777);
            $file->move($filePath, $name);

            return $this->_import_csv($filePath, $name);
        } else {
            echo "Please select file";
        }
    }
    
    

    private function _import_csv($path, $filename) {
        $csv_file = $path . $filename;
        $invalidArray = array();
        if (($handle = fopen($csv_file, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($col = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $projId = $this->inquiryRepo->getProjectInfoFromCsv($col[5]);
                if($col[1]=='')
                    $col[6] = 'Firstname cannot be empty';
                elseif($col[3] == '')
                    $col[6] = 'Mobile number cannot be empty';
                elseif($projId == 0)
                    $col[6] = "Project doesn't exist";
                
                if(isset($col[6])) {
                    array_push($invalidArray, $col);
                } else {
                    $userId = $this->inquiryRepo->getUserInfoFromCsv($col[1], $col[3], $col[4]);
                    DB::table('enquiries')->insert(
                            array(
                                'user_id' => $userId,
                                'project_id' => $projId,
                                'status' => 7,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'))
                    );
                }
               
                
            }
            
            fclose($handle);
            $this->downloadInvalidCsv($invalidArray);
            Session::flash("save", "Inquiries uploaded successfully");

            return Redirect::back();
        } else {
            echo "Error being upload file";
        }



        // echo "File data successfully imported to database!!";
    }
    public function downloadInvalidCsv($invalidArray)
    {
        if(count($invalidArray)>0)
        {
            $root = base_path() . '/uploads/InvalidRecords/';
            $filename = $root . 'InvalidRecordsReport_' . date('d-M-Y') . '_' . time() . '.csv';
            $saveFile = 'InvalidRecordsReport_' . date('d-M-Y') . '_' . time() . '.csv';
            $handle = fopen($filename, 'w+');
            $data = array();
            fputcsv($handle, array('Sr No.', 'FirstName', 'LastName', 'Mobile', 'Email', 'Project', 'Error'));
            foreach ($invalidArray as $row) {
                fputcsv($handle, array($row['0'], $row['1'], $row['2'], $row['3'], $row['4'], $row['5'], $row[6]));
            }
            fclose($handle);
            header('Content-Type: application/csv');
    //            header('Content-Disposition: attachment; filename=InvalidRecordsReport_' . date('d-M-Y').'.csv');
            header('Pragma: no-cache');
        }
    }
    public function ajaxUserBySalesManager() {
        $managerId = Input::get('managerId');
        $data = DB::table('users')->where('manager_id', $managerId)->where('user_group', 'sales_agent')->where('enable_flag', 1)->where('status', 1)->get();
        $json_array['users'] = $data;
        return json_encode($json_array);
    }

    public function delete() {
        $inquiryId = Input::get('inquiryId');
        $followupCount = Followups::where('enq_id',$inquiryId)->count();
        $callCount = CallDetails::where('enq_id',$inquiryId)->count();
        if($followupCount == 0 && $callCount == 0)
        {
            Inquiry::where('id', $inquiryId)->delete();
            InquiryUser::where('enquiry_id', $inquiryId)->delete();
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    public function exportExcel() {
        
    }
    public function externalInquirySave()
    {
        $usr = User::where('mobile',$_POST['phone'])->first();
        $response = array();
        if($usr)
        {
            $response['error'] = "Phone number already in use";
        }
        else
        {
            Log::info('Showing user profile for user: '.json_encode(Input::all()));
            $user = new User();
            $user->first_name = $_POST['name'];
            $user->mobile = $_POST['phone'];
            $user->email = $_POST['email'];
            $user->save();
            $userId = $user->id;
            $enq = new Inquiry();
            $enq->user_id = $userId;
            $enq->status = 7;
            $enq->created_by = 78;
            $enq->save();
            $enqId = $enq->id;
            if($_POST['enquiry_Source'] == "Email")
                $enq->contact_source_id = 11;
            elseif($_POST['enquiry_Source'] == "Hoardings")
                $enq->contact_source_id = 2;
            elseif($_POST['enquiry_Source'] == "Online")
                $enq->contact_source_id = 5;
            elseif($_POST['enquiry_Source'] == "Newspaper")
                $enq->contact_source_id = 1;
            elseif($_POST['enquiry_Source'] == "Property Portals")
                $enq->contact_source_id = 15;
            elseif($_POST['enquiry_Source'] == "Reference")
                $enq->contact_source_id = 3;
            elseif($_POST['enquiry_Source'] == "SMS")
                $enq->contact_source_id = 9;
            DB::table('enquiry_users')->insert(
                        array('user_id' => 78, 'enquiry_id' => $enqId)
                    );
            $response['success'] = "Enquiry submitted successfully";
        }
        return $response;
    }
    public function getSecondary()
    {
        $primary = Input::get('primary');
        $table = Input::get('table');
        $html = "<option value=''>Select</option>";
        $data = DB::table($table)->where('parent_id',$primary)->get();
        foreach($data as $val)
        {
            $html.="<option value='$val->id'>$val->name</option>";
        }
        echo $html;
    }
}
