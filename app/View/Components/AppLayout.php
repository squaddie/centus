<?php

namespace App\View\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

/**
 * Class AppLayout
 * @package App\View\Components\AppLayout
 */
class AppLayout extends Component
{
    /**
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('layouts.app');
    }
}
