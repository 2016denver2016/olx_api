<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PermissionRole
 * @package App\Models
 *
 * @property integer $permission_id
 * @property integer $role_id
 */
class PermissionRole extends Model
{
    protected $hidden = ['deleted_at', 'extra'];
    protected $table  = 'permission_role';
}
