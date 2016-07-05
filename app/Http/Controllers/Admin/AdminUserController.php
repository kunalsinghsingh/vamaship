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

       
        $users = $this->userRepo->getData('', '', $text);
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

    

    
    public function saveProfile() {
      if (Input::get('id') == "") {

            $rules = array(
                'first_name' => 'required',
                'mobile' => 'required|numeric|unique:users|regex:/^[\d\-\+\s]+$/',
                'email' =>'required',
            );
            
                $rules['password'] = 'required';
                
               
           
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
               
            } else {

                $saveProfile = User::find(Input::get('id'));
                $saveProfile->updated_by = Session::get('userId');
            }
            $saveProfile->first_name = Input::get('first_name');
            

            if (Input::get('password')) {
                $saveProfile->password = Hash::make(Input::get('password'));
            }
            //$token = bin2hex(openssl_random_pseudo_bytes(16));
            $token = bin2hex(random_bytes(40));
            $saveProfile->email = Input::get('email');
            $saveProfile->mobile = Input::get('mobile');
            $saveProfile->auth_token = $token;
           
            
           
            
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
        $deleteUser = User::find($id)->delete();
        
        $json_array['error'] = 'success';
        echo json_encode($json_array);
    }

  

    // public function userLogin(Request $request) {
    //     $users = $this->userRepo->getData('', Input::get('email'));
    //     if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')])) {
    //         // Authentication passed...
    //         Session::put('userId', $users->id);
    //         Session::put('userName', $users->first_name);
    //         Session::put('userEmail', $users->email);
            
    //         if($users)
    //             return redirect('/dashboard');
    //         else
    //             return redirect('/login');
    //     } else {
    //         return redirect('/login');
    //     }
    // }

    // public function userLogout() {
    //     Auth::logout();
    //     Session::flush();
    //     //Session::forget(array('admin_email', 'admin_user_id','admin_name','admin_user_group','admin_user_pic'));
    //     return redirect('/');
    // }

    // public function ajaxUserDetail() {
    //     $data = User::where('id', Input::get('userId'))->where('status', 1)->first();
    //     $json_array['error'] = 'success';
    //     return View('admin.User.ajaxUserDetailView', ['User' => $data]);
    // }

    

   
   
}
