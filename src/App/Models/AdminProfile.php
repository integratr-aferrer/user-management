<?php

namespace UserPackage\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use UserPackage\App\Traits\BootUuid;

class AdminProfile extends Model
{
    use BootUuid;

    public const RESOURCE_KEY = 'admin_profiles';

    protected $guarded = [];

    /**
     * Profile Relation
     * @return MorphOne
     */
    public function profile(): MorphOne
    {
        return $this->morphOne(User::class, 'profile');
    }
}
