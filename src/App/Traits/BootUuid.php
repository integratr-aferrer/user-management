<?php

namespace UserPackage\App\Traits;

use Ramsey\Uuid\Uuid;

trait BootUuid
{
    /**
     * uuid format
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $uuid = Uuid::uuid4();
            $model->id = $uuid->toString();
        });
    }
}
