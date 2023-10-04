<?php

namespace UserPackage\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use UserPackage\App\Traits\BootUuid;

class ClientProfile extends Model
{
    use BootUuid;

    public const RESOURCE_KEY = 'client_profiles';

    protected $guarded = [];
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * Profile Relation
     * @return MorphOne
     */
    public function profile(): MorphOne
    {
        return $this->morphOne(User::class, 'profile');
    }
}
