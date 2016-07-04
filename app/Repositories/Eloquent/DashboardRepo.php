<?php

namespace Repositories\Eloquent;

use Repositories\DashboardInterface;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Flat;
use App\Models\Followups;
use DB,
    Session;

class DashboardRepo implements DashboardInterface {

    public $projectId = '';
    public $from = '';
    public $to = '';
    public $salesAgent = NULL;
    public $saleManager = NULL;
    public $inquiryStatus = NULL;
    public $campaignId = NULL;
    public $dashboardType = NULL;
    public $flatId = NULL;

    public function dashboardInquiryCount($userId = "", $type = "", $from = "", $to = "", $project = "", $countType = "") {
        $query = DB::table('enquiries');
        if ($userId) {
            $query->join('enquiry_users', 'enquiries.id', '=', 'enquiry_users.enquiry_id');
            if (Session::get('userGroup') == 'sales_manager') {
                $query->where('enquiries.designated_sales_manager', $userId);
            } else {
                $query->where('enquiry_users.user_id', $userId);
            }
        }
        switch ($type) {
            case 'this_month':
                $query->whereRaw('YEAR(enquiries.created_at)=YEAR(NOW()) AND MONTH(enquiries.created_at)=MONTH(NOW())');
                break;

            case 'this_year':
                $query->whereRaw('YEAR(enquiries.created_at)=YEAR(NOW())');
                break;

            case 'site_visit_this_month':
                $query->whereRaw('YEAR(enquiries.site_visit_date)=YEAR(NOW()) AND MONTH(enquiries.site_visit_date)=MONTH(NOW())');
                $query->where('site_visit_done', 1);
                break;

            case 'site_visit_this_year':
                $query->whereRaw('YEAR(enquiries.site_visit_date)=YEAR(NOW())');
                $query->where('site_visit_done', 1);
                break;

            case 'site_visit_till_date':
                $query->where('site_visit_done', 1);
                break;

            case 'booking_this_month':
                $query->where('booking_done', 1);
                $query->whereRaw('YEAR(enquiries.booking_date)=YEAR(NOW()) AND MONTH(enquiries.booking_date)=MONTH(NOW())');
                break;

            case 'booking_this_year':
                $query->where('booking_done', 1);
                $query->whereRaw('YEAR(enquiries.booking_date)=YEAR(NOW())');
                break;

            case 'booking_till_date':
                $query->where('booking_done', 1);
                break;

            case 'warm_enquiry':
                $query->where('status', 1);
                break;

            case 'hot_enquiry':
                $query->where('status', 2);
                break;

            case 'qualified_enquiry':
                $query->where('status', 8);
                break;
            
             case 'booked_enquiry':
                $query->where('status', 6);
                break;

            case 'received_enquiry':
                $query->where('status', 7);
                break;

            case 'all_inquiry':
                break;
        }
        if ($from && $to) {
            if ($countType == "siteVisit") {
                $query->whereRaw("(DATE(enquiries.site_visit_date) >= '" . $from . "'  AND DATE(enquiries.site_visit_date) <= '" . $to . "')");
            } else if ($countType == "booking") {
                $query->whereRaw("(DATE(enquiries.booking_date) >= '" . $from . "'  AND DATE(enquiries.booking_date) <= '" . $to . "')");
            } else {
                $query->whereRaw("(DATE(enquiries.created_at) >= '" . $from . "'  AND DATE(enquiries.created_at) <= '" . $to . "')");
            }
        }
        if ($project) {
            $query->where('enquiries.project_id', $project);
        }
        if ($this->campaignId) {
            $query->where('enquiries.campaign_id', $this->campaignId);
        }
        $result = $query->count();
        return $result;
    }

