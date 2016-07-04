<?php

namespace App\Http\Controllers\Admin;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\UserInterface;
use App\Models\User;
use Hash;
use Auth;
use Crypt,
    Validator,
    Input,
    Redirect,
    DB,
    Response,
    Session;

class AdminUserController extends Controller {

    protected $userRepo;

    public function __construct(UserInterface $userRepo) {
        $this->userRepo = $userRepo;
       
        $this->middleware('auth');
    }
    public function index() {
        $text = Input::get('user_search');

       
        $users = $this->userRepo->getData('', '', $text,1);
       // $usersCount = $this->userRepo->getData('', '', $text, 1, 'count');
        return view('admin/User/userListing', ['users' => $users]);
    }

   

    public function getUserProfile() {
        //new update code 
         $userId = Request::segment(3);
         $User = User::find($userId);
         //$user_has_projects = DB::table('user_projects')->where('user_id', $userId)->get();
        
        return view('admin/User/addUser', ['user' => $User]);
    }

    public function store(Request $request) {

        $user = new User;
        $user->fill([
            'first_name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'status' => 1
        ])->save();

        return redirect('/dashboard');
    }

    
    public function saveProfile() {
      if (Input::get('id') == "") {

            $rules = array(
                'first_name' => 'required',
                'mobile' => 'required|numeric|unique:users|regex:/^[\d\-\+\s]+$/',
            );
            
                $rules['password'] = 'required';
                $rules['password_confirm'] = 'required|same:password';
               
           
        } 
         $rules['mobile'] = 'required|numeric|regex:/^[\d\-\+\s]+$/|unique:users,mobile,' . Input::get('id');
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messages = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '-err'] = $messages->first($key);
            }
        } else {
            if (!Input::get('id')) {

                $saveProfile = new User();
                $saveProfile->email = Input::get('email');
                $saveProfile->mobile = Input::get('mobile');
                $saveProfile->created_by = Session::get('userId');
            } else {

                $saveProfile = User::find(Input::get('id'));
                $saveProfile->updated_by = Session::get('userId');
            }
            $saveProfile->first_name = Input::get('first_name');
            $saveProfile->last_name = Input::get('last_name');

            if (Input::get('password')) {
                $saveProfile->password = Hash::make(Input::get('password'));
            }
            $saveProfile->email = Input::get('email');
            $saveProfile->mobile = Input::get('mobile');
            $saveProfile->user_type = Input::get('user_type');
           
           
            
            $saveProfile->profile_pic = Input::get('profile_image');
            
            $saveProfile->status = 1;
            $saveProfile->save();
            Session::flash('save', 'User Saved successfully');
            $json_array['error'] = 'success';
            if (!Input::get('id')) {

                $saveProfile->save();
                $user_id = $saveProfile->id;
            } else {

                $success_update = $saveProfile->update();
                $user_id = Input::get('id');
            }

            
            $json_array['limit'] = Session::get('limit');
        }
        $json_array['user_group'] = Input::get('userType');
        echo json_encode($json_array);

       
    }

    public function deleteUser() {
        $id = Input::get('userId');
        $deleteUser = User::find($id);
        $deleteUser->status = ("0");
        $deleteUser->mobile = (NULL);
        $deleteUser->email = ("");
        $deleteUser->update();
        $json_array['error'] = 'success';
        echo json_encode($json_array);
    }

    function userSwitch() {
        $json_array['error'] = 'error';
        $user_id = Input::get('switch_id');
        $user_type = User::find($user_id);
        //display($user_type);
        if ($user_type) {
            if ($user_type->enable_flag == 1) {
                Session::put('userEmail', $user_type->email);
                Session::put('userId', $user_type->id);
                Session::put('userName', $user_type->first_name);
                Session::put('userFirstName', $user_type->first_name);
                Session::put('userLastName', $user_type->last_name);
                Session::put('userPic', $user_type->profile_pic);
                Session::put('userGroup', $user_type->user_group);
                Session::put('userGroupName', $user_type->group_name);
                $json_array['error'] = 'success';
            } else {
                $json_array['error'] = 'Your Account is Disabled currently, contact System Administrator to Activate it.';
            }
        }
        echo json_encode($json_array);
    }

    


    public function search() {
        $users = $this->userRepo->getUserByAttribute('', '', '2417');
        
        DB::getQueryLog();
        // die;
    }

    public function userLogin(Request $request) {
        $users = $this->userRepo->getData('', Input::get('email'));
        if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')])) {
            // Authentication passed...
            Session::put('userId', $users->id);
            Session::put('userName', $users->first_name);
            Session::put('userEmail', $users->email);
            Session::put('userGroup', $users->user_group);
            Session::put('userPic', $users->profile_pic);
            if($users)
                return redirect('/dashboard');
            else
                return redirect('/login');
        } else {
            return redirect('/login');
        }
    }

    public function userLogout() {
        Auth::logout();
        Session::flush();
        //Session::forget(array('admin_email', 'admin_user_id','admin_name','admin_user_group','admin_user_pic'));
        return redirect('/');
    }

    public function ajaxUserDetail() {
        $data = User::where('id', Input::get('userId'))->where('status', 1)->first();
        $json_array['error'] = 'success';
        return View('admin.User.ajaxUserDetailView', ['User' => $data]);
    }

    

   
   
}
