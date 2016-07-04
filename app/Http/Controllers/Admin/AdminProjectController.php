<?php

namespace App\Http\Controllers\Admin;

use Request;
//use Illuminate\Support\Facades\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\UserInterface;
use Repositories\GeneralInterface;
use App\Models\User;
use App\Models\Project;
use Hash;
use Auth;
use Crypt,
    Validator,
    Input,
    Redirect,
    DB,
    Session,
    File,
    URL;

class AdminProjectController extends Controller {

    protected $userRepo;

    public function __construct(UserInterface $userRepo, GeneralInterface $generalRepo) {
        $this->userRepo = $userRepo;
        $this->generalRepo = $generalRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function index() {
        $query = new Project();
        //$query = Project::where('status', 1);
        $masterSearch = Input::get('master_search');
        if ($masterSearch) {
            $query->whereRaw('name LIKE  "%' . $masterSearch . '%"');
        }
        $projects = $query->paginate(10);
        $projects->setPath('projects');
        return View('admin.Project.ProjectsListingView', ['project' => $projects]);
    }

    function create() {
        $id = Request::segment(2);
        $query = DB::table('projects');

        //$query->where('status', 1);
        if ($id) {
            $query->where('id', $id);
            $projects = $query->first();
        } else {
            $projects = $query->get();
        }


        return View('admin.Project.ManageProjectsView', ['project' => $projects]);
    }

    function store(Request $request) {
        //echo Input::get('id'); exit;
        if (Input::get('id')) {
            $project = Project::find(Input::get('id'));
            $project->name = Input::get('name');
            $project->status = Input::get('status');
            $project->project_progress = Input::get('progres_status');
            $project->update();
            $projectId = Input::get('id');
        } else {
            $project = new Project();
            $project->name = Input::get('name');
            $project->status = Input::get('status');
            $project->project_progress = Input::get('progres_status');
            $project->save();
            $projectId = $project->id;
        }
        if (Input::file('broucher')) {
            $fileName = $projectId . '_' . Input::file('broucher')->getClientOriginalName();
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/cb/public/admin/ProjectStorage';
            //chmod($filePath, 0777);
            Input::file('broucher')->move($filePath, $fileName);
            DB::table('projects')->where('id', $projectId)->update(array('broucher' => $fileName));
        }

//        echo $request->file('broucher');
        return redirect('/projects');
    }

}
