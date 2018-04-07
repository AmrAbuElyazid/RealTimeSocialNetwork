@extends('layouts.app')

@section('content')
    @if (!count($users))
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="media">
                        <div class="media-body">
                            No results found for '{{Request::get('query')}}'.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
	@foreach ($users as $user)
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="media">
                        <a class="pull-left" href="/users/{{$user->id}}">
                            <img class="media-object img-circle" style="width: 80px" src="{{ url('/img/default-profile.png') }}">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">{{$user->id != Auth::id() ? $user->name.'\'s' : 'My'}} profile </h4>
                            {{$user->email}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
	@endforeach
@stop