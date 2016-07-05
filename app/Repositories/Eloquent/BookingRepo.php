<?php

namespace Repositories\Eloquent;

use Mail, Validator, Crypt, Request, Input, DB, Response;
use Repositories\BookingInterface;
use App\Models\Addressbook;



class BookingRepo implements BookingInterface
{ 

  public function selectAll()
    {
           $user = DB::table('address_book')->paginate(100);
           
            return $user;
    }

   
    public function save()
    {   
         
    }
     public function find($id) {
            return Addressbook::find($id);
        }

     public function update($id) {
        
            $user= Addressbook::find($id);
            
            $user->book_title = Input::get('book_title');
            $user->name = Input::get('name');
            $user->mobile = Input::get('mobile');
            $user->address1 = Input::get('add1');
            $user->address2 = Input::get('add2');
            $user->address3 = Input::get('add3');
            $user->pincode = Input::get('pincode');
            $user->city = Input::get('city');
            $user->state = Input::get('state');
         $user->update();    
        
        }

     public function delete($id){
        return Addressbook::find($id);
     }

 

}