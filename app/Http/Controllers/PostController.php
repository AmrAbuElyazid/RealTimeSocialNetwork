<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;
use App\Like;
use Auth;

class PostController extends Controller
{

	public function newPost (Request $request)
	{
		$post = new Post();
		$post->content = $request['post'];
		$post->user()->associate(Auth::user());
		$post->save();

		return $post;
	}

	public function getPosts ()
	{
		$friends = Auth::user()->friends();
		$friends_posts = [];
		foreach ($friends as $friend) {
			array_push($friends_posts, $friend->posts()->orderBy('created_at', 'desc')->get());
		}
		$my_posts = Post::whereUserId(Auth::id())->orderBy('created_at', 'desc')->get();
		return [
			'myPosts' => $my_posts,
			'friendsPosts' => $friends_posts,
		];
	}

	public function newComment (Request $request)
	{
		$comment = new Comment();
		$comment->content = $request['comment'];
		$comment->user()->associate(Auth::user());
		$comment->post()->associate(Post::find($request['post_id']));
		$comment->save();

		return $comment->post;
	}

	public function newLike (Request $request)
	{
		$post = Post::find($request['post_id']);

		$liked = Auth::user()->checkIfLiked($post);

		if(count($liked)) {
			$liked->delete();
		}else{
			$like = new Like();
			$like->user()->associate(Auth::user());
			$like->post()->associate($post);
			$like->save();
		}

		return $post;
	}

}
