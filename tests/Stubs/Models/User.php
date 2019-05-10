<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class     User
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int                              id
 * @property  string                           name
 * @property  string                           email
 * @property  string                           type
 * @property  int                              points
 * @property  bool                             is_premium
 * @property  \Illuminate\Support\Carbon       created_at
 * @property  \Illuminate\Support\Carbon       updated_at
 * @property  \Illuminate\Support\Carbon|null  verified_at
 * @property  \Illuminate\Support\Carbon|null  deleted_at
 */
class User extends Model
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'id'         => 'integer',
        'points'     => 'integer',
        'is_premium' => 'boolean'
    ];

    protected $dates = [
        'verified_at', 'deleted_at',
    ];
}
