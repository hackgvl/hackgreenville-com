@extends('layouts.app')

@section('title', "Build a Calendar Feed of the events you're interested in")
@section('description', 'Generate an iCal calendar feed to pull events for one, many, or all organizations promoted by HackGreenville into your calendar app')

@section('content')
    <div class="container py-5  d-flex justify-content-center">
        <div class="col-lg-8 col-sm-12">
            <div class="feed-container" x-data="{
            feeds: {{ \Illuminate\Support\Js::encode($organizations) }},
            copied: false,
            baseUrl: '{{ route('calendar-feed.show') }}',

            get allSelected() {
                return this.feeds.every(feed => feed.checked);
            },

            get someSelected() {
                return this.feeds.some(feed => feed.checked) && !this.allSelected;
            },

            toggleAll() {
                const newValue = !this.allSelected;
                this.feeds.forEach(feed => feed.checked = newValue);
            },

            toggleFeed(feed) {
                feed.checked = !feed.checked;
            },

            feedUrl(scheme = null) {
                const params = new URLSearchParams();

                if(!this.allSelected){
                    const selected = this.feeds
                        .filter(feed => feed.checked)
                        .map(feed => feed.id)
                        .join('-');

                    params.append('orgs', selected);
                }

                let url = `${this.baseUrl}?${params.toString()}`;

                if (scheme) {
                   url =  url.replace(/^.*?\:\/\//, scheme);
                }

                return url;
            },

            async copyLink() {

                try {
                    await navigator.clipboard.writeText(this.feedUrl());
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                } catch (err) {
                    alert('Failed to copy link');
                }
            }
        }">
                <div class="feed-header">
                    <h3 class="feed-title">
                        Curated Event Feed
                    </h3>
                </div>
                <div class="feed-body">

                    <div class="feed-item bg-white/5 m-2 select-all-item" @click="toggleAll()">
                        <div class="custom-control custom-switch">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="selectAll"
                                   :checked="allSelected"
                                   :indeterminate="someSelected"
                                   @click="toggleAll()">
                            <label class="custom-control-label" for="selectAll"></label>
                        </div>
                        <span class="feed-name">Select All / None</span>
                    </div>

                    <div class="feed-divider"></div>

                    <template x-for="feed in feeds" :key="feed.id">
                        <div class="feed-item m-2"
                             @click="toggleFeed(feed)">
                            <div class="custom-control custom-switch">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       :id="feed.id"
                                       x-model="feed.checked"
                                       @click="toggleFeed(feed)"
                                />
                                <label class="custom-control-label" :for="feed.id"></label>
                            </div>
                            <span class="feed-name" x-text="feed.title"></span>
                        </div>
                    </template>

                    <div class="text-center mt-4 d-flex flex-column align-items-center">
                        <a :href="feedUrl('webcal://')" class="btn btn-primary text-decoration-none mb-3">
                            Subscribe to Calendar Feed
                        </a>

                        <input type="text" class="form-control mb-3" :value="feedUrl" readonly />

                        <button class="copy-btn"
                                @click="copyLink()"
                                :class="{ 'copied': copied }">
                            <template x-if="!copied">
                                <svg class="copy-icon mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 4V16C8 17.1046 8.89543 18 10 18H20C21.1046 18 22 17.1046 22 16V7.41421C22 6.88378 21.7893 6.37507 21.4142 6L20 4.58579C19.6249 4.21071 19.1162 4 18.5858 4H10C8.89543 4 8 4.89543 8 6"
                                          stroke="currentColor"
                                          stroke-width="2"
                                          stroke-linecap="round"
                                          stroke-linejoin="round" />
                                    <path d="M16 4V2C16 0.89543 15.1046 0 14 0H4C2.89543 0 2 0.895431 2 2V12C2 13.1046 2.89543 14 4 14H6"
                                          stroke="currentColor"
                                          stroke-width="2"
                                          stroke-linecap="round"
                                          stroke-linejoin="round" />
                                </svg>
                            </template>
                            <template x-if="copied">
                                <svg class="copy-icon mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </template>
                            <span x-text="copied ? 'Copied!' : 'Copy Link'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .feed-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .feed-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        .feed-title {
            font-weight: 600;
            color: #2d3748;
            font-size: 1.25rem;
            margin: 0;
        }

        .feed-body {
            padding: 1.5rem;
        }

        .feed-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border-radius: 8px;
            background: #f8fafc;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .feed-item:hover {
            background: #edf2f7;
        }

        .feed-divider {
            height: 1px;
            background: rgba(0, 0, 0, 0.08);
            margin-bottom: 1rem;
        }

        .select-all-item {
            background: #f1f5f9;
        }

        .select-all-item:hover {
            background: #e2e8f0;
        }

        .feed-name {
            margin-left: 1rem;
            font-weight: 500;
            color: #4a5568;
        }

        .custom-switch {
            padding-left: 2.25rem;
        }

        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #4299e1;
            border-color: #4299e1;
        }

        .custom-control-label {
            cursor: pointer;
        }

        .subscribe-btn {
            background: #4299e1;
            border: none;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .subscribe-btn:hover {
            background: #3182ce;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(66, 153, 225, 0.2);
        }

        .copy-btn {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            color: #475569;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
        }

        .copy-btn:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        .copy-btn.copied {
            background: #86efac;
            border-color: #86efac;
            color: #065f46;
        }

        .gap-3 {
            gap: 1rem;
        }

        .feed-icon {
            width: 24px;
            height: 24px;
            fill: #4299e1;
            margin-right: 0.5rem;
        }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
@endsection
