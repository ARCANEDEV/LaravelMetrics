<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class     Post
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int                         id
 * @property  string                      title
 * @property  string                      content
 * @property  int                         views
 * @property  \Illuminate\Support\Carbon  created_at
 * @property  \Illuminate\Support\Carbon  updated_at
 * @property  \Illuminate\Support\Carbon  published_at
 */
class Post extends Model
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected $casts = [
        'id'    => 'integer',
        'views' => 'integer',
    ];

    protected $dates = [
        'published_at',
    ];

    protected $guarded = [];
}
