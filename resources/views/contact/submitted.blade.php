@extends('layouts.app')

@section('title', 'Contact HackGreenville')
@section('description', 'Send non-spammy questions or comments to the HackGreenville community.')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="max-w-md mx-auto text-center py-16">
            <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center mx-auto mb-5">
                <x-lucide-check aria-hidden="true" class="w-6 h-6 text-success"/>
            </div>
            <h1 class="text-2xl font-bold mb-2">{{ __('Thank you!') }}</h1>
            <p class="text-gray-500 text-sm">{{ __('We\'ll try to get back to you as soon as possible.') }}</p>
            <a href="/" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-blue-700 no-underline transition-colors mt-6">
                <x-lucide-arrow-left aria-hidden="true" class="w-3.5 h-3.5"/>
                Back to home
            </a>
        </div>
    </div>
@endsection
