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
             
        
        }

     public function delete($id){
        return Addressbook::find($id);
     }

 

}