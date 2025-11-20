<style>
.calendar-banner {
    background: linear-gradient(135deg, #2937f0 0%, #9089fc 100%);
}
</style>
<div class="container max-w-7xl mx-auto py-12 px-4">
    <div class="calendar-banner p-8 md:p-12 rounded-2xl overflow-hidden shadow-xl">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-3/4 text-center md:text-left mb-6 md:mb-0">
                <h2 class="text-white text-3xl font-semibold mb-2" style="letter-spacing: -0.02em;">Stay in sync with your events</h2>
                <p class="text-white text-lg opacity-90" style="letter-spacing: -0.01em;">
                    Subscribe to our calendar feed and get real-time updates across all your devices.
                </p>
            </div>
            <div class="md:w-1/4 text-center md:text-right">
                <a href="{{ route('calendar-feed.index') }}"
                   class="inline-block bg-white bg-opacity-90 text-blue-600 px-7 py-3 rounded-xl font-medium text-base hover:bg-opacity-100 hover:-translate-y-px hover:shadow-lg transition-all duration-200 no-underline"
                   style="letter-spacing: -0.01em; backdrop-filter: blur(10px);">
                    Subscribe Now
                </a>
            </div>
        </div>
    </div>
</div>