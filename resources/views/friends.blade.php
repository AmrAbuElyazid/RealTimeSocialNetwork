@extends('layouts.app')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
	            <div class="panel panel-default">
	                <div class="panel-body">
						<h3>Your friends: </h3> <hr>

						@if (!count(Auth::user()->friends()))
							<center>
								<h5 style="font-style: italic">You don't have any friends.</h5>
							</center>
						@endif

						@foreach (Auth::user()->friends() as $friend)
		                    <div class="media">
		                        <a class="pull-left" href="/users/{{$friend->id}}">
		                            <img class="media-object img-circle" style="width: 80px" src="{{ url('/img/default-profile.png') }}">
		                        </a>
		                        <div class="media-body">
		                            <h4 class="media-heading">{{$friend->name}} </h4>
		                            {{$friend->email}}
		                        </div>
		                    </div>
						@endforeach

	                </div>
	            </div>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
	            <div class="panel panel-default">
	                <div class="panel-body">
						<h3>Friends requests: </h3> <hr>

						@if (!count(Auth::user()->getFriendsRequests()))
							<center>
								<h5 style="font-style: italic">You don't have any friend requests.</h5>
							</center>
						@endif

						@foreach (Auth::user()->getFriendsRequests() as $request)
		                    <div class="media">
		                        <a class="pull-left" href="/users/{{$request->id}}">
		                            <img class="media-object img-circle" style="width: 80px" src="{{ url('/img/default-profile.png') }}">
		                        </a>
		                        <div class="media-body">
		                            <h4 class="media-heading">{{$request->name}} </h4>
		                            {{$request->email}}
		                        </div>
		                    </div>
						@endforeach

	                </div>
	            </div>
			</div>
		</div>
	</div>
@stop