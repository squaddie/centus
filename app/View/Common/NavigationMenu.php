<?php

namespace App\View\Common;

use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

/**
 * Class NavigationMenu
 * @package App\View\Common\NavigationMenu
 */
class NavigationMenu extends Component
{
    /**
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('common.navigation-menu');
    }
}
