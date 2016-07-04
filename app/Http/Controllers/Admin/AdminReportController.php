<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Support\Facades\Request;
use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\InquiryInterface;
use Repositories\UserInterface;
use Repositories\GeneralInterface;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\User;
use App\Models\InquirySource;
use Validator,
    Input,
    Redirect,
    DB,
    Response,
    Excel,
    URL;

class AdminReportController extends Controller {

    protected $inquiryRepo;
    protected $userRepo;

    public function __construct(InquiryInterface $inquiryRepo, UserInterface $userRepo) {
        $this->inquiryRepo = $inquiryRepo;
        $this->userRepo = $userRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $projects = Project::where('status', 1)->get();
        $SalesAgent = $this->userRepo->getData('', '', '', '', 'sales_agent');
        $salesManager = $this->userRepo->getData('', '', '', '', 'sales_manager');

        return view('admin/Reports/ReportView', ['projects' => $projects, 'salesAgent' => $SalesAgent, 'salesManager' => $salesManager]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
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

    public function inquiryReport() {
        $from = (Input::get('from') ? date('Y-m-d', strtotime(Input::get('from'))) : date('Y-m-01'));
        $to = (Input::get('to') ? date('Y-m-d', strtotime(Input::get('to'))) : date('Y-m-d'));
        $project = Input::get('project');
        $salesAgent = Input::get('salesAgent');
        $data = $this->inquiryRepo->getData("", "", $project, $from, $to, '', $salesAgent, "", "", "", "", "", "yes");
        $excelArray = array();
        if ($data) {
            foreach ($data as $k => $v) {
                $excelArray[$k]['Name'] = @$v->user->first_name . ' ' . @$v->user->last_name;
                $excelArray[$k]['Mobile'] = @$v->user->mobile;
                $excelArray[$k]['Email'] = @$v->user->email;
                $excelArray[$k]['Address'] = @$v->user->address;
                $excelArray[$k]['City'] = @$v->City->name;
                $excelArray[$k]['State'] = @$v->State->name;
                $excelArray[$k]['Zipcode'] = @$v->user->pin_code;
                $excelArray[$k]['Project'] = @$v->Project->name;
                $excelArray[$k]['InquiryStatus'] = @$v->InquiryStatus->name;
                $excelArray[$k]['Remark'] = @$v->operator_remarks;
                $userName = "";
                foreach ($v->InquiryUser as $user) {
                    $userName .= $user->User->first_name . ' ' . $user->User->last_name . ',';
                }
                $excelArray[$k]['SalesAgent'] = rtrim($userName,',');
                $excelArray[$k]['Medium'] = @$v->InquirySource->name;
                $excelArray[$k]['InquiryDate'] = date('d-m-Y', strtotime($v->created_at));
                $excelArray[$k]['SalesManager'] = @$v->SalesManager->first_name . ' ' . @$v->SalesManager->last_name;
            }
        } //echo '<pre>'; print_r($excelArray); exit;
        $root = $_SERVER['DOCUMENT_ROOT'] . '/acme/Reports/';
        $filename = $root . 'InquiryReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . '.csv';
        $handle = fopen($filename, 'w+');
        $data = array();
        fputcsv($handle, array('InquiryDate', 'Name', 'Mobile', 'Email', 'Address', 'City', 'State', 'Zipcode', 'InquiryStatus', 'Remark', 'SalesAgent', 'Medium'));
        foreach ($excelArray as $row) { 
            fputcsv($handle, array($row['InquiryDate'], $row['Name'], $row['Mobile'], $row['Email'], $row['Address'], $row['City'], $row['State'], $row['Zipcode'], $row['InquiryStatus'], $row['Remark'], $row['SalesAgent'], $row['Medium']));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'InquiryReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . ' .csv', $headers);
    }

    public function projectReport() {
        $from = (Input::get('from') ? date('Y-m-d', strtotime(Input::get('from'))) : date('Y-m-01'));
        $to = (Input::get('to') ? date('Y-m-d', strtotime(Input::get('to'))) : date('Y-m-d'));
        $project = Input::get('project');
        $query = Project::where('projects.status', 1)->addSelect('projects.id as project_id', 'projects.*');
        if ($project) {
            $query->where('projects.id', $project);
        }

        $query->join('enquiries', 'projects.id', '=', 'enquiries.project_id');
        $query->whereRaw("(DATE(enquiries.created_at) >= '" . date('Y-m-d', strtotime($from)) . "'  AND DATE(enquiries.created_at) <= '" . date('Y-m-d', strtotime($to)) . "')");

        $projects = $query->distinct()->get();
        $excelArray = array();

        if ($projects) {
            foreach ($projects as $k => $v) {
                $excelArray[$k]['Project'] = @$v->name;
                $excelArray[$k]['Total_Inquiries_Received'] = $this->inquiryRepo->inquiryCount($v->project_id, $from, $to, 'all');
                $excelArray[$k]['Total_Site_Visit_Done'] = $this->inquiryRepo->inquiryCount($v->project_id, $from, $to, 'siteVisit');
                $excelArray[$k]['Total_Bookings_Done'] = $this->inquiryRepo->inquiryCount($v->project_id, $from, $to, 'booking');
            }
        }
        $root = $_SERVER['DOCUMENT_ROOT'] . '/Reports/';
        $filename = $root . 'ProjectReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . '.csv';
        $handle = fopen($filename, 'w+');
        $data = array();
        fputcsv($handle, array('Project', 'Total_Inquiries_Received', 'Total_Site_Visit_Done', 'Total_Bookings_Done'));
        foreach ($excelArray as $row) {
            fputcsv($handle, array($row['Project'], $row['Total_Inquiries_Received'], $row['Total_Site_Visit_Done'], $row['Total_Bookings_Done']));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'ProjectReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . ' .csv', $headers);
    }

    public function sourceReport() {
        $from = (Input::get('from') ? date('Y-m-d', strtotime(Input::get('from'))) : date('Y-03-10'));
        $to = (Input::get('to') ? date('Y-m-d', strtotime(Input::get('to'))) : date('Y-m-d'));
        $project = Input::get('project');
        $query = InquirySource::where('inquiry_sources.status', 1)->addSelect('inquiry_sources.id as source_id', 'inquiry_sources.*');
        $query->join('enquiries', 'inquiry_sources.id', '=', 'enquiries.contact_source_id');
        $query->whereRaw("(DATE(enquiries.created_at) >= '" . date('Y-m-d', strtotime($from)) . "'  AND DATE(enquiries.created_at) <= '" . date('Y-m-d', strtotime($to)) . "')");

        $Sources = $query->distinct()->get();
        $excelArray = array();
        if ($Sources) {
            foreach ($Sources as $k => $v) {
                $excelArray[$k]['InquirySource'] = @$v->name;
                $excelArray[$k]['Inquiries_Received'] = $this->inquiryRepo->inquiryCount('', $from, $to, 'all', 1, $v->source_id);
                $excelArray[$k]['Site_Visits_Sheduled'] = $this->inquiryRepo->inquiryCount('', $from, $to, 'siteVisit', 1, $v->source_id);
                $excelArray[$k]['Bookings_Done'] = $this->inquiryRepo->inquiryCount('', $from, $to, 'booking', 1, $v->source_id);
            }
        }
        $root = $_SERVER['DOCUMENT_ROOT'] . '/Reports/';
        $filename = $root . 'MediumReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . '.csv';
        $handle = fopen($filename, 'w+');
        $data = array();
        fputcsv($handle, array('InquirySource', 'Inquiries_Received', 'Site_Visits_Sheduled', 'Bookings_Done'));
        foreach ($excelArray as $row) {
            fputcsv($handle, array($row['InquirySource'], $row['Inquiries_Received'], $row['Site_Visits_Sheduled'], $row['Bookings_Done']));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'MediumReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . ' .csv', $headers);
    }

    public function salesAgentReport() {
        $from = (Input::get('from') ? date('Y-m-d', strtotime(Input::get('from'))) : date('Y-m-01'));
        $to = (Input::get('to') ? date('Y-m-d', strtotime(Input::get('to'))) : date('Y-m-d'));
        $project = Input::get('project');
        $query = DB::table('enquiries');
        $query->select('users.id as ma_user_id', DB::raw('Concat(users.first_name," ",users.last_name) as Sales_Agent_Name'));
        $query->rightJoin('enquiry_users', 'enquiries.id', '=', 'enquiry_users.enquiry_id');
        $query->join('users', 'enquiry_users.user_id', '=', 'users.id');
        $query->where('users.status', 1);
        $query->whereRaw("(DATE(enquiries.created_at) >= '" . $from . "'  AND DATE(enquiries.created_at) <= '" . $to . "')");
        $query->groupBy('enquiry_users.user_id');
        if ($project) {
            $query->where('enquiries.project_id', $project);
        }
        $result = $query->get();
        $excelArray = array();
        if ($result) {
            foreach ($result as $k => $v) {
                $user_id = $v->ma_user_id;
                $excelArray[$k]['Sales_Agent_Name'] = $v->Sales_Agent_Name;
                $excelArray[$k]['Total_Inquiries'] = $this->inquiryRepo->agentReportCount($project, $from, $to, $user_id, 1);
                $excelArray[$k]['Inquiries_Received'] = $this->inquiryRepo->agentReportCount($project, $from, $to, $user_id, 2);
                $excelArray[$k]['Follow_Ups_Done'] = $this->inquiryRepo->agentReportFollowupCount($project, $from, $to, $user_id);
                $excelArray[$k]['Site_Visits_Sheduled'] = $this->inquiryRepo->agentReportCount($project, $from, $to, $user_id, 3);
                $excelArray[$k]['Actual_Site_Visits_Done'] = $this->inquiryRepo->agentReportCount($project, $from, $to, $user_id, 4);
                $excelArray[$k]['Bookings_Done'] = $this->inquiryRepo->agentReportCount($project, $from, $to, $user_id, 5);
            }
        }
        //echo '<pre>'; print_r($excelArray); exit;
        $root = $_SERVER['DOCUMENT_ROOT'] . '/acme/Reports/';
        $filename = $root . 'CallAgentReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . '.csv';
        $handle = fopen($filename, 'w+');
        $data = array();
        fputcsv($handle, array('Sales_Agent_Name', 'Total_Inquiries', 'Inquiries_Received', 'Follow_Ups_Done', 'Site_Visits_Sheduled', 'Actual_Site_Visits_Done', 'Bookings_Done'));
        foreach ($excelArray as $row) {
            fputcsv($handle, array($row['Sales_Agent_Name'], $row['Total_Inquiries'], $row['Inquiries_Received'], $row['Follow_Ups_Done'], $row['Site_Visits_Sheduled'], $row['Actual_Site_Visits_Done'], $row['Bookings_Done']));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'CallAgentReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . ' .csv', $headers);
    }

    public function followupReport() {

        $from = (Input::get('from') ? date('Y-m-d', strtotime(Input::get('from'))) : date('Y-m-01'));
        $to = (Input::get('to') ? date('Y-m-d', strtotime(Input::get('to'))) : date('Y-m-d'));
        $project = Input::get('project');
        $salesAgent = Input::get('sales_agent');
        $data = $this->inquiryRepo->getData("", "", $project, $from, $to, '', $salesAgent, "", "", "", "", "", "followupReport");
        $excelArray = array();
        if ($data) {
            foreach ($data as $k => $v) {
                $excelArray[$k]['Name'] = @$v->user->first_name . ' ' . @$v->user->last_name;
                $excelArray[$k]['Mobile'] = @$v->user->mobile;
                $excelArray[$k]['Email'] = @$v->user->email;
                $excelArray[$k]['Address'] = @$v->user->address;
                $excelArray[$k]['City'] = @$v->City->name;
                $excelArray[$k]['State'] = @$v->State->name;
                $excelArray[$k]['Zipcode'] = @$v->user->pin_code;
                $excelArray[$k]['Project'] = @$v->Project->name;
                $excelArray[$k]['InquiryStatus'] = @$v->InquiryStatus->name;
                $excelArray[$k]['FollowupDate'] = ($v->next_followup_date) ? date('d-m-Y', strtotime($v->next_followup_date)) : '';
                $excelArray[$k]['Remark'] = @$v->operator_remarks;
                $userName = "";
                foreach ($v->InquiryUser as $user) {
                    $userName .= $user->User->first_name . ' ' . $user->User->last_name . ',';
                }
                $excelArray[$k]['SalesAgent'] = rtrim($userName,',');
                $excelArray[$k]['Medium'] = @$v->InquirySource->name;
                $excelArray[$k]['InquiryDate'] = date('d-m-Y', strtotime($v->created_at));
                $excelArray[$k]['SalesManager'] = @$v->SalesManager->first_name . ' ' . @$v->SalesManager->last_name;
            }
        }
        $root = $_SERVER['DOCUMENT_ROOT'] . '/Reports/';
        $filename = $root . 'FollowupReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . '.csv';
        $handle = fopen($filename, 'w+');
        $data = array();
        fputcsv($handle, array('InquiryDate', 'Name', 'Mobile', 'Email', 'Address', 'City', 'State', 'Zipcode', 'InquiryStatus', 'Remark', 'SalesAgent', 'Medium'));
        foreach ($excelArray as $row) { 
            fputcsv($handle, array($row['InquiryDate'], $row['Name'], $row['Mobile'], $row['Email'], $row['Address'], $row['City'], $row['State'], $row['Zipcode'], $row['InquiryStatus'], $row['Remark'], $row['SalesAgent'], $row['Medium']));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'FollowupReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . ' .csv', $headers);
    }

    public function siteVisitReport() {
        $from = (Input::get('from') ? date('Y-m-d', strtotime(Input::get('from'))) : date('Y-m-01'));
        $to = (Input::get('to') ? date('Y-m-d', strtotime(Input::get('to'))) : date('Y-m-d'));
        $project = Input::get('project');
        $salesManager = Input::get('sales_manager');
        $query = Inquiry::select('enquiries.*');
        if ($project) {
            $query->where('project_id', $project);
        }
        if ($from && $to) {
            $query->whereRaw("(DATE(enquiries.site_visit_date) >= '" . date('Y-m-d', strtotime($from)) . "'  AND DATE(enquiries.site_visit_date) <= '" . date('Y-m-d', strtotime($to)) . "')");
        }
        if ($salesManager) {
            $query->where('enquiries.designated_sales_manager', $salesManager);
        }
        $result = $query->get();
        $excelArray = array();
        if ($result) {
            foreach ($result as $k => $v) {
                $excelArray[$k]['Name'] = @$v->user->first_name . ' ' . @$v->user->last_name;
                $excelArray[$k]['Mobile'] = @$v->user->mobile;
                $excelArray[$k]['Email'] = @$v->user->email;
                $excelArray[$k]['InquiryStatus'] = @$v->InquiryStatus->name;
                $excelArray[$k]['SiteVisitDate'] = ($v->site_visit_date) ? date('d-m-Y', strtotime($v->site_visit_date)) : '';
                $excelArray[$k]['Remark'] = @$v->operator_remarks;
                $userName = "";
                foreach ($v->InquiryUser as $user) {
                    $userName .= $user->User->first_name . ' ' . $user->User->last_name . ',';
                }
                $excelArray[$k]['SalesAgent'] = rtrim($userName,',');
                $excelArray[$k]['SalesManager'] = @$v->salesManager->first_name . ' ' . @$v->salesManager->last_name;
                $excelArray[$k]['Medium'] = @$v->InquirySource->name;
                $excelArray[$k]['SiteVisitBookedDate'] = date('d-m-Y', strtotime($v->created_at));
                $excelArray[$k]['SiteVisitDone'] = ($v->site_visit_done_flag == 1) ? 'Yes' : 'No';
                
            }
        } 
        $root = $_SERVER['DOCUMENT_ROOT'] . '/Reports/';
        $filename = $root . 'SiteVisitReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . '.csv';
        $handle = fopen($filename, 'w+');
        $data = array();
        fputcsv($handle, array('Name', 'Mobile', 'Email', 'InquiryStatus', 'Site_Visit_Booked', 'Site_Visit_Scheduled_Date', 'Remark', 'SalesAgent', 'SiteVisitDone', 'Medium'));
        foreach ($excelArray as $row) {
            fputcsv($handle, array($row['Name'], $row['Mobile'], $row['Email'], $row['InquiryStatus'], $row['SiteVisitBookedDate'], $row['SiteVisitDate'], $row['Remark'], $row['SalesAgent'], $row['SiteVisitDone'], $row['Medium']));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'SiteVisitReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . ' .csv', $headers);
    }

    public function enquiryTypeReport() {
        $from = (Input::get('from') ? date('Y-m-d', strtotime(Input::get('from'))) : date('Y-m-01'));
        $to = (Input::get('to') ? date('Y-m-d', strtotime(Input::get('to'))) : date('Y-m-d'));
        $project = Input::get('project');
        $salesAgent = Input::get('sales_agent');
        $data = $this->inquiryRepo->getData("", "", $project, $from, $to, '', $salesAgent, " ", "", "", "", "", "yes");
        $excelArray = array();
        if ($data) {
            foreach ($data as $k => $v) {
                $excelArray[$k]['SalesAgent'] = @$v->salesAgent->first_name . ' ' . @$v->salesAgent->last_name;
                $excelArray[$k]['Total_Inbound_calls'] = $this->inquiryRepo->TotalCallsCount($salesAgent, $from, $to, "inbound");
                $excelArray[$k]['Total_Outbound_calls'] = $this->inquiryRepo->TotalCallsCount($salesAgent, $from, $to, "outbound");
            }
        }
        $root = $_SERVER['DOCUMENT_ROOT'] . '/Reports/';
        $filename = $root . 'CallInquiryTypeReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . '.csv';
        $handle = fopen($filename, 'w+');
        $data = array();
        fputcsv($handle, array('SalesAgent', 'Total_Inbound_calls', 'Total_Outbound_calls'));
        foreach ($excelArray as $row) {
            fputcsv($handle, array($row['SalesAgent'], $row['Total_Inbound_calls'], $row['Total_Outbound_calls']));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'CallInquiryTypeReport' . date('d-m-Y', strtotime($from)) . '_' . date('d-m-Y', strtotime($to)) . ' .csv', $headers);
    }

}
