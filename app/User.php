<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // このユーザが所有する投稿。（ Micropostモデルとの関係を定義）
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    // このユーザに関係するモデルの件数をロードする。
    public function loadRelationshipCounts()
    {
        $this->loadCount('microposts', 'followings', 'followers', 'favorites');
    }

    // このユーザがフォロー中のユーザ
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    // このユーザをフォロー中のユーザ（Userモデルとの関係を定義）
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    // $userIdで指定されたユーザをフォローする
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);

            return true;
        }
    }

    // $userIdで指定されたユーザをアンフォローする。
    public function unfollow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);

            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    // 指定された$userIDのユーザをこのユーザがフォロー中であるかを調べる。フォロー中ならtrueを返す。
    public function is_following($userId)
    {
        // フォロー中のユーザの中に$userIDのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    // このユーザとフォロー中ユーザの投稿に絞り込む
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidモドの配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }

    // お気に入りの一覧を取得
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }

    // お気に入り登録する
    public function favorite($micropostID)
    {
        // すでに登録しているかの確認
        $exist = $this->is_favorite($micropostID);

        if ($exist) {
            return false;
        } else {
            $this->favorites()->attach($micropostID);

            return true;
        }
    }

    // お気に入り解除する
    public function unfavorite($micropostID)
    {
        // すでに登録しているかの確認
        $exist = $this->is_favorite($micropostID);

        if ($exist) {
            $this->favorites()->detach($micropostID);

            return true;
        } else {
            return false;
        }
    }

    public function is_favorite($micropostID)
    {
        return $this->favorites()->where('micropost_id', $micropostID)->exists();
    }
}
