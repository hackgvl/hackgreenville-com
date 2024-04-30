@extends('layouts.app', ['show_loading' => true])

@section('title', 'Calendar of Greenville, SC Area Tech Events')
@section('description', 'A monthly calendar view of upcoming tech events in the Greenville, SC area.')

@section('content')
    <div class="container">
        <div id="calendar"></div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            let firstRender = true;

            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid', 'list'],
                header: {
                    left: 'title',
                    center: '',
                    right: 'listWeek,dayGridMonth,dayGridDay,prev,next' // user can switch between the two
                },
                events: {
                    url: '/api/calendar',
                },
                loading: function (isLoading, view) {
                    if (isLoading) {// isLoading gives boolean value
                        $(".loading").fadeIn('fast');
                    } else {
                        $(".loading").fadeOut('fast');

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

			                let link = `https://calendar.google.com/calendar/r/eventedit?text=${info.event.title}&`;
			                link += `dates=${info.event.extendedProps.start_fmt}/${info.event.extendedProps.end_fmt}&`;
			                link += `details=${calendar_desc}&`;
			                link += `location=${info.event.extendedProps.location}`;

			                link = encodeURI(link);

                            window.open(link, '_blank');
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
                    console.warn('layout root not found');
                    return;
                }

                let thisWeek = layoutRoot.querySelector('.fc-today').closest('.fc-week');

                // current week can be found at end of last month, within this month, or beginning of next month
                if (thisWeek === null) {
                    console.warn('Current week is not on this page.');
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
        });
    </script>
@endsection
