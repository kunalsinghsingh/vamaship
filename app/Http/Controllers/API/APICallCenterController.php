<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Repositories\CallCenterInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
class APICallCenterController extends Controller
{
    protected $callCenterRepo;
    
    public function __construct(CallCenterInterface $callCenterRepo) {
        $this->callCenterRepo = $callCenterRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $this->callCenterRepo->rawData = $dataArray = Input::get();
        
        $data['cc_callid'] = $dataArray['CallSid'];
        $data['dialstatus'] = isset($dataArray['DialCallStatus']) ? $dataArray['DialCallStatus'] : '';
        $data['project'] = isset($dataArray['project']) ? $dataArray['project'] : '';
        $data['callFrom'] = $dataArray['From'];
        $data['callTo'] = $dataArray['To'];
        $data['dialWhom'] = isset($dataArray['DialWhomNumber']) ? $dataArray['DialWhomNumber'] : '';
        $this->callCenterRepo->dataArray = $data;
        $this->callCenterRepo->dumpCallCenterRawData();
        
        //callCenterData
        $this->callCenterRepo->insertCallCenterData();
        
        //InquiryCreation
        echo 'test13';
        
        // if ($getArray) {
        // }
        $this->callCenterRepo->id = $callId = Input::get('CallSid');
        $this->callCenterRepo->getInquiryByAttribute();
        
        //dd(Input::all());
        //echo Input::get('abc');
        //if(isset($_GET['CallSid']) && )
        
        
    }
}
