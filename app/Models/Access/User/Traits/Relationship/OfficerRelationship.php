<?php

namespace App\Models\Access\User\Traits\Relationship;

/**
 * Class OfficerRelationship.
 */
trait OfficerRelationship
{
    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * @return mixed
     */
    public function tickets()
    {
        return $this->hasMany(config('fine.ticket'));
    }
}
