<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Support\Facades\Request;
use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\InquiryInterface;
use Repositories\GeneralInterface;
use App\Models\Campaign;
use App\Models\CampaignVn;
use App\Models\CampaignVnAgents;
use App\Models\CampaignProjects;
use App\Models\CampaignSources;
use App\Models\User;
use App\Models\Inquiry;
use Validator,
    Input,
    Redirect,
    DB,
    Session;

class AdminCampaignController extends Controller {

    protected $inquiryRepo;

    public function __construct(InquiryInterface $inquiryRepo) {
        $this->inquiryRepo = $inquiryRepo;
        $this->middleware('auth');
        Session::forget('campaignName');
        Session::forget('campaignFrom');
        Session::forget('campaignTo');
        Session::forget('campaignProject');
        Session::forget('campaignBudget');
        //date_default_timezone_set('asia/calcutta');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
        Session::put('campaignName', '');
        Session::put('campaignFrom', '');
        Session::put('campaignTo', '');
        Session::put('campaignProject', '');
        Session::put('campaignBudget', '');
        $campaign_name = Input::get('campaign_name');
        $project = Input::get('project');
        $fromDate = Input::get('from') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('from')))) : '';
        $toDate = Input::get('to') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('to')))) : '';
        $query = Campaign::select('campaigns.*');
        if ($campaign_name) {
            $query->whereRaw('campaigns.name LIKE "%' . $campaign_name . '%"');
        }
        if ($project) {
            $query->where('campaigns.project_id', $project);
        }
        if ($fromDate && $toDate) {
            $query->whereRaw("(DATE(created_at) >= '" . $fromDate . "'  AND DATE(created_at) <= '" . $toDate . "')");
        }
        $campaigns = $query->paginate(100);
        $campaigns->setPath('campaigns');
        $campaignsCount = $query->count();
        return view('admin/campaign/CampaignListingView', ['campaigns' => $campaigns, 'campaignsCount' => $campaignsCount]);
    }

    public function addCampaign($id = "") {
        $campaignVirtualNumbers = DB::table('vn_allocation')->where('alloted_flag', 0)->get();
        $campaignNumbers = DB::table('campaign_vn')->select('campaign_vn.*')->addSelect('campaigns.name as campaign_name')->leftJoin('campaigns', 'campaign_vn.campaign_id', '=', 'campaigns.id')->get();
        $campaigns = DB::table('campaigns')->where('id', $id)->first();
        $CampaignProjects =CampaignProjects::where('campaign_id',$id)->get();
        $CampaignSources =CampaignSources::where('campaign_id',$id)->get();
        $salesAgentVn = array();
        foreach ($campaignNumbers as $vn) {
            $salesAgentVn[$vn->vn_id] = DB::table('campaign_vn_agents')->where('campaign_vn_id', $vn->vn_id)->get();
        }
        $sessionFormData = Session::get('CampaignForm');

        return view('admin/campaign/addCampaignView', ['campaignVirtualNumbers' => $campaignVirtualNumbers, 'campaigns' => $campaigns, 'campaignNumbers' => $campaignNumbers, 'salesAgentVn' => $salesAgentVn, 'sessionFormData' => $sessionFormData,'CampaignProjects'=>$CampaignProjects,'CampaignSources'=>$CampaignSources]);
    }

    public function saveCampaign() {
        $rules = array();
        $rules = array(
            'campaign_name' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messaage = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '_err'] = $messaage->first($key);
            }
        } else {
            if (!Input::get('id')) {
                $campaign = new Campaign;
            } else {
                $campaign = Campaign::find(Input::get('id'));
                $campaign->updated_by = session('userId');
            }
            $campaign->budget = Input::get('budget');
            $campaign->spent = Input::get('spent');
            $campaign->status = Input::get('status');
//            $campaign->inquiry_source_id = Input::get('inquiry_source_id');
            $campaign->from_date = Input::get('from') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('from')))) : '';
            $campaign->to_date = Input::get('to') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('to')))) : '';
//            $campaign->project_id

            $campaign->name = Input::get('campaign_name');
            if (!Input::get('id')) {
                $campaign->save();
                $campaignId = $campaign->id;
            } else {
                $success_update = $campaign->update();
                $campaignId = Input::get('id');
            }
            $vn_ids = json_decode(Input::get('vn_ids'));

            foreach ($vn_ids as $k => $vk) {
                DB::table('campaign_vn_agents')->where('campaign_vn_id', $vk)->delete();
                if (Input::get('sales_agent_' . $vk)) {
                    foreach (Input::get('sales_agent_' . $vk) as $kv) {
                        DB::table('campaign_vn_agents')->insert(
                                array('user_id' => $kv, 'campaign_id' => $campaignId, 'campaign_vn_id' => $vk)
                        );
                    }
                }
                DB::table('vn_allocation')->where('id', $vk)->update(array('alloted_flag' => 1));
                DB::table('campaign_vn')->where('vn_id', $vk)->update(array('campaign_id' => $campaignId));
            }
            if (Input::get('project')) {
                DB::table('campaign_projects')->where('campaign_id', $campaignId)->delete();
                foreach (Input::get('project') as $k => $v) {
                    DB::table('campaign_projects')->insert(
                            array('project_id' => $v, 'campaign_id' => $campaignId)
                    );
                }
            }
            DB::table('campaign_sources')->delete();
            if (Input::get('inquiry_source_id')) {
                foreach (Input::get('inquiry_source_id') as $k => $v) {
                    DB::table('campaign_sources')->insert(
                            array('source_id' => $v, 'campaign_id' => $campaignId)
                    );
                }
            }
            $json_array['error'] = 'success';
        }
        Session::put('campaignName', '');
        Session::put('campaignFrom', '');
        Session::put('campaignTo', '');
        Session::put('campaignProject', '');
        Session::put('campaignBudget', '');

        echo json_encode($json_array);
    }

    public function virtualNumberSearch() {
        $query = DB::table('vn_allocation');
        $keyword = Input::get('term');
        $query->whereRaw("vn_allocation.vn_numbers LIKE '%" . $keyword . "%'");
        $query->where('alloted_flag', 0);
        $Vnumber = $query->get();
        return $Vnumber;
    }

    public function addVnCampaign() {
        $vnNumers = Input::get('checkedNumbers');

        foreach ($vnNumers as $k => $v) {
            if ($v)
                DB::table('campaign_vn')->insert(array(
                    'virtual_phon_number' => $v[0],
                    'vn_id' => $v[1]
                ));
        }
        echo 1;
    }

    public function addSessionData() {
        $campaingForm = array();
        Session::put('CampaignName', Input::get('campaign_name'));
        Session::put('CampaignInquirySource', Input::get('inquiry_source_id'));
        Session::put('CampaignBudget', Input::get('budget'));
        Session::put('CampaignProject', Input::get('project'));
        Session::put('CampaignFromDate', Input::get('from'));
        Session::put('CampaignToDate', Input::get('to'));
        Session::put('CampaignForm', $campaingForm);
        $sessionFormData = Session::get('CampaignForm');
    }

    public function removeVnCampaign() {
        $id = Input::get('id');
        $vnId = Input::get('vnId');
        DB::table('campaign_vn')->where('id', $id)->delete();
        DB::table('vn_allocation')->where('id', $vnId)->update(array('alloted_flag' => 0));
        DB::table('campaign_vn_agents')->where('campaign_vn_id', $id)->delete();
        $json_array['error'] = 'success';
        echo json_encode($json_array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
}
