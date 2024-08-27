<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int    $id
 * @property string $url
 * @property int    $user_id
 * @property int    $advert_id
 * @property int    $status
 *
 */
class Olx extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE           = 1;
    public const STATUS_BLOCKED          = 0;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'olx';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'user_id',
        'advert_id',
        'status',
        'price',
        'email'
    ];


    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
