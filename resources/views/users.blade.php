@extends('layouts.app')
@section('content')
<script type="text/javascript">
    var user_id = {{$user->id}};
</script>
<div class="container" ng-app="facebookApp" ng-controller="chatCtrl">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object img-circle" style="width: 80px" src="{{ url('/img/default-profile.png') }}">
                        </a>
                        <div class="media-body">
                            <a ng-click="scrollToBottom(500)" class="message-button btn btn-default" data-toggle="modal" href='#chat'>
                                Chat with {{$user->name}}
                            </a>
                            <h4 class="media-heading">{{$user->id != Auth::id() ? $user->name.'\'s' : 'My'}} profile </h4>
                            {{$user->email}}
                            <hr>
                            @if (Auth::id() != $user->id)
                                <script>
                                    var user_id = {{$user->id}};
                                </script>
                                <div ng-app="facebookApp" ng-controller="friendshipsController">
                                    <div ng-show="!friends && sentRequestTo">
                                        <h4 style="font-style: italic">Waiting for your friend request to be accepted..</h4>
                                    </div>
                                    <div ng-show="!friends && !sentRequestTo">
                                        <button type="button" class="btn btn-default" ng-click="sendFriendRequestTo()" ng-show="!haveRequestFrom">Send request</button>
                                        <button type="button" class="btn btn-default" ng-click="acceptFriendRequest()" ng-show="haveRequestFrom">Accept friend request</button>
                                        <button type="button" class="btn btn-default" ng-click="denyFriendRequest()" ng-show="haveRequestFrom">Deny friend request</button>
                                    </div>
                                    <button type="button" class="btn btn-default" ng-click="unfriend()" ng-show="friends">Unfriend</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>

            @if (!$user->posts->count())
                <div class="panel panel-default" >
                    <div class="panel-body">
                    There's no posts.
                    </div>
                </div>
            @endif

            @foreach ($user->posts()->orderBy('created_at', 'desc')->get() as $post)
                <div class="panel panel-default">
                    <div class="panel-body">
                            <div class="media">
                                <a class="pull-left" ng-href="/users/{{$post->getUser->id}}">
                                 <img class="media-object img-circle" style="width: 80px" src="{{ url('/img/default-profile.png') }}">
                                </a>
                                <div class="media-body">
                                  <h4 class="media-heading">{{$post->getUser->name}} </h4>
                                    {{$post->content}}
                                   <hr>

                                   @foreach ($post->comments as $comment)
                                      <div class="media" style="font-size: 80%">
                                        <a class="pull-left" ng-href="/users/{{$post->getUser->id}}">
                                         <img class="media-object img-circle" style="width: 80px" src="{{ url('/img/default-profile.png') }}">
                                        </a>
                                        <div class="media-body">
                                          <h5 class="media-heading">{{$comment->user->name}}</h5>
                                            {{$comment->content}}
                                        </div>
                                      </div>
                                   @endforeach
                                </div>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div>
        <div class="modal fade" id="chat">
            <div class="modal-dialog">
                <div>
                    <div>
                        <h4 class="modal-title">{{$user->name}}</h4>
                    </div>
                    <div class="chat-body" id="chat-body">
                        <span ng-repeat="msg in msgs" ng-class="{{$user->id}} == msg.to_id ? 'right' : 'left'">@{{msg.text}}</span>
                        <span class="left saving" ng-if="isHimTyping">
                            <span><b>o</b></span>
                            <span><b>o</b></span>
                            <span><b>o</b></span>
                        </span>
                    </div>
                    <div>
                        <form ng-submit="sendMessage({{$user->id}})">
                            <input ng-change="typing()" autoComplete="off" type="text" ng-model="msg" class="form-control mytext" id="msg" placeholder="Send a message to {{$user->name}}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection