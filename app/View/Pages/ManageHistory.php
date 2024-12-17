<?php

namespace App\View\Pages;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

/**
 * Class ManageHistory
 * @package App\View\Pages\ManageHistory
 */
class ManageHistory extends Component
{
    /** @var array $history */
    public array $history = [];
    /** @var User $userModel */
    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->history = $this->userModel->getUserHistory();
    }

    /**
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('pages.manage-history');
    }
}
