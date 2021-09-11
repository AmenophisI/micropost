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
        $this->loadCount('microposts', 'followings', 'followers');
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
}
