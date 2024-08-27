<?php

namespace App\Models;

use App\Helpers\BootHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $user_id
 * @property int    $device_id
 * @property int    $device_type
 * @property int    $push_id
 * @property string $auth_token
 * @property int    $valid_until
 * @property string $created_at
 * @property string $updated_at
 * @property int    $created_by
 * @property int    $updated_by
 */
class DeviceSession extends Model
{
    use BootHelper;

    public const DEVICE_TYPE_OTHER   = 0;
    public const DEVICE_TYPE_ANDROID = 1;
    public const DEVICE_TYPE_IOS     = 2;

    public const DEFAULT_TTL = 3600;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'device_id',
        'device_type',
        'push_id',
        'auth_token',
        'valid_until',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public static function getDeviceTypeByUserAgent(string $userAgent): int
    {
        if (preg_match('/(android|linux)/i', $userAgent)) {
            return self::DEVICE_TYPE_ANDROID;
        }

        if (preg_match('/(iphone|darwin)/i', $userAgent)) {
            return self::DEVICE_TYPE_IOS;
        }

        return self::DEVICE_TYPE_OTHER;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
