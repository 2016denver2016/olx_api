<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role
 * @package App\Models
 *
 * @property integer $id
 * @property string  $name
 * @property string  $display_name
 * @property string  $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class Role extends SpatieRole
{
    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'extra'];

    // protected $dateFormat = 'U';
}
