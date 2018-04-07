<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Post;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts ()
    {
        return $this->hasMany(Post::class);
    }

    public function comments ()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes ()
    {
        return $this->hasMany(Like::class);
    }

    public function checkIfLiked (Post $post)
    {
        return $this->likes()->wherePostId($post->id)->first();
    }

    public function friendshipsFromMyRequest ()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }
    public function friendshipsFromTheirRequest ()
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }

    public function friendships ()
    {
        return $this->friendshipsFromMyRequest->merge($this->friendshipsFromTheirRequest);
    }

    public function friends ()
    {
        $friendships = $this->friendshipsFromMyRequest->merge($this->friendshipsFromTheirRequest)->where('accepted', true)->all();
        return $this->getUsersFromFriendships($friendships);
    }

    public function getUsersFromFriendships ($friendships)
    {
        $users = [];
        foreach ($friendships as $friendship) {
            if($friendship->user_id != $this->id) {
                array_push($users, User::find($friendship->user_id));
            }else{
                array_push($users, User::find($friendship->friend_id));
            }
        }
        return $users;
    }

    public function checkForFriendship (User $user)
    {
        $friendships = $this->friendships()->where('user_id', $this->id)->where('friend_id', $user->id)->merge($this->friendships()->where('user_id', $user->id)->where('friend_id', $this->id)->all());

        return (bool) $friendships->count();
    }

    public function checkIfFriends (User $user)
    {
        $friendships = $this->friendships()->where('user_id', $this->id)->where('friend_id', $user->id)->where('accepted', true)->merge($this->friendships()->where('user_id', $user->id)->where('friend_id', $this->id)->where('accepted', true)->all());

        return (bool) $friendships->count();
    }

    public function sendFriendRequestTo (User $user)
    {
        if(!$this->checkForFriendship($user)) {
            $friendship = new Friendship([
                'user_id' => $this->id,
                'friend_id' => $user->id,
                'accepted' => false,
            ]);

            return $friendship->save();
        }
    }

    public function getFriendsRequests ()
    {
        $friendships = Friendship::whereFriendId($this->id)->whereAccepted(false)->get();
        return $this->getUsersFromFriendships($friendships);
    }

    public function acceptFriendRequest (User $user)
    {
        $friendship = Friendship::whereUserId($user->id)->whereFriendId($this->id)->whereAccepted(false)->first();
        if(!empty($friendship)) {
            $friendship->accepted = true;
            $friendship->save();
        }
    }

    public function denyFriendRequest (User $user)
    {
        $friendship = Friendship::whereUserId($user->id)->whereFriendId($this->id)->whereAccepted(false)->first();
        if(!empty($friendship)) {
            $friendship->delete();
        }
    }

    public function unfriend (User $user)
    {
        $friendship = Friendship::where('user_id', $user->id)->where('friend_id', $this->id)->where('accepted', true)->first();
        if(empty($friendship)) {
            $friendship = Friendship::where('user_id', $this->id)->where('friend_id', $user->id)->where('accepted', true)->first();
        }
        if(!empty($friendship)) {
            $friendship->delete();
        }
    }

    public function checkIfSentRequestTo (User $user)
    {
        $friendship = Friendship::whereUserId($this->id)->whereFriendId($user->id)->whereAccepted(false)->get();

        return (bool) $friendship->count();
    }

    public function checkIfHaveRequestFrom (User $user)
    {
        $friendship = Friendship::whereUserId($user->id)->whereFriendId($this->id)->whereAccepted(false)->get();

        return (bool) $friendship->count();
    }

    public function messageTo ()
    {
        return $this->hasManyThrough(Chat::class, User::class, 'id', 'to_id');
    }
}
