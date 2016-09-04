<?php

namespace App;

use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
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

    /**
     * @param $id
     * @return string
     * Find user with current ID
     */
    public function findOrFail($id) {
        if($id == 0) {
            return 'true';
        } else {
            return 'false';
        }
    }

    /**
     * Checking token of user, when updating him status
     *
     * @param $token
     * @param $id
     * @return string
     */

    public function checkToken($token, $id) {
        $user = DB::table('users')
            ->where('id', '=', $id)
            ->where('status_token', '=', $token)
            ->count();
        if($user != 0) {
            DB::table('users')
                ->where('id', '=', $id)
                ->update(array(
                    'status_token' => '',
                    'status' => '0'
                ));
            return 'true';
        } else {
            return 'false';
        }
    }


}
