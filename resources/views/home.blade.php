@extends('layouts.app')
@section('content')
<div class="container" ng-app="facebookApp" ng-controller="postsController" ng-init="getPosts()">
{{-- <p id="power">0</p> --}}
<script>
    //var socket = io('http://localhost:3000');
</script>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <textarea ng-model="post" class="form-control" rows="3" required="required" placeholder="What do you have in mind?"></textarea>
                    <button ng-click="newPost('{!! Auth::user()->name !!}')" type="button" class="btn btn-default" style="float: right; width: 20%">Post</button>
                </div>
            </div>
            <div id="posts">
                <div class="panel panel-default" ng-repeat="post in myPosts">
                    <div class="panel-body">
                        <div class="media">
                            <a class="pull-left" ng-href="/users/@{{post.getUser.id}}">
                                <img class="media-object img-circle" style="width: 80px" src="{{ url('/img/default-profile.png') }}">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading">@{{post.getUser.name}}</h4> <h5>@{{post.dateForHumans}}</h5>
                                <hr>
                                <div style="min-height: 100px;">
                                    <h4>@{{post.content}}</h4>
                                </div>
                                <span class="badge" style="margin-right: 5px;"> @{{post.likesCount}} </span>
                                <span ng-click="like(post, $index)" style="text-decoration: none; cursor: pointer">
                                    <span ng-class="post.liked ? 'liked' : ''"><b>Like</b></span>
                                </span>
                                <hr>
                                <div ng-show="!post.getComments.length">
                                    <h5 style="font-style: italic; color: grey">No comments yet</h5>
                                </div>
                                <div class="media" style="font-size: 80%" ng-repeat="comment in post.getComments">
                                    <a class="pull-left" ng-href="/users/@{{comment.user.id}}">
                                        <img class="media-object img-circle" style="width: 80px" src="{{ url('/img/default-profile.png') }}">
                                    </a>
                                    <div class="media-body">
                                        <h5 class="media-heading">@{{comment.user.name}}</h5> <h6>@{{comment.dateForHumans}}</h6>
                                        @{{comment.content}}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <input ng-model="comments[post.id]" ng-keyup="$event.keyCode == 13 ? newComment(post, $index) : null" class="form-control" required="required" placeholder="Enter your comment">
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection