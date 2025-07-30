@extends('layouts.app')

@section('title', 'Contact HackGreenville')
@section('description', 'Send non-spammy questions or comments to the HackGreenville community.')

@section('content')
    <div class="flex justify-center">
        <div class="container max-w-4xl mx-auto px-4 py-12">
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
                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               name="contact" 
                               id="contact" 
                               value="{{ old('contact') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('contact') border-red-500 @enderror"
                               required>
                        @error('contact')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea name="message" 
                                  id="message" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary h-32 resize-vertical @error('message') border-red-500 @enderror"
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6 text-center">
                        {!! HCaptcha::display(['class' => 'hcaptcha mt-4 text-center']) !!}
                    </div>

                    @error('h-captcha-response')
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">{{ $message }}</div>
                    @enderror

                    <div class="text-center">
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors cursor-pointer">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    @endpush
@endsection
