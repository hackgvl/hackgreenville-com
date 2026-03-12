<div class="max-w-7xl mx-auto px-4 mb-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-gradient-to-r from-blue-600 to-indigo-500 rounded-lg px-5 py-3 shadow">
        <p class="text-white text-sm font-medium m-0">
            <x-lucide-calendar-check class="w-4 h-4 mr-1 inline"/>
            Subscribe to our calendar feed — get real-time event updates on all your devices.
        </p>
        <a href="{{ route('calendar-feed.index') }}"
           class="shrink-0 bg-white text-blue-600 px-4 py-1.5 rounded-md text-sm font-medium hover:bg-blue-50 transition-colors no-underline">
            Subscribe
        </a>
    </div>
</div>
