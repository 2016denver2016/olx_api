<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Class Permission
 * @package App\Models
 *
 * @property integer $id
 * @property string  $name
 * @property string  $display_name
 * @property string  $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class Permission extends SpatiePermission
{
    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'extra'];

    // protected $dateFormat = 'U';

    public static function excludedPermissions()
    {
        return [
            'locations_tree.read',
            'customer.read',
            'modules.read',
            'nullable_content.read',
            'content.create', 'content.read', 'content.update', 'content.delete',
        ];
    }

    public static function excludedPermissionsSuper()
    {
        return [
            'media_plan.create', 'media_plan.read', 'media_plan.update', 'media_plan.delete',
        ];
    }
}
