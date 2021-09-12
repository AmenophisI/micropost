@if (Auth::user()->is_favorite($micropost->id))
        {!! Form::open(['route'=>['favorites.unfavorite',$micropost->id],'method'=>'delete']) !!}
            {!! Form::submit(('お気に入り解除'), ['class'=>'btn btn-secondary btn-sm']) !!}
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => ['favorites.favorite', $micropost->id]]) !!}
            {!! Form::submit('お気に入り登録', ['class' => "btn btn-success btn-sm"]) !!}
        {!! Form::close() !!}
    
@endif