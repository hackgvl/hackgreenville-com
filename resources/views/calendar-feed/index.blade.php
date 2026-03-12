@extends('layouts.app')

@section('title', "Build a Calendar Feed of the events you're interested in")
@section('description', 'Generate an iCal calendar feed to pull events for one, many, or all organizations promoted by HackGreenville into your calendar app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-5">
        <div class="w-full lg:w-2/3 mx-auto">
            <h1 class="text-4xl font-bold mb-4">Subscribe to a Personalized Calendar</h1>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="text-xl font-bold">How Does it Work?</h3>
                </div>
                <div class="p-6">
                    <ul>
                        <li>Select one or more organizations to follow and press 'Generate Calendar Feed Link'</li>
                        <li>Add this generated link to your preferred calendar application, often configured under 'By URL', 'iCal / WebCal', or 'From Web'
                            <ul class="ml-6 mt-2">
                                <li><a href="https://support.google.com/calendar/answer/37100#:~:text=Use%20a%20link%20to%20add%20a%20public%20calendar" rel="nofollow">Google</a></li>
                                <li><a href="https://support.microsoft.com/en-us/office/import-or-subscribe-to-a-calendar-in-outlook-com-or-outlook-on-the-web-cff1429c-5af6-41ec-a5b4-74f2c278e98c#ID0EDLBBDDD" rel="nofollow">Outlook</a></li>
                                <li><a href="https://support.mozilla.org/en-US/kb/creating-new-calendars#w_on-the-network-connect-to-your-online-calendars" rel="nofollow">Thunderbird</a></li>
                                <li><a href="https://support.apple.com/guide/calendar/subscribe-to-calendars-icl1022/mac" rel="nofollow">Apple</a></li>
                            </ul>
                        </li>
                        <li>Due to performance caching, updates by event organizers may take hours to refresh within your calendar app</li> 
                        <li>Changing your subscription in the future will require generating a new link and replacing the URL in your calendar application</li>
                    </ul>
                </div>
            </div>

            <div class="feed-container mt-4" x-data="{
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
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-xl font-bold">
                            Organizations
                        </h3>
                    </div>
                    <div class="p-6">

                        <div class="flex items-center p-4 mb-3 rounded-lg bg-slate-100 cursor-pointer hover:bg-slate-200 transition-colors" @click="toggleAll()">
                            <button type="button"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out"
                                    :class="allSelected ? 'bg-primary' : (someSelected ? 'bg-primary opacity-50' : 'bg-gray-300')"
                                    @click.stop="toggleAll()">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                      :class="allSelected || someSelected ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                            <span class="ml-4 font-medium text-gray-700">Select All / None</span>
                        </div>

                        <div class="h-px bg-gray-200 mb-4"></div>

                        <template x-for="feed in feeds" :key="feed.id">
                            <div class="flex items-center p-4 mb-3 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors"
                                 @click="toggleFeed(feed)">
                                <button type="button"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out"
                                        :class="feed.checked ? 'bg-primary' : 'bg-gray-300'"
                                        @click.stop="toggleFeed(feed)">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                          :class="feed.checked ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                                <span
                                    class="ml-4 font-medium text-gray-700"
                                    :class="{
                                        'font-bold': feed.checked,
                                    }"
                                    x-text="feed.title"></span>
                            </div>
                        </template>
                        
                        <div x-show="feeds.length === 0" class="text-center py-8 text-gray-500">
                            No organizations available at this time.
                        </div>

                        <div class="text-center mt-4 flex flex-col items-center">
                            <a :href="feedUrl('webcal://')" class="inline-block bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors no-underline mb-3">
                                Generate Calendar Feed Link
                            </a>

                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 bg-gray-50" :value="feedUrl" readonly />

                            <button @click="copyLink()"
                                    class="inline-flex items-center px-6 py-3 font-medium rounded-lg border transition-all"
                                    :class="copied ? 'bg-green-300 border-green-300 text-green-900' : 'bg-slate-100 border-slate-200 text-slate-700 hover:bg-slate-200 hover:-translate-y-px'">
                                <template x-if="!copied">
                                    <svg class="mr-2 w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                    <svg class="mr-2 w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </template>
                                <span x-text="copied ? 'Copied!' : 'Copy Link'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

@endsection
