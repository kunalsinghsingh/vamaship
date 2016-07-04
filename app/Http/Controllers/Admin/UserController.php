<?php

namespace App\Http\Controllers\Admin;

//use App\User;
//use Validator;
use Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Repositories\UserInterface;

use App\Models\User;
use Hash;
use Auth;
use Crypt,
    Validator,
    Input,
    Redirect,
    DB,
    Session,
    URL,
    Mail;

class UserController extends Controller {
    /*

      |--------------------------------------------------------------------------
      | Registration & Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users, as well as the
      | authentication of existing users. By default, this controller uses
      | a simple trait to add these behaviors. Why don't you explore it?
      |
     */

    protected $userRepo;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(UserInterface $userRepo) {

        $this->userRepo = $userRepo;
        
    }

    protected $redirectPath = '/dashboard';
    protected $loginPath = '/login';

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|confirmed|min:6',
        ]);
    }


    protected function userLogin(Request $request) {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required'
        );
        
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'));
        } else {
            //DB::enableQueryLog();
            $users = $this->userRepo->getData('', Input::get('email'));
            //dd(DB::getQueryLog());
            //dd($users);
            if(!$users){
                Session::flash('flash_error', "The email and password you entered don't match.");
                return redirect('/login');
            }
            
            if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password'), 'enable_flag' => 1])) {
                // Authentication passed...
                Session::put('userId', $users->id);
                Session::put('userName', $users->first_name);
                Session::put('userEmail', $users->email);
                Session::put('userGroup', $users->user_group);
                Session::put('userPic', $users->profile_pic);
                Session::put('FollowupPopUpFlag', 1);
                Session::put('userPhoneName',$users->agent_phone_name);
                //dd($users);
                return redirect($this->redirectPath);
            } else {
                Session::flash('flash_error', "The email and password you entered don't match.");
                return redirect('/login');
            }
        }
    }

    protected function userLogout() {
        Auth::logout();
        Session:flush();
        return redirect('/login');
    }

   

    
}

