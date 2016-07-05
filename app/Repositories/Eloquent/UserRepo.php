<?php
namespace Repositories\Eloquent;

use Repositories\UserInterface;
use App\Models\User;
use DB;

class UserRepo Implements UserInterface
{
    
    public $id;
    public $email;
    public $name;
    public $type;
    public $mobile;
    public $dataArray = array();
    public function getUsers() {
        $users = $this->getData();
        return $users;
    }
     public function getUserByAttribute() {
        $query = User::orderBy('id', 'desc');
        if ($this->email) {
            $query->where('email', $this->email);
        }
        if ($this->id) {
            $query->where('id', $this->id);
        }
        if ($this->mobile && $this->type) {
            echo '454'.$this->mobile;
            $query->where('mobile', trim($this->mobile));
            $query->where('user_group', $this->type);
        }
        // if ($this->mobile) {
        //     echo '36'
        //     $query->where('mobile', trim($this->mobile));
        // }
        $result = $query->first();
        return $result;
    }
  
    public function getData($id = '', $email = '', $text = '') {
        
        $query = User::where('status', 1)->orderBy('users.id', 'desc');
       
     if ($email) {
            $query->where('email', $email);
            $result = $query->first();
        } 
        else {
            if ($text) {
                $query->whereRaw('(users.first_name LIKE  "%' . $text . '%" OR  users.last_name LIKE  "%' . $text . '%"  OR users.email LIKE "%' . $text . '%"  OR  users.mobile LIKE "%' . $text . '%" )');
            }
            
            else {
                $result = $query->paginate(100);
                
                    $result->setPath('user');
                
            }
        }
        
        return $result;
    }
    
    public function createUser() {
        $data = $this->dataArray;
        $user = new User;
        
        $user->mobile = $data['mobile'];
       
        $user->status = 1;
        $user->save();
        return $user->id;
    }
    
   
}
?>