<?php

namespace App\Models\Traits;

trait UuidTrait
{
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
