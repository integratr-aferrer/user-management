<?php

namespace UserPackage\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use UserPackage\App\Traits\BootUuid;

class User extends Model
{
    use BootUuid;

    public const RESOURCE_KEY = 'users';

    protected $guarded = [];

    /**
     * Profile Relation
     * @return MorphTo
     */
    public function profile(): MorphTo
    {
        return $this->morphTo();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
