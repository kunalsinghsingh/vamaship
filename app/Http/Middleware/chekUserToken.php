<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Repositories\UserInterface;
use Crypt;
class chekUserToken
{
    public $userRepo;
    public function __construct(Request $request, UserInterface $userRepo) {
        $this->userRepo = $userRepo;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $token = $request->header('X-AUTH-TOKEN');
        $temp = 1;
        if ($token) {
            $tokenUserId = Crypt::decrypt($token);
            $userId = $this->userRepo->id = $tokenUserId;
            $user = $this->userRepo->getUserByAttribute();
            if(!$user && $user->id != $tokenUserId){
                return (new Response('Invalid User!', 401))->header('Content-Type', 'application/json');
            }
        } 
        else {
            return (new Response('Invalid User!', 401))->header('Content-Type', 'application/json');
        }
        return $next($request);
    }
}
