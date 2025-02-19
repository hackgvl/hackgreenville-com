<style>
.calendar-banner {
    background: linear-gradient(135deg, #2937f0 0%, #9089fc 100%);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(41, 55, 240, 0.12);
}


.btn-subscribe {
    background: rgba(255, 255, 255, 0.9);
    color: #2937f0;
    border: none;
    padding: 0.75rem 1.75rem;
    font-weight: 500;
    font-size: 1rem;
    letter-spacing: -0.01em;
    border-radius: 12px;
    transition: all 0.2s ease;
    backdrop-filter: blur(10px);
}

.btn-subscribe:hover {
    background: rgba(255, 255, 255, 1);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    color: #2937f0;
    text-decoration: none;
}

.banner-title {
    font-weight: 600;
    letter-spacing: -0.02em;
    font-size: 1.75rem;
}

.banner-text {
    font-size: 1.1rem;
    letter-spacing: -0.01em;
    opacity: 0.9;
}
</style>
<div class="container py-5">
    <div class="calendar-banner p-4 p-md-5">
        <div class="row align-items-center">
            <div class="col-md-9 text-center text-md-left mb-4 mb-md-0">
                <h2 class="text-white banner-title mb-2">Stay in sync with your events</h2>
                <p class="text-white banner-text mb-0">
                    Subscribe to our calendar feed and get real-time updates across all your devices.
                </p>
            </div>
            <div class="col-md-3 text-center text-md-right">
                <a href="{{ route('calendar-feed.index') }}" class="btn btn-subscribe">Subscribe Now</a>
            </div>
        </div>
    </div>
</div>
