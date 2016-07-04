<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use DB;
use Illuminate\Http\Request;
use Input;
use Repositories\CallCenterInterface;
use Repositories\InquiryInterface;
use Repositories\UserInterface;

class APINovanetController extends Controller
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

    public function getNovanetCalls()
    {
        $inputData        = file_get_contents('php://input');
        $callDataInsertId = DB::table('temp1')->insertGetId(array('post_value' => $inputData, 'created_at' => date('Y-m-d H:i:s')));
        
        $callData = json_decode($inputData, true);
        $post = array();
        if ($callData['contactId'] && $callData['tokenId']) //$callData['contactId'] =>leadId, $callData['tokenId'] =>agentId
        {
            $post['enquiry_id'] = $callData['contactId'];
            $post['created_by'] = $callData['tokenId'];
            $post['duration'] = $callData['duration'];
            $post['call_type'] = 'preview';
            $post['orientation_type'] = 'outbound';
            $post['dateTime'] = @date("Y-m-d H:i:s",($callData['dateTime']/1000));
            
            $insertId = $this->callCenterRepo->saveOutboundCallResponse($post);

            if($insertId){

            }else{

            }
        }
        
        echo "data received";
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
