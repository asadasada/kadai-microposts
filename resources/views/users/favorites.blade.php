@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center">{{ $user->name }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => '/upload', 'method' => 'post', 'files' => true]) !!}
                    {{--成功メッセージ--}}
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="form-group">
                        @if ($user->avatar_filename)
                        <p class="text-center">
                            <img src="{{ asset('storage/avatar/' . $user->avatar_filename) }}" alt="avatar" />
                        </p>
                        @endif
                        {!! Form::label('file', '推奨サイズ200px*200px' , ['class' => 'control-labelse']) !!}
                        {!! Form::file('file') !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit('アップロード' , ['class' => 'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                    {{--<img class="media-object rounded img-fluid" src="{{ Gravatar::src($user->email, 500) }}" alt="">--}}
                </div>
            </div>
        </aside>
        <div class="col-md-8">
            <ul class="nav nav-tabs nav-justified mb-3">
            
                <li class="nav-item"><a href="{{ route('users.favorites', ['id' => $user->id]) }}" 
                class-"nav-link {{ Request::is('users/*/favorites') ? 'active' : ''}}">お気に入り</a></li>
            </ul>
            @if (count($favorites) > 0)
                @include('users.favorites', ['favorites'=> $favorites])
            @endif
        </div>
    </div>
@endsection