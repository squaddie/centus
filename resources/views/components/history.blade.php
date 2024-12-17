<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    <h1 class="text-2xl font-medium text-gray-900">
        Weather Notifications
    </h1>

    <p class="mt-6 text-gray-500 leading-relaxed">
        Stay updated with the latest weather notifications tailored just for you. Plan your day better with accurate and
        timely updates. On this page you will see the history of the notifications.
    </p>
</div>

<div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8 p-6 lg:p-8">

    @if(count($history))
    @foreach($history as $item)
        @if($item['type'] === 1)
            <x-history-precipitation :history="$item"/>
        @else
            <x-history-uv :history="$item"/>
        @endif
    @endforeach
    @else
        <p>There is no history available.</p>
    @endif
</div>
