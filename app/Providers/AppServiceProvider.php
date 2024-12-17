<?php

namespace App\Providers;

use App\View\Common\NavigationMenu;
use App\View\Pages\ManageChannels;
use App\View\Pages\ManageCities;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

/**
 * Class AppServiceProvider
 * @package App\Providers\AppServiceProvider
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {

    }

    /**
     * @return void
     */
    public function boot(): void
    {
        Livewire::component('common.navigation-menu', NavigationMenu::class);
        Livewire::component('pages.manage-cities', ManageCities::class);
        Livewire::component('pages.manage-channels', ManageChannels::class);
    }
}
