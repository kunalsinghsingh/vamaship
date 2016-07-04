<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Repositories\BookingInterface;
use App\Http\Requests;
use App\Models\Bookingaddress;
use App\Http\Controllers\Controller;

use App\Models\Addressbook;
use App\Models\User;
use Crypt,
    Validator,
    Input,
    Redirect,
    DB,
    Response,
    Session;

class BookingController extends Controller
{
    public $BookingRepo;

   public function __construct(BookingInterface $BookingRepo)
   {
      $this->BookingRepo = $BookingRepo;
   }
    
    public function index()
    {
        $users = $this->BookingRepo->selectAll();

        return view('admin.Booking.bookingListing' ,['users' => $users]); 
        
    }

    public function create() {


    return view('admin.Booking.addBooking'); 
   
    }

 public function update($id) {
        
       
        $User = Addressbook::find($id);
         $saveProfile = new Addressbook();
            $saveProfile->book_title = Input::get('book_title');
            $saveProfile->name = Input::get('name');
            $saveProfile->mobile = Input::get('mobile');
            $saveProfile->address1 = Input::get('add1');
            $saveProfile->address2 = Input::get('add2');
            $saveProfile->address3 = Input::get('add3');
            $saveProfile->pincode = Input::get('pincode');
            $saveProfile->city = Input::get('city');
            $saveProfile->state = Input::get('state'); 
            
            Session::flash('save', 'User Saved successfully');
            $json_array['error'] = 'success';
            $saveProfile->update();
       
        return view('admin/Booking/addBooking', ['user' => $User]);
    }

  public function save() {
        

           $rules = array(
                'book_title' => 'required',
                'name'=>'required',
                'mobile' =>'required',
                'add1'=>'required',
                'add2'=>'required',
                'add3'=>'required',
                'pincode'=>'required',
                'city'=>'required',
                'state'=>'required',
                
            );

      

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $json_array['error'] = 'error';
            $messages = $validator->messages();
            foreach ($rules as $key => $value) {
                $json_array[$key . '-err'] = $messages->first($key);
            }
        } else {
            $saveProfile = new Addressbook();
            $saveProfile->book_title = Input::get('book_title');
            $saveProfile->name = Input::get('name');
            $saveProfile->mobile = Input::get('mobile');
            $saveProfile->address1 = Input::get('add1');
            $saveProfile->address2 = Input::get('add2');
            $saveProfile->address3 = Input::get('add3');
            $saveProfile->pincode = Input::get('pincode');
            $saveProfile->city = Input::get('city');
            $saveProfile->state = Input::get('state'); 
            
            Session::flash('save', 'User Saved successfully');
            $json_array['error'] = 'success';
            if (!Input::get('id')) {

                $saveProfile->save();
                $user_id = $saveProfile->id;
            } 

            
            //$json_array['limit'] = Session::get('limit');
        }


        echo json_encode($json_array);
    }

    public function delete($id){
     $user = $this->BookingRepo->delete($id);
         $user->delete($id);
         return redirect('/booking');
}

  
}
