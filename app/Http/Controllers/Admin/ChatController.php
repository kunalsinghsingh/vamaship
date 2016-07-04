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

class ChatController extends Controller
{
	public function groupChat()
	
	{
		
		 return view('admin.Chat.GroupChatView');
	}
}
