<?php

namespace Modules\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class LevelRoute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'netcore_permission__level_routes';

    protected $fillable = ['route', 'url', 'level_id'];
}
