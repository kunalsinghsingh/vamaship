<?php

namespace App\Http\Controllers\Admin;

use Request;
//use Illuminate\Support\Facades\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\UserInterface;
use Repositories\GeneralInterface;
use Repositories\CronInterface;
use Repositories\InquiryInterface;
use App\Models\User;
use App\Models\EmailAttachment;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\Folder;
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
    File;

class AdminMastersController extends Controller {

    protected $userRepo;

    public function __construct(UserInterface $userRepo, GeneralInterface $generalRepo, InquiryInterface $inquiryRepo, CronInterface $cronRepo) {
        $this->userRepo = $userRepo;
        $this->generalRepo = $generalRepo;
        $this->inquiryRepo = $inquiryRepo;
        $this->cronRepo = $cronRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function index() {
        Session::put('limit', '');
        if (isset($_GET['page']) && is_numeric($_GET['page']))
            Session::put('limit', $_GET['page']);
        $tables = array('user_group', 'loan_status', 'agents', 'parking_status', 'inquiry_status', 'inquiry_sources', 'customer_sources', 'interest_type', 'fund_sources', 'flat_types', 'flat_status', 'contact_sources', 'zones', 'amenities', 'projects', 'budgets', 'project_building_status');
        $table_name = Request::segment(3);
        if (!in_array($table_name, $tables)) {
//            echo 'sadsa';
            return redirect('dashboard');
        }
        $master_search = Input::get('master_search');

        $heading = Request::segment(2);
        $master = $this->getMaster($table_name, '', $master_search);

        return View('admin.masters.MastersListingView', ['master' => $master, 'heading' => $heading, 'table_name' => $table_name]);
    }

    function manageMaster() {
        $heading = Request::segment(2);
        $table_name = Request::segment(3);
        $id = Request::segment(5);
        $master = $this->getMaster($table_name, $id);
        return View('admin.masters.ManageMastersView', ['master' => $master, 'heading' => $heading, 'table_name' => $table_name]);
    }

    function getMaster($table = '', $id = '', $master_search = '') {
        if ($id) {
            $data = DB::table($table)->where('id', $id)->first();
        } else {
            $query = DB::table($table);
            if ($master_search) {
                $query->whereRaw('name LIKE  "%' . $master_search . '%"');
            }
            $data = $query->paginate(10);
            $data->setPath($table);
        }

        return $data;
    }

    function saveMasters() {
        $json_array['error'] = 'error';
        $rules = array(
            'name' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messaage = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '_err'] = $messaage->first($key);
            }
        } else {
            $name = Input::get('name');
            $status = Input::get('status');
            $table = Input::get('table');
            $heading = Input::get('heading');
            $id = Input::get('id');
            $json_array['error'] = 'success';
            if (!$id) {
                if($table == 'inquiry_status' || $table == 'inquiry_sources')
                {
                    $arr = array(
                            'name' => $name,
                            'status' => $status,
                            'is_primary' => Input::get('is_primary'),
                            'cat_id' => Input::get('category'),
                            'parent_id' => Input::get('parent_id'),
                            'created_by' => Session::get('userId'),
                            'created_at' => date('Y-m-d H:i:s')
                        );
                }
                else
                {
                    $arr = array(
                            'name' => $name,
                            'status' => $status,
                            'created_by' => Session::get('userId'),
                            'created_at' => date('Y-m-d H:i:s')
                        );
                }
                DB::table($table)->insert(
                    $arr
                );
                Session::flash('save', ucwords($heading) . ' saved successfully.');
            } else {
                if($table == 'inquiry_status')
                {
                    $arr = array('name' => $name,
                                'status' => $status,
                                'is_primary' => Input::get('is_primary'),
                                'cat_id' => Input::get('category'),
                                'parent_id' => Input::get('parent_id'),
                                'updated_by' => Session::get('userId'),
                                'updated_at' => date('Y-m-d H:i:s'));
                }
                else
                {
                    $arr = array('name' => $name,
                                'status' => $status,
                                'updated_by' => Session::get('userId'),
                                'updated_at' => date('Y-m-d H:i:s'));
                }
                DB::table($table)
                        ->where('id', $id)
                        ->update($arr);
                Session::flash('save', ucwords($heading) . ' updated successfully.');
            }
        }
        echo json_encode($json_array);
    }

    function emailAttachment() {
        $id = Request::segment(2);
        $query = DB::table('email_attachment');
        if ($id) {
            $query->where('id', $id);
        }
        $data = $query->first();
        $folderName = Folder::where('id', $data->folder_id)->first();
        return view('admin.Attachment.EmailAttachementView', ['attachment' => $data, 'FolderName' => $folderName]);
    }

    function saveAttachment() {
        $id = Input::get('id');
        $folderId = Input::get('folderId');
        if ($id) {
            $emailAttach = EmailAttachment::find(Input::get('id'));
            $folderName = Folder::where('id', $emailAttach->folder_id)->first();
            $fileName = $emailAttach->name;
            $old_path = $_SERVER['DOCUMENT_ROOT'] . '/public/admin/Attachment/' . $folderName->name . '/' . $emailAttach->file_name;
        } else {
            $emailAttach = new EmailAttachment();
        }
        if ($folderId) {
            $folderName = Folder::where('id', $folderId)->first();
        }
        $new_path = $_SERVER['DOCUMENT_ROOT'] . '/public/admin/Attachment/' . $folderName->name . '/' . $emailAttach->file_name;
        if ($id) {
            File::move($old_path, $new_path);
        }
        //dd(Input::hasFile('attachfile'));
        if (Input::hasFile('attachfile')) {
            $file = Input::file('attachfile');
            $fileExtention = Input::file('attachfile')->getClientOriginalExtension();
            $fileName = str_replace('.' . $fileExtention, '', Input::file('attachfile')->getClientOriginalName());
            $this->generalRepo->folder = $folderName->name;
            $this->generalRepo->Attachment($file);
            $emailAttach->file_name = Input::file('attachfile')->getClientOriginalName();
        } else if (!$id) {
            Session::flash('response-msg', 'Please select file to upload!!');
            $redirect = '/folder-doc/' . $folderId;
            return redirect($redirect);
        }
        if ($folderId) {
            $emailAttach->folder_id = $folderId;
            $emailAttach->name = $fileName;
            $emailAttach->status = 1;
        }
        else
        {
            $emailAttach->name = Input::get('name');
            $emailAttach->status = Input::get('status');
            $emailAttach->created_by = Session::get('userId');
        }
        if ($id) {
            $emailAttach->update();
        } else {
            $emailAttach->save();
        }

        if ($folderId) {
            $redirect = '/folder-doc/' . $folderId;
        } else {
            $redirect = 'emailAttach';
        }
        return redirect($redirect);
    }

    function AttachmentListing() {
        $data = EmailAttachment::where('status', 1)->paginate(10);
        $data->setPath('Attachments');
        return view('admin.Attachment.AttachmentListingView', ['attach' => $data]);
    }

    function folderListing() {
        $data = Folder::where('status', 1)->paginate(10);
        $data->setPath('Folder');
        return view('admin.Attachment.FolderListingView', ['folders' => $data]);
    }

    function createFolder() {
        $id = Request::segment(2);
        $data = Folder::where('id', $id)->where('status', 1)->first();
        return view('admin.Attachment.AddFolderView', ['attach' => $data]);
    }

    function EmailTemplate() {
        $data = EmailTemplate::paginate(10);
        return view('admin.EmailTemplate.TemplateListingView', ['attach' => $data]);
    }

    function AddEmailTemplate() {
        $id = Request::segment(2);
        $data = EmailTemplate::where('id', $id)->where('status', 1)->first();
        return view('admin.EmailTemplate.AddEmaiTemplateView', ['template' => $data]);
    }

    function saveEmailTemplate() {
        $rules = array(
            'name' => 'required',
            'subject' => 'required',
            'content' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messaage = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '_err'] = $messaage->first($key);
            }
            echo json_encode($json_array);
        } else {
            if (Input::get('id')) {
                $emailTemplate = EmailTemplate::find(Input::get('id'));
                $emailTemplate->updated_by = session('userId');
            } else {
                $emailTemplate = new EmailTemplate();
            }

            $emailTemplate->name = Input::get('name');
            $emailTemplate->status = Input::get('status');
            $emailTemplate->subject = Input::get('subject');
            $emailTemplate->content = Input::get('content');
            $emailTemplate->created_by = Session::get('userId');
            if (Input::get('id')) {
                $emailTemplate->update();
            } else {
                $emailTemplate->save();
            }
        return redirect('/email-template');
        }
    }
    
    function SmsTemplate() {
        $data = SmsTemplate::where('status', 1)->paginate(10);
        return view('admin.SmsTemplate.TemplateListingView', ['attach' => $data]);
    }

    function AddSmsTemplate() {
        $id = Request::segment(2);
        $data = SmsTemplate::where('id', $id)->where('status', 1)->first();
        return view('admin.SmsTemplate.AddSmsTemplateView', ['template' => $data]);
    }

    function saveSmsTemplate() {
        $rules = array(
            'name' => 'required',
            'content' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messaage = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '_err'] = $messaage->first($key);
            }
            echo json_encode($json_array);
        } else {
            if (Input::get('id')) {
                $smsTemplate = SmsTemplate::find(Input::get('id'));
                $smsTemplate->updated_by = session('userId');
            } else {
                $smsTemplate = new SmsTemplate();
            }

            $smsTemplate->name = Input::get('name');
            $smsTemplate->status = Input::get('status');
            $smsTemplate->content = Input::get('content');
            $smsTemplate->created_by = Session::get('userId');
            if (Input::get('id')) {
                $smsTemplate->update();
            } else {
                $smsTemplate->save();
            }
        return redirect('/sms-template');
        }
    }

    function folderDocument() {
        $folderId = Request::segment(2);
        $data = EmailAttachment::where('folder_id', $folderId)->get();
        $folderData = Folder::where('id', $folderId)->where('status', 1)->first();
        $folderName = $folderData->name;

        return view('admin.Attachment.folderDocumentView', ['attach' => $data, 'folderName' => $folderName]);
    }

    function saveFolder() {
        $folder = new Folder();
        $folderName = Input::get('folder_name');

        if (!empty(Input::get('folder_name'))) {
            $data = Folder::whereRaw('name LIKE "%' . $folderName . '%"')->where('status', 1)->first();
            if ($data) {
                Session::flash('folder-response', 'Folder name already exist!');
                $json_array['error'] = 'success';
            } else {
                $folder->name = $folderName;
                $folder->status = 1;
                $folder->save();
                $json_array['error'] = 'success';
                mkdir($_SERVER['DOCUMENT_ROOT'].'/public/admin/Attachment/'.$folderName);
            }
        } else {
            Session::flash('folder-response', 'Please insert folder name!!');
            $json_array['error'] = 'success';
        }
        echo json_encode($json_array);
    }

    function downloadFile() {
        Input::get('folder');
        $this->generalRepo->folder = Request::segment(2);
        $this->generalRepo->fileName = Request::segment(3);
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/public/admin/Attachment/' . Request::segment(2) . '/' . Request::segment(3);
        //        substr($filePath, $start);
        $extensn = strrchr(Request::segment(3), '.');
        $fextn = str_replace('.', '', $extensn);

        $mimeTypes = array(
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'html' => 'text/html',
            'exe' => 'application/octet-stream',
            'zip' => 'application/zip',
            'doc' => 'application/msword',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'gif' => 'image/gif',
            'png' => 'image/png',
            'jpeg' => 'image/jpg',
            'jpg' => 'image/jpg',
            'php' => 'text/plain'
        );


        $headers = array(
            "Content-Type:image/jpg"
//            'Content-Disposition' => 'attachement',
//            'filename' => $filename,
        );
        return Response::download($filePath, Request::segment(3), $headers);
//        $this->generalRepo->getDownload();
    }

    function triggerMail() {
        $inquiryId = Input::get('inquiryId');
        $template = Input::get('template');
        $data = $this->inquiryRepo->getData($inquiryId);
        $this->generalRepo->data = $data;
        $this->generalRepo->name = $data->User->first_name;
        $this->generalRepo->email = $data->User->email;
        $this->generalRepo->lastName = $data->User->lastName;
        $this->generalRepo->projectName = ($data->Project) ? $data->Project->name : '';
        $this->generalRepo->buildingName = ($data->Building) ? $data->Building->name : '';
        $filePath = array();
        if($data->User->email!='')
        {
            if (Input::get('attachmentVal')) {
                foreach (Input::get('attachmentVal') as $k => $v) {
                    $this->generalRepo->attachmentId = $v;
                    $fileData = $this->generalRepo->AttachmentData();
    //           print_r($fileData);
                    $filePath[$k] = $_SERVER['DOCUMENT_ROOT'] . '/public/admin/Attachment/' . $fileData->folder->name . '/' . $fileData->file_name;
    //            $filePath[$k] = 'localhost/leadCrm/public/admin/Attachment/' . $fileData->folder->name . '/' . $fileData->file_name;
                }
            }

            if ($template) {
                $this->generalRepo->attachments = $filePath;
                $this->generalRepo->templateId = $template;
                $templateContent = $this->generalRepo->templateData();
                $this->generalRepo->template = 'Email.EmailTemplateView';
                if($template != 'custom')
                {
                    $this->generalRepo->templateContent = $templateContent->content;
                    $this->generalRepo->mailSub = $templateContent->subject;
                }
                else
                {
                    $this->generalRepo->templateContent = Input::get('custom_content');
                    $this->generalRepo->mailSub = 'Inquiry Email';
                }
                $this->generalRepo->TriggerMail();
            } else {
                Session::flash('ErrorMsg', 'Please select template to send');
    //            return false;
            }
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
    public function followUpEmailCron()
    {
        $this->cronRepo->followUpEmail();
    }
}
