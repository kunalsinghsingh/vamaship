<?php

namespace App\Http\Controllers\Admin;

use Request;
//use Illuminate\Support\Facades\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\UserInterface;
use Repositories\GeneralInterface;
use Repositories\InquiryInterface;
use App\Models\User;
use App\Models\EmailAttachment;
use App\Models\EmailTemplate;
use App\Models\Folder;
use App\Models\Uploads;
use App\Models\Campaign;
use Hash;
use Auth;
use Crypt,
    Validator,
    Input,
    Redirect,
    DB,
    Session,
    URL,
    Response,
    File,
    Schema;

class AdminUploadController extends Controller {

    protected $userRepo;

    public function __construct(UserInterface $userRepo, GeneralInterface $generalRepo, InquiryInterface $inquiryRepo) {
        $this->userRepo = $userRepo;
        $this->generalRepo = $generalRepo;
        $this->inquiryRepo = $inquiryRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function leadUploads() {
        $from = (Input::get('from') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('from')))) : '');
        $to = (Input::get('to') ? date('Y-m-d', strtotime(str_replace('/', '-', Input::get('to')))) : '');
        $uploadedBy = Input::get('uploadedBy');
        $campaign = Input::get('campaignName');
        $uploadData = Uploads::where('is_lead', 1)->orderBy('id', 'DESC');
        if ($from && $to) {
            $uploadData->whereRaw("(DATE(created_at) >= '" . date('Y-m-d', strtotime($from)) . "'  AND DATE(created_at) <= '" . date('Y-m-d', strtotime($to)) . "')");
        }
        if ($uploadedBy) {
            $uploadData->where('uploaded_by', $uploadedBy);
        }
        if ($campaign) {
            $uploadData->where('campaign_id', $campaign);
        }
        $uploads = $uploadData->get();
        $uploadedByData = DB::table('users')->whereRaw('user_group IN ("upload_manager","db_admin","admin")')->orderBy('first_name')->get();
        $CampaignData = Campaign::where('campaign_status', 3)->get();
        return view('admin.LeadUpload.UploadListingView', ['upload' => $uploads, 'uploadedBy' => $uploadedByData,'CampaignData'=>$CampaignData]);
    }

    function records($id) {
        $columns = Schema::getColumnListing('records');
        $records = DB::table('records')->where('fid', $id)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.LeadUpload.RecordListingView', compact('records', 'columns'));
    }

    function saveUpload(Request $request) {
        date_default_timezone_set('Asia/Kolkata');
        $curl = new \anlutro\cURL\cURL;

        if (Input::file('csv')) {
            $file = Input::file('csv');
            if ($file->getClientOriginalExtension() == 'csv' && $file->isValid()) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . "_" . date("Y_m_d") . "." . $file->getClientOriginalExtension();
                if ($file->move(base_path() . "/uploads", $fileName)) {
                    $upload = new Uploads();
                    $upload->filename = $fileName;
                    $upload->created_at = date('Y-m-d H:i:s');
                    $upload->save();
//                    $ins = DB::table('uploads')->insert(['filename' => $fileName, 'created_at' => date('Y-m-d H:i:s')])->lastInsertId();

                    $ins = $upload->id;
                    $records = $this->csvToArray(base_path() . "/uploads/$fileName");
                    $count = count($records);
                    $success = 0;
                    $failed = 0;

                    foreach ($records as $key => $record) {

                        $request = $curl->newRequest('post', 'https://spiceconduit.spiceworks.com/v1/leads', $record)
                                ->setOption(CURLOPT_SSL_VERIFYHOST, 0)
                                ->setOption(CURLOPT_SSL_VERIFYPEER, 0);
                        $response = $request->send();

                        $request = json_decode($response->body)->result;
                        if ($request == "success")
                            $success++;
                        else
                            $failed++;

                        $record['fid'] = $ins;
                        $record['created_at'] = date('Y-m-d H:i:s');
                        $ins1 = DB::table('records')->insert($record);
                    }

                    DB::table('uploads')->where('id', $ins)->update(['total' => $count, 'success' => $success, 'failed' => $failed]);

                    return redirect()->to('upload-master');
                } else {
                    return ['error' => 'failed'];
                }
            } else {
                return ['error' => 'Invalid File'];
            }
        } else {
            return ['error' => 'No File Uploaded'];
        }
    }

    function csvToArray($file) {
        $array = $fields = array();
        $i = 0;
        $handle = @fopen($file, "r");
        if ($handle) {
            while (($row = fgetcsv($handle, 4096)) !== false) {
                if (empty($fields)) {
                    $fields = $row;
                    continue;
                }
                foreach ($row as $k => $value) {
                    $array[$i][$fields[$k]] = $value;
                }
                $i++;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }

        return $array;
    }

    function downloadUploadCsv() {
        $pathToFile = $_SERVER['DOCUMENT_ROOT'] . '/public/admin/inquiryUploads/testUpload.csv';
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($pathToFile, 'upload.csv', $headers);
    }

}
