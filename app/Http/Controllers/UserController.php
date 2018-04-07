<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
	public function userProfile ($id)
	{
		$user = User::find($id);
	    return view('users', compact('user'));
	}

	public function getMyFriends ()
	{
		return view('friends');
	}
}
