<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CallDetails;
use App\Models\Inquiry;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Input;
use Repositories\CallCenterInterface;
use Repositories\InquiryInterface;
use Repositories\UserInterface;
use Log;
class APIOzontelController extends Controller
{

    public $userRepo;
    public $inquiryRepo;
    public $callCenterRepo;
    public function __construct(Request $request, UserInterface $userRepo, InquiryInterface $inquiryRepo, CallCenterInterface $callCenterRepo)
    {
        $this->userRepo       = $userRepo;
        $this->inquiryRepo    = $inquiryRepo;
        $this->callCenterRepo = $callCenterRepo;
    }
    public function getImmediateOzontelCall()
    {
        $inputString = '{"ucid":"806146639050953","callerID":"2225062280","did":"912233574757","skillName":"concerete_buildr","agentUniqueID":"null","dataID":"null","campaignID":"21169","monitorUcid":"80614663905095","phoneName":"jigar1","agentPhoneNumber":"9820313041","type":"inbound","uui":"-","http:\/\/concrete-crm_clu_pw\/api\/v1\/get-immediate-call":"2225062280","agentID":"60442","customer":"concerete_buildr"}'; //json_encode(Input::all());
        $arr = json_decode($inputString);
        $dt = date('Y-m-d H:i:s');
        DB::table('temp1')->insert(
            array("post_value" => $inputString,
                  "created_at"   => $dt)
        );
        $agent = User::where('agent_phone_name',$arr->phoneName)->first();
        //echo $agent->id; exit;
        $cnt = CallDetails::where('cc_callid',$arr->monitorUcid)->count();
        if($cnt == 0)
        {
            $user = User::where('mobile', $arr->callerID)->first();
            if ($user) 
            {
                $userId = $user->id;
                $enq = Inquiry::where('user_id',$userId)->first();
                if($enq)
                { 
                    Inquiry::where('user_id',$userId)->update(array('updated_at' => date('Y-m-d H:i:s')));
                    $enqId = $enq->id;
                }
                else
                { 
                    $enq             = new Inquiry();
                    $enq->user_id    = $userId;
                    $enq->status     = 7;
                    $enq->created_by = $agent->id;
                    $enq->cc_callid  = $arr->monitorUcid;
                    $enq->save();
                    $enqId = $enq->id;
                }
            }
            else
            {
                $user             = new User();
                $user->created_by = $agent->id;
                $user->mobile     = $arr->callerID;
                $user->user_group = 'customer';
                $user->save();
                $userId          = $user->id;
                $enq             = new Inquiry();
                $enq->user_id    = $userId;
                $enq->status     = 7;
                $enq->created_by = $agent->id;
                $enq->cc_callid  = $arr->monitorUcid;
                $enq->save();
                $enqId = $enq->id;

                DB::table('enquiry_users')->insert(
                    array('user_id' => $agent->id, 'enquiry_id' => $enqId)
                );
            }
            $call = new CallDetails();
            $call->cc_callid = $arr->monitorUcid;
            $call->customer_mobile = $arr->callerID;
            $call->enq_id = $enqId;
            $call->user_id = $agent->id;
            $call->save();
        }
        else
        {
            $call = CallDetails::where('cc_callid',$arr->monitorUcid)->update(array('customer_mobile'=>$arr->callerID));
        }
    }

    public function getOzontelCalls()
    {
        $inquiryArray = array('status' => 1);
        if (Input::has('data')) {
            $inputString = Input::get('data');
        } else {
            $inputString = json_encode(Input::all());
        }
        $dt  = date('Y-m-d H:i:s');
        $arr = json_decode($inputString);
        DB::table('temp1')->insert(
            array("post_value" => $inputString,
                "created_at" => $dt)
        );
        if ($inputString) {
            $agent = User::where('agent_phone_name',$arr->PhoneName)->first();
            $user = User::where('mobile', $arr->CallerID)->first();
            if ($user) 
            {
                $userId = $user->id;
                $enq = Inquiry::where('user_id',$userId)->first();
                if($enq)
                {
                    Inquiry::where('cc_callid', $arr->monitorUCID)->update(array('updated_at' => date('Y-m-d H:i:s'), "cc_callid" => $arr->monitorUCID));
                    $enqId = $enq->id;
                }
                else
                {
                    $enq             = new Inquiry();
                    $enq->user_id    = $userId;
                    $enq->status     = 7;
                    $enq->created_by = $agent->id;
                    $enq->cc_callid  = $arr->monitorUCID;
                    $enq->save();
                    $enqId = $enq->id;
                    DB::table('enquiry_users')->insert(
                        array('user_id' => $agent->id, 'enquiry_id' => $enqId)
                    );
                }
            }
            else
            {
                    $user             = new User();
                    $user->created_by = $agent->id;
                    $user->mobile     = $arr->CallerID;
                    $user->user_group = 'customer';
                    $user->save();
                    $userId          = $user->id;
                    $enq             = new Inquiry();
                    $enq->user_id    = $userId;
                    $enq->status     = 7;
                    $enq->created_by = $agent->id;
                    $enq->cc_callid  = $arr->monitorUCID;
                    $enq->save();
                    $enqId = $enq->id;
                
                    DB::table('enquiry_users')->insert(
                        array('user_id' => $agent->id, 'enquiry_id' => $enqId)
                    );
            }
            $cnt = CallDetails::where('cc_callid',$arr->monitorUCID)->count();
            if($cnt == 0)
            {
                $call = new CallDetails();
                $call->cc_callid = $arr->monitorUCID;
                $call->customer_mobile = $arr->CallerID;
                $call->enq_id = $enqId;
                $call->dialstatus = $arr->DialStatus;
                $call->start_time = $arr->StartTime;
                $call->end_time = $arr->EndTime;
                $call->duration = $arr->Duration;
                $call->audio_file = $arr->AudioFile;
                $call->phone_name = $arr->PhoneName;
                $call->user_id = $agent->id;
                $call->save();
            }
            else 
            {
                $call = CallDetails::where('cc_callid',$arr->monitorUCID)->update(array('customer_mobile'=>$arr->CallerID, 'enq_id'=>$enqId, "dialstatus" => $arr->DialStatus, "start_time" => $arr->StartTime, "end_time" => $arr->EndTime, "duration" => $arr->Duration, "audio_file" => $arr->AudioFile, "phone_name" => $arr->PhoneName, "customer_mobile" => $arr->CallerID));
            }
        }
        else
        {
            echo "invalid data";
        }
    }

    
    public function outboundCallResponse()
    {

    }

    public function createUser()
    {
    }

    public function createInquiry()
    {
    }

    public function updateInquiry()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //

    }
}
