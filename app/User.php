<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\UserNotification;
use App\AdminNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role','photo','status','provider','provider_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders(){
        return $this->hasMany('App\Models\Order');
    }

    public function createNotifUser($data_encode){
        $user=new UserNotification;
        $user->type = 'App\Notifications\UserNotification';
        $user->notifiable_type = 'App\User';
        $user->notifiable_id = $this->id;
        $user->data = $data_encode;
        $user->save();
    }

    // public function createNotifAdmin($data_detail){
    //     $admin=new AdminNotification;
    //     $admin->type='App\Notification\AdminNotification';
    //     $admin->notifiable_type = 'App\Admin';
    //     $admin->notifiable_id = $this->id;
    //     $admin->data = $data_detail;
    //     $admin->save();
    // }
}
