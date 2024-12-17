<div class="w-full">
    <div class="flex items-center">
        <h2 class="text-xl font-semibold text-gray-900">
            Precipitation warning
        </h2>
    </div>

    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
        The current precipitation for the city of {{ $history['city']['name'] }} is {{ $history['value'] }}mm.
    </p>
</div>
