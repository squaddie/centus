<?php

namespace App\View\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

/**
 * Class GuestLayout
 * @package App\View\Components\GuestLayout
 */
class GuestLayout extends Component
{
    /**
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('layouts.guest');
    }
}
