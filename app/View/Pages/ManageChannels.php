<?php

namespace App\View\Pages;

use App\Enums\ChannelsEnum;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

/**
 * Class ManageChannels
 * @package App\View\Pages\ManageChannels
 */
class ManageChannels extends Component
{
    /** @var int $telegramID */
    public int $telegramID;
    /** @var string $selectedChannel */
    public string $selectedChannel;
    /** @var array $options */
    public array $options = [];
    /** @var Authenticatable $userModel */
    protected Authenticatable $userModel;

    public function __construct()
    {
        $this->userModel = auth()->user();
    }

    /**
     * @return void
     */
    public function mount(): void
    {
        if ($this->userModel->hasChatId()) {
            $this->telegramID = $this->userModel->getChatId();
        }

        $this->selectedChannel = $this->userModel->channel;
        $this->options = ChannelsEnum::options();
    }

    /**
     * @return void
     */
    public function setTelegramID(): void
    {
        $this->userModel->setChatId($this->telegramID);
    }

    /**
     * @param string $value
     * @return void
     */
    public function changeSelectedChannel(string $value): void
    {
        $this->selectedChannel = $value;
        $this->userModel->setChannel($value);
    }

    /**
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('pages.manage-channels');
    }
}
