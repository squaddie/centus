<?php

namespace App\View\Pages;

use App\Models\City;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

/**
 * Class ManageCities
 * @package App\View\Pages\ManageCities
 */
class ManageCities extends Component
{
    /** @var array $cities */
    public array $cities;
    /** @var string $cityName */
    public string $cityName = '';
    /** @var int $threshold_temperature */
    public int $threshold_temperature;
    /** @var int $threshold_uv */
    public int $threshold_uv;
    /** @var City $cityModel */
    protected City $cityModel;
    /** @var Authenticatable $userModel */
    protected Authenticatable $userModel;

    public function __construct()
    {
        $this->cityModel = new City();
        $this->userModel = auth()->user();
    }

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->cities = $this->userModel->cities()->get()->toArray();
    }

    /**
     * @return void
     */
    public function addCity(): void
    {
        $this->validate([
            'cityName' => 'required|string|max:255',
            'threshold_temperature' => 'required|numeric',
            'threshold_uv' => 'required|numeric',
        ]);

        $city = $this->cityModel->getCityByName($this->cityName);
        $city->users()->attach($this->userModel->id, [
            'threshold_temperature' => $this->threshold_temperature,
            'threshold_uv' => $this->threshold_uv,
        ]);

        $this->reset(['cityName', 'threshold_temperature', 'threshold_uv']);
        $this->mount();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteCity(int $id): void
    {
        $this->userModel->cities()->detach($id);
        $this->reset(['cities']);
        $this->mount();
    }

    /**
     * @param int $id
     * @param string $field
     * @param int $value
     * @return void
     */
    public function updateCityThreshold(int $id, string $field, int $value): void
    {
        $this->userModel->cities()->updateExistingPivot($id, [$field => $value]);
        $this->mount();
    }

    /**
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('pages.manage-cities');
    }
}