    public function getSiteVisitBookingCount($userId = "", $type = "", $from = "", $to = "", $project = "", $countType = "") {
        $query = DB::table('followups');
        $query->join('enquiries', 'enquiries.id', '=', 'followups.enq_id');
        if ($userId) {
            $query->join('enquiry_users', 'enquiries.id', '=', 'enquiry_users.enquiry_id');
            if (Session::get('userGroup') == 'sales_manager') {
                $query->where('enquiries.designated_sales_manager', $userId);
            } else {
                $query->where('enquiry_users.user_id', $userId);
            }
        }
        switch ($type) {
            case 'site_visit_this_month':
                $query->whereRaw('YEAR(followups.followup_date)=YEAR(NOW()) AND MONTH(followups.followup_date)=MONTH(NOW())');
                $query->where('followups.is_active', 1);
                break;

            case 'site_visit_this_year':
                $query->whereRaw('YEAR(followups.followup_date)=YEAR(NOW())');
                $query->where('followups.followup_type', 'site visit');
                $query->where('followups.is_active', 1);
                break;

            case 'site_visit_till_date':
                $query->where('followups.followup_type', 'site visit');
                $query->where('followups.is_active', 1);
                break;

            case 'booking_this_month':
                $query->where('followups.followup_type', 'booking');
                $query->whereRaw('YEAR(followups.followup_date)=YEAR(NOW()) AND MONTH(followups.followup_date)=MONTH(NOW())');
                $query->where('followups.is_active', 1);
                break;

            case 'booking_this_year':
                $query->where('followups.followup_type', 'booking');
                $query->whereRaw('YEAR(followups.followup_date)=YEAR(NOW())');
                $query->where('followups.is_active', 1);
                break;

            case 'booking_till_date':
                $query->where('followups.followup_type', 'booking');
                $query->where('followups.is_active', 1);
                break;
        }
        if ($project) {
            $query->where('enquiries.project_id', $project);
        }
        if ($this->campaignId) {
            $query->where('enquiries.campaign_id', $this->campaignId);
        }
        $result = $query->count();
        return $result;
    }

    public function salesAgentChart($userId = "", $createdAt = "", $project = "", $from = "", $to = "") {
        
    }

    public function getTodaysSummaryCount($datecondition = '', $projcondition = '', $count = '') {
        $query = Inquiry::Select('enquiries.*', 'enquiries.created_at');
        $query->join('enquiry_users', 'enquiries.id', '=', 'enquiry_users.enquiry_id');
        if (Session::get('userGroup') == 'sales_agent') {
            $query->where('enquiry_users.user_id', Session::get('userId'));
        }
        if (Session::get('userGroup') == 'sales_manager') {
            $query->where('enquiries.designated_sales_manager', Session::get('userId'));
        }
        $query->whereRaw($datecondition . ' ' . $projcondition);

        if ($count == 1) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
        return $result;
    }
    
    public function getTodaysSummaryCountFollowup($datecondition = '', $projcondition = '', $count = ''){
        $query = Followups::Select('followups.*', 'followups.created_at');
        $query->join('enquiries', 'enquiries.id', '=', 'followups.enq_id');
        $query->join('enquiry_users', 'enquiries.id', '=', 'enquiry_users.enquiry_id');
        if (Session::get('userGroup') == 'sales_agent') {
            $query->where('enquiry_users.user_id', Session::get('userId'));
        }
        if (Session::get('userGroup') == 'sales_manager') {
            $query->where('enquiries.designated_sales_manager', Session::get('userId'));
        }
        $query->whereRaw($datecondition . ' ' . $projcondition);

        if ($count == 1) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
        return $result;
    }

    public function getleadAnalysisInquiry($project_id) {
        $data = Project::where('status', 1)->get();
        foreach ($data as $k => $v) {
            
        }
    }

    public function getInquiryCountByProject() {
        $query1 = Inquiry::select('id as enquiry', DB::raw('count(*) as total'));
        if ($this->projectId) {
            $query1->where('project_id', $this->projectId);
        }
        $result1 = $query1->get()->toArray();

        $query2 = Inquiry::select('site_visit_done', DB::raw('count(*) as total'))->where('site_visit_done', 1);
        if ($this->projectId) {
            $query2->where('project_id', $this->projectId);
        }
        $result2 = $query2->get()->toArray();
        ;
        $query3 = Inquiry::select('booking_done', DB::raw('count(*) as total'))->where('booking_done', 1);
        if ($this->projectId) {
            $query3->where('project_id', $this->projectId);
        }
        $result3 = $query3->get()->toArray();


        $result = array_merge($result1, $result2, $result3);
        return json_encode($result);
    }

    public function getDashboardType($dashboardType = '', $project = '', $campaign = '', $agent = '', $source = '') {
        Switch ($dashboardType) {
            case "projects":
                $query = DB::table('projects')->where('status', 1);
                if ($project) {
                    $query->where('id', $project);
                }
                break;
            case "campaign":
                $query = DB::table('campaigns');
                if ($campaign) {
                    $query->where('id', $campaign);
                }
                break;
            case "salesAgent":
                $query = DB::table('users')->Select("users.id", "users.first_name as name")->where('users.status', 1)->where('users.user_group', 'sales_agent');
                if ($agent) {
                    $query->where('id', $agent);
                }
                break;
            case "source":
                $query = DB::table('inquiry_sources')->where('status', 1);
                if ($source) {
                    $query->where('id', $source);
                }
                break;
        }
        $result = $query->get();
        return $result;
    }

    function getFlatData() {
        $flat = Flat::where('id', $this->flatId)->first();
        return $flat;
    }

}

?>