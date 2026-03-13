@extends('layouts.app', ['show_loading' => true])

@section('title', 'Calendar of Greenville, SC Area Tech Events')
@section('description', 'A monthly calendar view of upcoming tech events in the Greenville, SC area.')

@section('head')
    <script src='{{ url('vendors/fullcalendar/packages/core/main.min.js') }}' data-turbo-track="reload"></script>
    <script src='{{ url('vendors/fullcalendar/packages/daygrid/main.js') }}' data-turbo-track="reload"></script>
    <script src='{{ url('vendors/fullcalendar/packages/list/main.js') }}' data-turbo-track="reload"></script>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold">Calendar</h1>
                <p class="text-gray-500 mt-1 text-sm">Tech events aggregated from meetup groups and community organizations across the Upstate</p>
            </div>
            <a href="{{ route('calendar-feed.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary no-underline transition-colors shrink-0">
                <x-lucide-rss class="w-3.5 h-3.5"/>
                Subscribe to feed
            </a>
        </div>

        <div id="calendar"></div>
    </div>
@endsection

@section('js')
    <script>
        (function () {
            let firstRender = true;

            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                height: 'auto',
                plugins: ['dayGrid', 'list'],
                header: {
                    left: 'title',
                    center: '',
                    right: 'listWeek,dayGridMonth,dayGridDay,prev,next' // user can switch between the two
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
                    const datetime_format = 'MM/DD hh:mm A';

                    let calendar_desc = `Event link: ${event_link}`;
                    calendar_desc += '<br />';
                    calendar_desc += info.event.extendedProps.description;

                    let desc = `<div class="text-left">`;
                    desc += `<strong>Dates:</strong> ${moment(info.event.start).
                        format(`(dddd) ${datetime_format}`)} - ${moment(info.event.end).format(datetime_format)}`;
                    desc += '<br />';
                    desc += '<br />';
                    desc += info.event.extendedProps.description.replace(/\<a/, '<a rel="external"');
                    desc += '</div>';

                    const swalProps = {
                        title: info.event.title,
                        html: desc,
                        type: 'info',
                        showCloseButton: true,
                    };

                    const cancelled = info.event.extendedProps.cancelled;

                    if (!cancelled) {
                        swalProps['confirmButtonText'] = 'Add to Google Calendar!';
                        swalProps['cancelButtonText'] = 'Visit Event Page';
                        swalProps['showCancelButton'] = true;
                        swalProps['confirmButtonColor'] = '#3085d6';
                        swalProps['cancelButtonColor'] = '#00a508';
                    }

                    Swal.fire(swalProps).then((result) => {

                        if (cancelled) {
                            return true;
                        }

                        if (result.value) {
                            window.open(info.event.extendedProps.add_to_google_calendar_url, '_blank');
                        } else if (result.dismiss == "cancel") {
                            window.open(event_link, '_blank');
                        }
                    });

                    info.el.style.borderColor = info.el.style.borderColor == 'red' ? 'black' : 'red';
                }
            });

            function maybeMoveToCurrentWeek() {
                let layoutRoot = calendarEl.querySelector('.fc-dayGridMonth-view');

                if (layoutRoot === null) {
                    // Layout root not found
                    return;
                }

                let thisWeek = layoutRoot.querySelector('.fc-today').closest('.fc-week');

                // current week can be found at end of last month, within this month, or beginning of next month
                if (thisWeek === null) {
                    // Current week is not on this page
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
