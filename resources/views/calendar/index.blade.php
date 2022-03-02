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
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid'],
                events: {
                    url: '/api/calendar',
                    // extraParams: function() { // a function that returns an object
                    //     return {
                    //         dynamic_value: Math.random()
                    //     };
                    // }
                },
                loading: function (isLoading, view) {
                    if (isLoading) {// isLoading gives boolean value
                        $(".loading").fadeIn('fast');
                    } else {
                        $(".loading").fadeOut('fast');
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
	                desc += info.event.extendedProps.description.replace(/\<a/, '<a target="_blank"');
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

	                swal.fire(swalProps).then((result) => {

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

            calendar.render();
        });
    </script>
@endsection
