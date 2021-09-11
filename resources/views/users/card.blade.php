<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            {{ $user->name }}
        </h3>
    </div>
    <div class="card-body">
        {{-- ユーザのメールアドレスをもとにGravatarを取得して表示 --}}
        <img src="{{ Gravatar::get($user->email,['size'=>500]) }}" alt="" class="rounded img-fluid">
    </div>
</div>
{{-- フォロー・アンフォローボタン --}}
@include('user_follow.follow_button')