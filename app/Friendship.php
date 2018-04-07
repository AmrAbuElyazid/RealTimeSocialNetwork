<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{

	protected $guarded = ['id'];

	public function friendsFromMyRequest ()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
	public function friendsFromTheirRequest ()
	{
		return $this->belongsTo(User::class, 'friend_id');
	}
}
