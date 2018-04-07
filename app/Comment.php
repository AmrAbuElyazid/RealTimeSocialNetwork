<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

	protected $appends = ['dateForHumans'];

	public function user ()
	{
		return $this->belongsTo(User::class);
	}

	public function post ()
	{
		return $this->belongsTo(Post::class);
	}

	public function getDateForHumansAttribute ()
	{
		return $this->created_at->diffForHumans();
	}

}
