@if (Auth::id() !=$user->id)
    @if (Auth::user()->is_favorite($micropost->id))
        {!! Form::open(['route'=>['users.unfavorites',$micropost->id]]) !!}
            {!! Form::submit(('お気に入り解除'), ['class'=>'btn btn-danger btn-block']) !!}
        {!! Form::close() !!}
    @endif
    {!! Form::open(['route' => ['users.favorites', $micropost->id]]) !!}
        {!! Form::submit('お気に入り登録', ['class' => "btn btn-primary btn-block"]) !!}
    {!! Form::close() !!}
@endif