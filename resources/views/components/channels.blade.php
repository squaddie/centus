<div>
    <!-- Explanation Text -->
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <p class="text-gray-500 leading-relaxed">
            Setup notification channels. You can set up either Telegram bot notifications or Email (Email is your default notification channel).
        </p>
    </div>

    <!-- Notification Options -->
    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 gap-6 p-6 lg:p-8">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Notification Settings
            </h2>

            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                Choose your preferred notification channel below:
            </p>

            <!-- Radio Inputs -->
            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="selectedChannel" value="telegram" wire:change="changeSelectedChannel('telegram')" class="form-radio text-blue-500">
                    <span class="ml-2">Telegram</span>
                </label>

                <label class="inline-flex items-center ml-6">
                    <input type="radio" wire:model="selectedChannel" value="email" wire:change="changeSelectedChannel('email')" class="form-radio text-blue-500">
                    <span class="ml-2">Email</span>
                </label>
            </div>

            <!-- Extra Content for Telegram -->
            @if ($selectedChannel === 'telegram')
                <div class="mt-6 p-4 border border-blue-300 bg-blue-50 rounded-md">
                    <h3 class="text-lg font-medium text-blue-700">
                        Telegram Setup
                    </h3>
                    <p class="mt-2 text-sm text-blue-600">
                        To enable Telegram notifications, please connect to our bot. Firstly we need to get your Telegram Chat ID. So, open this bot
                        <a href="https://t.me/getmyid_bot" target="_blank" class="text-blue-500 hover:underline font-bold">Get My ID Telegram Bot</a>
                        and click "Start", then copy <span class="font-bold">Current chat ID:</span> value and paste into Chat ID input. After it is done, please open our notification bot and click "Start". From that moment you will receive notifications via Telegram.
                        <a href="https://t.me/notificationTestANDREW_bot" target="_blank" class="text-blue-500 hover:underline font-bold">
                            Open Notification Bot
                        </a>
                    </p>

                    <form wire:submit.prevent="setTelegramID" class="space-y-6 mb-6 mt-10">
                        <!-- Full-width input -->
                        <div>
                            <label for="telegramID" class="block text-sm font-medium text-gray-700">Telegram Chat ID</label>
                            <input type="text" id="telegramID"
                                   class="mt-1 block w-1/2 p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   wire:model="telegramID" />
                        </div>

                        <!-- Button -->
                        <div class="flex justify-start">
                            <button type="submit"
                                    class="w-1/5 bg-red-500 text-black hover:bg-red-600 hover:text-white px-4 py-2 rounded-lg shadow-md transition-all duration-150 ease-in-out">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>
</div>
