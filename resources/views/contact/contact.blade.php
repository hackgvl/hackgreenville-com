@extends('layouts.app')

@section('title', 'Contact HackGreenville')
@section('description', 'Send non-spammy questions or comments to the HackGreenville community.')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="mb-12">
            <h1 class="text-3xl font-bold">Contact Us</h1>
            <p class="text-gray-500 mt-1 text-sm">Send non-spammy questions or comments to the HackGreenville community</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-12 gap-y-10">

            {{-- Sidebar --}}
            <div class="lg:col-span-1 order-2 lg:order-1">
                <div class="flex items-center gap-3 mb-5">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Other Ways</h2>
                    <div class="h-px bg-gray-200 flex-1"></div>
                </div>

                <div class="space-y-4">
                    <a href="/join-slack" class="group flex items-start gap-3 no-underline">
                        <div class="w-8 h-8 rounded-md bg-primary/5 flex items-center justify-center shrink-0 mt-0.5">
                            <x-lucide-hash class="w-4 h-4 text-primary/40 group-hover:text-primary/70 transition-colors"/>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-700 group-hover:text-primary transition-colors">Slack Community</div>
                            <p class="text-xs text-gray-400 leading-relaxed mt-0.5">For quick questions, chat with us in real time</p>
                        </div>
                    </a>

                    <a href="/code-of-conduct" class="group flex items-start gap-3 no-underline">
                        <div class="w-8 h-8 rounded-md bg-primary/5 flex items-center justify-center shrink-0 mt-0.5">
                            <x-lucide-shield-check class="w-4 h-4 text-primary/40 group-hover:text-primary/70 transition-colors"/>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-700 group-hover:text-primary transition-colors">Code of Conduct</div>
                            <p class="text-xs text-gray-400 leading-relaxed mt-0.5">For CoC concerns, please review our policy</p>
                        </div>
                    </a>

                    <a href="https://github.com/hackgvl/hackgreenville-com/issues" rel="noopener" class="group flex items-start gap-3 no-underline">
                        <div class="w-8 h-8 rounded-md bg-primary/5 flex items-center justify-center shrink-0 mt-0.5">
                            <x-lucide-github class="w-4 h-4 text-primary/40 group-hover:text-primary/70 transition-colors"/>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-700 group-hover:text-primary transition-colors">GitHub Issues</div>
                            <p class="text-xs text-gray-400 leading-relaxed mt-0.5">Report bugs or suggest features for the website</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Form --}}
            <div class="lg:col-span-2 order-1 lg:order-2">
                <div class="flex items-center gap-3 mb-5">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Send a Message</h2>
                    <div class="h-px bg-gray-200 flex-1"></div>
                </div>

                {{ aire()->route('contact.submit')->data('turbo', 'false')->novalidate()->rules([
                    'name' => 'required|max:255',
                    'contact' => 'required|email',
                    'message' => 'required|max:5000',
                ]) }}

                {{ aire()->summary()->verbose() }}

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4">
                    {{ aire()->input('name', 'Name')->required() }}
                    {{ aire()->email('contact', 'Email')->required() }}
                </div>

                {{ aire()->textArea('message', 'Message')->rows(5)->required() }}

                <x-turnstile data-appearance="interaction-only" />

                @error('cf-turnstile-response')
                    <div class="mb-4 p-3 bg-red-50 border border-red-300 text-red-700 rounded-lg text-sm">{{ $message }}</div>
                @enderror

                {{ aire()->submit('Send Message') }}
                {{ aire()->close() }}
            </div>

        </div>
    </div>

    @push('scripts')
        <x-turnstile.scripts />
    @endpush
@endsection
