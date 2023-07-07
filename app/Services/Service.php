<?php

namespace App\Services;

class Service
{
    protected $isApiCall = false;

    public function __construct()
    {
        if (request()->is('api/*')) {
            $this->isApiCall = true;
        }
    }
}
