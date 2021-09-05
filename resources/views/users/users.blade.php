@if(count($users)>0)
    <ul class="list-unstyled">
        @foreach ($users as $user)
            <li class="media">
                {{-- ユーザのメールアドレスをもとにGravatarを取得して表示 --}}
                <img src="{{ Gravatar::get($user->email,['size'=>50]) }}" alt="" class="mr-2 rounded">
                <div class="media-body">
                    <div>
                        {{ $user->name }}
                    </div>
                    <div>
                        {{-- ユーザ詳細ページへのリンク --}}
                        <p>{!! link_to_route('users.show','View profile',['user'=>$user->id]) !!}</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {{ $users->links() }}
@endif