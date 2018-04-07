<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class FriendshipController extends Controller
{
	public function checkIfFriends (Request $request)
	{
		$user = User::find($request['user_id']);
		return ['state' => Auth::user()->checkIfFriends($user)];
	}

	public function checkIfHaveRequestFrom (Request $request)
	{
		$user = User::find($request['user_id']);
		return ['state' => Auth::user()->checkIfHaveRequestFrom($user)];
	}

	public function checkIfSentRequestTo (Request $request)
	{
		$user = User::find($request['user_id']);
		return ['state' => Auth::user()->checkIfSentRequestTo($user)];
	}

	public function sendFriendRequestTo (Request $request)
	{
		$user = User::find($request['user_id']);
		return ['state' => Auth::user()->sendFriendRequestTo($user)];
	}

	public function acceptFriendRequest (Request $request)
	{
		$user = User::find($request['user_id']);
		return ['state' => Auth::user()->acceptFriendRequest($user)];
	}

	public function denyFriendRequest (Request $request)
	{
		$user = User::find($request['user_id']);
		return ['state' => Auth::user()->denyFriendRequest($user)];
	}

	public function unfriend (Request $request)
	{
		$user = User::find($request['user_id']);
		return ['state' => Auth::user()->unfriend($user)];
	}
}
