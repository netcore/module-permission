<?php
namespace Modules\Permission\Traits;

trait AddUserPermissions {

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }


    /**
     * @param $name
     * @return mixed
     */
    public function hasRole($name)
    {
        if ( !$this->relationLoaded('roles') ) {
            $this->load('roles');
        }

        return $this->roles->contains('name', $name);
    }

}