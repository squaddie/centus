<div>
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <h1 class="text-2xl font-medium text-gray-900 mb-4">
            Cities Management
        </h1>

        <p class="mt-2 text-gray-500 leading-relaxed">
            Set cities you want to be notified about.
        </p>
    </div>

    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8 p-6 lg:p-8">
        <div class="w-full">
            <div class="flex items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">
                    Add New City
                </h2>
            </div>
            <!-- Form to add a new city (inputs in one row) -->
            <form wire:submit.prevent="addCity" class="space-y-6 mb-6">
                <!-- Full-width input -->
                <div>
                    <label for="cityName" class="block text-sm font-medium text-gray-700">City Name</label>
                    <input type="text" id="cityName"
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           wire:model="cityName" />
                </div>
                <!-- Two half-width inputs with 5px space -->
                <div class="flex" style="gap: 10px;">
                    <div class="flex-1">
                        <label for="threshold_temperature" class="block text-sm font-medium text-gray-700">Precipitation (set number in mm)</label>
                        <input type="number" id="threshold_temperature"
                               class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               wire:model="threshold_temperature" />
                    </div>
                    <div class="flex-1">
                        <label for="threshold_uv" class="block text-sm font-medium text-gray-700">UV Index</label>
                        <input type="number" id="threshold_uv"
                               class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               wire:model="threshold_uv" />
                    </div>
                </div>
                <!-- Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="w-1/5 bg-red-500 text-black hover:bg-red-600 hover:text-white px-4 py-2 rounded-lg shadow-md transition-all duration-150 ease-in-out">
                        Add City
                    </button>
                </div>
            </form>


        </div>

        <!-- Displaying the list of cities with inputs to update -->
        <div class="w-full mt-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Cities List</h3>

            @if(count($cities) > 0)
                <ul class="mt-4 space-y-4">
                    @foreach($cities as $index => $city)
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
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-sm font-medium text-gray-700">UV Index</label>
                                        <input
                                            type="text"
                                            class="mt-1 w-1/5 p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            wire:model="cities.{{ $index }}.pivot.threshold_uv"
                                            wire:blur="updateCityThreshold({{ $city['id'] }}, 'threshold_uv', $event.target.value)"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons for deleting -->
                            <div class="flex items-center ml-4">
                                <button wire:click="deleteCity({{ $city['id'] }})"
                                        class="bg-red-500 text-black hover:bg-red-600 hover:text-white px-4 py-2 rounded-lg shadow-md transition-all duration-150 ease-in-out mt-8">
                                    Delete
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 mt-4">No cities added yet.</p>
            @endif
        </div>
    </div>

</div>
