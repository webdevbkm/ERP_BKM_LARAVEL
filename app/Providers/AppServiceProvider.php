<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; // Import Blade facade
use App\View\Components\MasterLayout; // Import component MasterLayout

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // **FIX:** Mendaftarkan komponen layout master secara eksplisit
        // Ini memastikan Laravel selalu mengenali tag <x-master-layout>
        Blade::component('master-layout', MasterLayout::class);
    }
}
