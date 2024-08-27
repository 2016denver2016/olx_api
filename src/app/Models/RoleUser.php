<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RoleUser
 * @package App\Models
 *
 * @property integer $user_id
 * @property integer $role_id
 */
class RoleUser extends Model
{
    public    $timestamps = false;
    protected $table      = 'role_user';
}
