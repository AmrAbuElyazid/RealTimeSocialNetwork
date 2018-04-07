<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Comment;
use App\Like;
use Auth;

class Post extends Model
{

	protected $appends = ['getUser', 'getComments', 'dateForHumans', 'likesCount', 'liked'];

	public function user ()
	{
		return $this->belongsTo(User::class);
	}

    public function comments ()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes ()
    {
        return $this->hasMany(Like::class);
    }

	public function getGetUserAttribute ()
	{
		return User::find($this->user_id);
	}

	public function getGetCommentsAttribute ()
	{
		return Comment::wherePostId($this->id)->with('user')->get();
	}

	public function getLikesCountAttribute ()
	{
		return count($this->likes);
	}

	public function getLikedAttribute ()
	{
		return (bool) Auth::user()->checkIfLiked($this);
	}

	public function getDateForHumansAttribute ()
	{
		return $this->created_at->diffForHumans();
	}
}
