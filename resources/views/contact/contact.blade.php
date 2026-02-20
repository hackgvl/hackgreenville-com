@extends('layouts.app')

@section('title', 'Contact HackGreenville')
@section('description', 'Send non-spammy questions or comments to the HackGreenville community.')

@section('content')
    <div class="flex justify-center">
        <div class="max-w-4xl mx-auto px-4 py-12">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold mb-8">Contact HackGreenville</h1>
                <ul class="text-left max-w-2xl mx-auto space-y-3 text-lg">
                    <li class="flex items-start"><span class="mr-2">•</span><span>See our <a href="/code-of-conduct" class="text-primary hover:underline">Code of Conduct</a> for related concerns.</span></li>
                    <li class="flex items-start"><span class="mr-2">•</span><span>All other requests may be shared through the form below.</span></li>
                </ul>
            </div>

            <div class="mb-8">
                <hr class="border-gray-300 mb-6">
                <h2 class="text-3xl font-bold text-center mb-8">Contact Form</h2>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
                {{ aire()->route('contact.submit')->novalidate()->rules([
                    'name' => 'required|max:255',
                    'contact' => 'required|email',
                    'message' => 'required|max:5000',
                ]) }}
                
                {{ aire()->summary()->verbose() }}

                {{ aire()->input('name', 'Name')->required() }}
                {{ aire()->email('contact', 'Email')->required() }}
                {{ aire()->textArea('message', 'Message')->rows(4)->required() }}

                <div class="mb-6 flex justify-center">
                    {!! HCaptcha::display(['class' => 'hcaptcha']) !!}
                </div>

                @error('h-captcha-response')
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">{{ $message }}</div>
                @enderror

                <div class="text-center">
                    {{ aire()->submit('Submit') }}
                </div>
                {{ aire()->close() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    @endpush
@endsection