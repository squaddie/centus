<li wire:key="{{ $index }}" class="flex justify-between items-center bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-200 ease-in-out mt-5">
    <div class="flex-1">
        <p class="text-lg font-semibold text-gray-900">{{ $city['name'] }}</p>
        <!-- Input to update Precipitation and UV Index -->
        <div class="flex space-x-4 mt-2">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-700">Precipitation</label>
                <input
                    type="text"
                    class="mt-1 w-1/5 p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    wire:model="cities.{{ $index }}.pivot.threshold_temperature"
                    wire:blur="updateCityThreshold({{ $city['id'] }}, 'threshold_temperature', $event.target.value)"
                >
            </div>
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-700">UV Index</label>
                <input
                    type="text"
                    class="mt-1 w-1/5 p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    wire:model="cities.{{ $index }}.pivot.threshold_uv"
                    wire:blur="updateCityThreshold({{ $city['id'] }}, 'threshold_uv', $event.target.value)"
                >
            </div>
        </div>
    </div>

    <!-- Buttons for deleting -->
    <div class="flex items-center ml-4">
        <button wire:click="deleteCity({{ $city['id'] }})"
                class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-lg shadow-md transition-all duration-150 ease-in-out">
            Delete
        </button>
    </div>
</li>
