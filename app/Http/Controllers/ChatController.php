<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;
use App\User;
use App\Thread;
use Auth;
use Illuminate\Support\Facades\Redis;

class ChatController extends Controller
{
	public function sendMessage (Request $request)
	{
		$chat = new Chat();
		$chat->text = $request['text'];

		$chat->from_id = Auth::id();
		$chat->to_id = $request['to_id'];

		$chat->save();

		$request['id'] = $request['to_id'];
		$thread_id = $this->newThread($request);

	    event(new \App\Events\ChatEvent($chat, $thread_id));
	}

	public function getMessages (Request $request)
	{
		$msgs1 = User::find($request['user_id'])->messageTo->where('from_id', Auth::id())->all();
		$msgs2 = Auth::User()->messageTo->where('from_id', $request['user_id'])->all();
		$msgs = array_merge($msgs1, $msgs2);
		$this->array_sort_by_column($msgs, 'created_at');
		return $msgs;
	}

	public function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
	    $reference_array = array();

	    foreach($array as $key => $row) {
	        $reference_array[$key] = $row[$column];
	    }

	    array_multisort($reference_array, $direction, $array);
	}

	public function newThread (Request $request)
	{
		$user_id = $request['id'];
		$thrd = Thread::where(function ($q) use ($user_id) {
			$q->where('user1', $user_id)->where('user2', Auth::id());
		})->orWhere(function ($q) use ($user_id) {
			$q->where('user1', Auth::id())->where('user2', $user_id);
		})->first();
		if(count($thrd) == 0) {
			$thread = new Thread();
			$thread->user1 = Auth::id();
			$thread->user2 = $user_id;
			$thread->save();
			return $thread->id;
		}
		return $thrd->id;
	}

	public function typing (Request $request)
	{
		$message = null;
		if ($request['typing']) $message = Auth::id();
	    event(new \App\Events\ChatEvent($message, $request['thread_id']));
	}

}
