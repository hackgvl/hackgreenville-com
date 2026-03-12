@extends('layouts.app')

@section('title', 'Contact HackGreenville')
@section('description', 'Send non-spammy questions or comments to the HackGreenville community.')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-2">Contact Us</h1>
        <p class="text-sm text-gray-500 mb-8">
            For Code of Conduct concerns, see our <a href="/code-of-conduct" class="text-primary hover:underline">Code of Conduct</a>.
        </p>

        {{ aire()->route('contact.submit')->novalidate()->rules([
            'name' => 'required|max:255',
            'contact' => 'required|email',
            'message' => 'required|max:5000',
        ]) }}

        {{ aire()->summary()->verbose() }}

        {{ aire()->input('name', 'Name')->required() }}
        {{ aire()->email('contact', 'Email')->required() }}
        {{ aire()->textArea('message', 'Message')->rows(5)->required() }}

        <div class="mb-6 flex justify-center">
            {!! HCaptcha::display(['class' => 'hcaptcha']) !!}
        </div>

        @error('h-captcha-response')
            <div class="mb-4 p-3 bg-red-50 border border-red-300 text-red-700 rounded text-sm">{{ $message }}</div>
        @enderror

        {{ aire()->submit('Send Message') }}
        {{ aire()->close() }}
    </div>

    @push('scripts')
        <script src="https://js.hcaptcha.com/1/api.js?onload=onHcaptchaLoad&render=explicit" async defer></script>
        <script>
            window.onHcaptchaLoad = function() {
                document.querySelectorAll('.h-captcha').forEach(function(el) {
                    if (!el.hasChildNodes()) hcaptcha.render(el);
                });
            };
        </script>
    @endpush
@endsection
