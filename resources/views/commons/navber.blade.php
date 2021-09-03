<header class="mb-4">
    <nav class="navber navber-expand-sm navber-dark bg-dark">
        {{-- トップページへのリンク --}}
        <a class="navber-brand" href="/">Microposts</a>

        <button type="button" class="navber-toggler" data-toggle="collapse" data-target="#nav-ber">
            <span class="navber-toggler-icon"></span>
        </button>

        <div class="collapse navber-collapse" id="nav-ber">
            <ul class="navber-nav mr-auto"></ul>
            <ul class="navber-nav">
                {{-- ユーザ登録ページへのリンク --}}
                <li>{!! link_to_route('signup.get','Signup',[],['class'=>'nav-link']) !!}</li>
                {{-- ログインページへのリンク --}}
                <li class="nav-item"><a href="#" class="nav-link">Login</a></li>
            </ul>
        </div>
    </nav>
</header>