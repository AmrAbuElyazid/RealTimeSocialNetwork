<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Chat extends Model
{
	public function messageFrom()
	{
		return $this->belongsTo(User::class, 'from_id', 'user_id');
	}

	public function messageTo()
	{
		return $this->belongsTo(User::class, 'to_id', 'user_id');
	}
}
