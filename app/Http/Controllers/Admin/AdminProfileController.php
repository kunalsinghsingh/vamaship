<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Support\Facades\Request;
use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\InquiryInterface;
use Repositories\GeneralInterface;
use App\Models\Inquiry;
use App\Models\User;
use Hash;
use Auth;
use Crypt,
    Validator,
    Input,
    Redirect,
    DB,
    Session;

class AdminProfileController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function getUserProfile() {
        $id = Session::get('userId');

        $user = User::where("id", "=", $id)->get()->first();
        return view('admin.User.manageProfile', ['user' => $user]);
    }

    public function saveUserProfile() {
        if (Input::get('old_password') != "") {

            $userdata = array(
                'email' => Session::get('admin_email'),
                'password' => Input::get('old_password'),
            );

            if (Input::get('old_password') != "") {
                $userdatanew = array(
                    'email' => Session::get('admin_email'),
                    'password' => Input::get('password'),
                );

                if (Auth::attempt($userdatanew)) {
                    $json_array['nerror'] = 'nerror';
                    $rules = array(
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'mobile' => 'required|regex:/^[\d\-\+\s]+$/',
                        'password' => 'required',
                        'password_confirm' => 'required|same:password',
                    );
                } else {

                    $rules = array(
                        'password' => 'required',
                        'password_confirm' => 'required|same:password',
                    );
                }
            }

            if (Auth::attempt($userdata)) {

                $rules = array(
                    'first_name' => 'required',
                    'mobile' => 'required|regex:/^[\d\-\+\s]+$/',
                    'password' => 'required',
                    'password_confirm' => 'required|same:password',
                );
            } else {
                $json_array['oerror'] = 'oerror';
                $rules = array(
                    'password' => 'required',
                    'password_confirm' => 'required|same:password',
                );
            }
        } else {

            $rules = array(
                'first_name' => 'required',
                'mobile' => 'required|regex:/^[\d\-\+\s]+$/'
            );
        }

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messages = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '-err'] = $messages->first($key);
            }
        } else {


            $update_profile = User::find(Session::get('userId'));
            $update_profile->first_name = Input::get('first_name');
            $update_profile->last_name = Input::get('last_name');
            if (Input::get('password') != "") {
                $update_profile->password = Hash::make(Input::get('password'));
            }

            $update_profile->mobile = Input::get('mobile');
            $update_profile->profile_pic = Input::get('profile_image');
            $update_profile->status = 1;

            $update_profile->update();
            Session::put('userPic', Input::get('profile_image'));
            Session::flash('save', 'Profile Updated successfully.');
            $json_array['error'] = 'success';
        }
        
        echo json_encode($json_array);
    }

}
