<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Repositories\UserInterface;
use App\Models\User;
use Hash;
use Auth, DB, Crypt;
class APIAuthController extends Controller
{
    public $userRepo;
    public function __construct(Request $request, UserInterface $userRepo) {
        $this->userRepo = $userRepo;
    }
    
    public function userAuthentication(Request $request, Response $response) {
        $data = $request->all();
        $email = $this->userRepo->email = $data['email'];
        $validate = Auth::attempt($data);
        if ($validate) {
            $user = Auth::user();
            $userToken = Crypt::encrypt($user->id);
            return (new Response())->header('Content-Type', 'application/json')->header('X-AUTH-TOKEN', $userToken);
        } 
        else {
            return (new Response('Invalid User!', 401))->header('Content-Type', 'application/json');
        }
    }
}
