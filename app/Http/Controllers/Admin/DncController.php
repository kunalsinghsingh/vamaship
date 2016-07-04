<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Repositories\DncRepositoryInterface;
use App\Http\Requests;
use App\Models\Dnc;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Models\User;
use Input;
use Validator;
use App\Models\Campaign;
use App\Models\Project;

class DncController extends Controller
{
  public $DncRepo;

  public function __construct(DncRepositoryInterface $DncRepo)
  {
    $this->DncRepo = $DncRepo;
  }

  public function index()
  {
    $users = DB::table('dnc')->paginate(15);

    $data=$this->search();

    return view('admin.Dnc.listingDnc' ,['users' => $users,'data'=>$data]); 

  }

  public function create()
  {
    $client = DB::table('users')->select('id','first_name','last_name')->where('user_group','client')->get();
    $campaign = DB::table('campaigns')->select('id','name')->get();    
    return view('admin.Dnc.addDnc',['client' => $client ,'campaign'=>$campaign]); 
  }

  public function save(Request $req){

    $inputs=input::all();

    $rules=array(
      'first_name'=>'required',
      'last_name' =>'required',
            //'company_phone' =>'required|unique:dnc|regex:/^[\d\-\+\s]+$/',
      'company_phone' =>'required',
      );
    $validator = Validator::make($inputs, $rules);
    if ($validator->fails()) {


     $messages = $validator->errors();

             //return "Not Valid Data";

     return redirect('adddcn')
     ->withErrors($validator)
     ->with('messages',$messages)
     ->withInput();

   }
   else{
    $data = $this->DncRepo->save($req);
    if($req->ajax())
      return $data;
    else
      return redirect('/dnc');

  }

  

}
public function edit($id)
{
 $client = DB::table('users')->select('id','first_name','last_name')->where('user_group','client')->get();
 $campaign = DB::table('campaigns')->select('id','name')->get(); 
 $dnc_client=DB::table('dnc')->select('id','client_id','campaign_id')->where('id',$id)->get();
 $client_data=explode(',',$dnc_client[0]->client_id);
 $cl=implode(',',$client_data);
 if($cl!=null)
 {
  $mains =  DB::Select("select id,first_name,last_name from users where id IN (".@$cl.")");
}
else
{
 $mains=array();
}
$dnc=Dnc::where('id',$id)->first();



$campaign_data=explode(',',$dnc_client[0]->campaign_id);
$cm=implode(',',$campaign_data);

if($cm!=null)
{

  $cmp_name= DB::Select("select id, name from campaigns where id IN (".@$cm.")");
}
else
{
  $cmp_name=array();
}

$user = $this->DncRepo->find($id);


return view('admin.Dnc.updateDnc',['user'=>$user ,'client' => $client ,'campaign'=>$campaign ,'dnc_client'=> $dnc_client,'mains'=>$mains,'cmp_name'=>$cmp_name,'dnc'=>$dnc,]);
}

public function update($id)
{

 $userupdate = $this->DncRepo->update($id);

 return redirect('/dnc');

}


public function delete($id){
 $user = $this->DncRepo->delete($id);
 $user->delete($id);
 return redirect('/dnc');
}

public function search()
{
  $text = Input::get('user_search');
  $dns_status=Input::get('dnc_status');
  $users = $this->DncRepo->search(); 
  return view('admin.Dnc.listingDnc' ,['users' => $users]); 
}   

}
