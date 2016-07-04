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
        $user->user_group = $data['user_group'];
        $user->status = 1;
        $user->save();
        return $user->id;
    }
    
    public function getUserByProject($projectId) {
    }
    
    public function getProjetByUser($userId) {
    }
}
?>