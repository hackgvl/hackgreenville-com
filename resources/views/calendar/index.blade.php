@extends('layouts.app', ['show_loading' => true])

@section('title', 'Calendar of Greenville, SC Area Tech Events')
@section('description', 'A monthly calendar view of upcoming tech events in the Greenville, SC area.')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold">Calendar</h1>
                <p class="text-gray-500 mt-1 text-sm">Tech events aggregated from meetup groups and community organizations across the Upstate</p>
            </div>
            <a href="{{ route('calendar-feed.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary no-underline transition-colors shrink-0">
                <x-lucide-rss aria-hidden="true" class="w-3.5 h-3.5"/>
                Subscribe to feed
            </a>
        </div>

        <div id="calendar"></div>
    </div>

    <dialog id="event-dialog" class="fixed inset-0 m-auto rounded-lg shadow-xl p-0 max-w-lg w-full max-h-[calc(100dvh-3rem)] flex flex-col backdrop:bg-black/50 not-open:hidden">
        <div class="flex items-center justify-between p-4 border-b shrink-0">
            <h2 id="event-dialog-title" class="text-lg font-semibold pr-4 m-0"></h2>
            <form method="dialog">
                <button class="text-gray-400 hover:text-gray-600 text-2xl leading-none cursor-pointer" aria-label="Close">&times;</button>
            </form>
        </div>
        <div id="event-dialog-body" class="p-4 text-left overflow-y-auto min-h-0"></div>
        <div id="event-dialog-actions" class="flex gap-3 justify-end p-4 border-t empty:hidden shrink-0"></div>
    </dialog>
@endsection

@section('js')
    <script type="module">
        (async function () {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            const { Calendar, dayGridPlugin, listPlugin } = await window.loadCalendarLibs();

            let firstRender = true;

            const calendar = new Calendar(calendarEl, {
                height: 'auto',
                plugins: [dayGridPlugin, listPlugin],
                header: {
                    left: 'title',
                    center: '',
                    right: 'listWeek,dayGridMonth,dayGridDay,prev,next'
                },
                events: {
                    url: '{{ route('calendar.data') }}',
                },
                loading: function (isLoading, view) {
                    var overlay = document.getElementById('loading-overlay');
                    if (isLoading) {
                        overlay.style.display = 'flex';
                    } else {
                        overlay.style.display = 'none';

                        if (firstRender) setTimeout(maybeMoveToCurrentWeek, 100);
                    }
                },
                eventClick: function (info) {
                    const event_link = info.event.extendedProps.event_url;
                    const cancelled = info.event.extendedProps.cancelled;

                    const dateRange = info.event.formatRange({
                        weekday: 'long', month: '2-digit', day: '2-digit',
                        hour: '2-digit', minute: '2-digit', hour12: true
                    });

                    const dialog = document.getElementById('event-dialog');
                    if (!dialog || typeof dialog.showModal !== 'function') {
                        window.open(event_link, '_blank');
                        return;
                    }

                    document.getElementById('event-dialog-title').textContent = info.event.title;

                    let body = `<strong>Dates:</strong> ${dateRange}<br/><br/>`;
                    body += info.event.extendedProps.description.replace(/\<a/, '<a rel="external"');
                    document.getElementById('event-dialog-body').innerHTML = body;

                    const actions = document.getElementById('event-dialog-actions');
                    if (!cancelled) {
                        actions.innerHTML = `
                            <a href="${info.event.extendedProps.add_to_google_calendar_url}" target="_blank"
                               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 no-underline text-sm">
                                Add to Google Calendar!
                            </a>
                            <a href="${event_link}" target="_blank"
                               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 no-underline text-sm">
                                Visit Event Page
                            </a>`;
                    } else {
                        actions.innerHTML = '';
                    }

                    dialog.showModal();

                    info.el.style.borderColor = info.el.style.borderColor == 'red' ? 'black' : 'red';
                }
            });

            // Close dialog on backdrop click
            const dialog = document.getElementById('event-dialog');
            dialog.addEventListener('click', (e) => {
                if (e.target === dialog) dialog.close();
            });

            function maybeMoveToCurrentWeek() {
                let layoutRoot = calendarEl.querySelector('.fc-dayGridMonth-view');

                if (layoutRoot === null) {
                    return;
                }

                let thisWeek = layoutRoot.querySelector('.fc-today').closest('.fc-week');

                if (thisWeek === null) {
                    return;
                }

                document.querySelector('.fc-scroller')
                    .scrollTo({
                        top: thisWeek.offsetTop,
                        behavior: 'smooth',
                    });

                firstRender = false;
            }

            calendar.render();
        })();
    </script>
@endsection
