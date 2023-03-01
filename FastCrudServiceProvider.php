<?php

use Illuminate\Support\ServiceProvider;

class FastCrudServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'satriotol');
    }
}
