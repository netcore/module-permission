<?php

namespace Modules\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'))->withTimestamps();
    }
}
