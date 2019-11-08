<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['dayGrid'],
            eventSources: [{events: {!! $events !!} }],
            eventClick: function (info) {
                const event_link = info.event.extendedProps.event_url;
                const datetime_format = 'MM/DD hh:mm A';

                let calendar_desc = `Event link: ${event_link}`;
                calendar_desc += "<br />";
                calendar_desc += info.event.extendedProps.description;

                let desc = `<div class="text-left">`;
                desc += `<strong>Dates:</strong> ${moment(info.event.start).format(`(dddd) ${datetime_format}`)} - ${moment(info.event.end).format(datetime_format)}`;
                desc += "<br />";
                desc += "<br />";
                desc += info.event.extendedProps.description.replace(/\<a/, '<a target="_blank"');
                desc += "</div>";
                swal.fire({
                    title: info.event.title,
                    html: desc,
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#00a508',
                    confirmButtonText: 'Add to Google Calendar!',
                    cancelButtonText: 'Visit Event Page',
                    showCloseButton: true,
                }).then((result) => {
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
