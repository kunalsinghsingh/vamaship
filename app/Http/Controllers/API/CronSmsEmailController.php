<?php

namespace App\Http\Controllers\API;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\InquiryInterface;
use Repositories\GeneralInterface;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\User;
use App\Models\InquirySource;
use App\Models\InquiryUser;
use Validator,
    Input,
    Redirect,
    DB,
    Session,
    Response,
    Mail,
    URL;

class CronSmsEmailController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $inquiryRepo;
    protected $generalRepo;

    public function __construct(InquiryInterface $inquiryRepo, GeneralInterface $generalRepo) {
        $this->inquiryRepo = $inquiryRepo;
        $this->generalRepo = $generalRepo;
        $this->middleware('auth');
    }

    public function index() {
        //
    }

    public function sendEmailToAgents() {
        $this->inquiryRepo->todayDate = date('Y-m-d');
        $users = DB::table('users')->where('status', 1)->where('enable_flag', 1)->where('user_group', 'sales_agent')->get();
        if ($users) {
            foreach ($users as $k => $v) {
                $data = $this->inquiryRepo->getData('', '', '', '', '', '', $v->id);
                $this->generalRepo->SendEmailOfInquiry($data);
            }
        }





        // print_r($excelArray);
    }

    public function sendEmailToAdmin() {
        $this->inquiryRepo->todayDate = date('Y-m-d');
        $data = $this->inquiryRepo->getData();
        $content['inquiry'] = $data;
        $content['email'] = @$email;
        $content['name'] = @$name;
        Mail::send('Email.SendDailyEmailTemplate', $content, function($message) use ($content) {
            $message->to('archana@infiniteit.biz', 'SANGAM')->from('crm@approach.com')->subject('Daily Inquiry Report');
        });
    }

    public function sendSmsToCustomer() {
        $users = $this->generalRepo->allCustomerWithSiteVisit('siteVisit');
        if ($users) {
            foreach ($users as $k => $v) {
                $this->generalRepo->sendDailySms($v->mobileNo);
            }
        }
    }

    public function getDailyFollowupReport() {
        $users = DB::table('users')->where('status', 1)->where('enable_flag', 1)->where('user_group', 'sales_agent')->get();
        if ($users) {
            foreach ($users as $k => $v) {
                $name = $v->first_name . ' ' . $v->last_name;
                $email = $v->email;
                $followupDate = date('Y-m-d');
                $data = $this->inquiryRepo->getFollowup('', $followupDate, '', '', $v->id);
                $this->generalRepo->sendEmailOfFollowup($data, $name, $email);
            }
        }
    }

    public function blockedInquiryStatus() {
        $users = DB::table('users')->where('status', 1)->where('enable_flag', 1)->where('user_group', 'sales_agent')->get();
        if ($users) {
            foreach ($users as $k => $v) {
                $this->generalRepo->salesAgent = $v->id;
                $data = $this->generalRepo->blockedInquiryStatus();

                $content['inquiry'] = $data;
                $content['email'] = @$v->mobile;
                $content['name'] = @$v->first_name;
                if (count($data) != 0) {
                    Mail::send('Email.EmailTemplateBlockedStatus', $content, function($message) use ($content) {
                        $message->to('archana@infiniteit.biz', 'Approach')->from('crm@approach.com')->subject('Blocked Flat Details');
                    });
                }
            }
        }
    }

    public function pendingInquiries() {
        $users = DB::table('users')->where('status', 1)->where('enable_flag', 1)->where('user_group', 'sales_agent')->get();
        if ($users) {
            foreach ($users as $k => $uv) {
                $this->generalRepo->salesAgent = $uv->id;
                $data = $this->generalRepo->pendingInquiries();
                $this->generalRepo->data = $data;
                $this->generalRepo->email = $uv->email;
                $this->generalRepo->template = 'Email.SendDailyPendingInquiryEmailTemplate';
                $this->generalRepo->name = $uv->first_name . ' ' . $uv->last_name;
                $this->generalRepo->TriggerMail();
            }
        }
    }

    public function pendingInquiries1() {
        $managers = DB::table('users')->where('status', 1)->where('enable_flag', 1)->where('user_group', 'sales_manager')->get();
        if ($managers) {
            foreach ($managers as $k => $v) {
                $this->generalRepo->salesManager = $v->id;
                $data = $this->generalRepo->pendingInquiries();
                $this->generalRepo->data = $data;
                $this->generalRepo->email = $v->email;
                $this->generalRepo->template = 'Email.SendDailyPendingInquiryEmailTemplate';
                $this->generalRepo->name = $v->first_name . ' ' . $v->last_name;
                //$this->generalRepo->TriggerMail();
            }
        }
    }

    public function userAuth(Request $request) {

        //App::abort(200, 'Not authenticated');
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
