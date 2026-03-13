<div class="max-w-7xl mx-auto mb-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 border border-blue-200 rounded-lg px-5 py-3 bg-blue-50">
        <p class="text-gray-700 text-sm m-0">
            <x-lucide-calendar-check class="w-4 h-4 mr-1 inline text-blue-400"/>
            Never miss a meetup! Add local tech events directly to your favorite calendar app.
        </p>
        <a href="{{ route('calendar-feed.index') }}"
           class="shrink-0 bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors no-underline">
            Get the Feed
        </a>
    </div>
</div>
