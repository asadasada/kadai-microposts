<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'user_id', 'microposts_id')->withTimestamps();
    }

    
    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($user_id);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $user_id;
    
        if ($exist || $its_me) {
            // 既にフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($user_id);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($user_id);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $user_id;
    
        if ($exist && !$its_me) {
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($user_id);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $user_id)->exists();
    }
    
   
    
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }

    
      public function favorite($micropostId)
    {
       
        $exist = $this->is_favorite($micropost_id);
       
        $its_me = $this->id == $micropost_id;
    
        if ($exist || $its_me) {
           
            return false;
        } else {
           
            $this->favorites()->attach($micropost_id);
            return true;
        }
    }
    

    
    public function unfavorite($micropostId)
    
     {
       
        $exist = $this->is_favorite($micropost_id);
        
        $its_me = $this->id == $micropost_id;
    
        if ($exist || $its_me) {
            
            return false;
        } else {
            
            $this->favorites()->detach($micropost_id);
            return true;
        }
    }
    
    public function is_favorite($micropostId)
    {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }
}
