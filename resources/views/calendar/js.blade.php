<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['dayGrid'],
            eventSources: [{events: {!! $events !!} }],
            eventClick: function (info) {
                let desc = info.event.extendedProps.description.replace(/\<a/, '<a target="_blank"');
                swal.fire({
                    title: info.event.title,
                    html: desc,
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Add it to my calendar!'
                }).then((result) => {
                    if (result.value) {
                        const link = "https://calendar.google.com/calendar/r/eventedit?" +
                            `text=${info.event.title}&` +
                            `dates=${info.event.extendedProps.start_fmt}/${info.event.extendedProps.end_fmt}&` +
                            `details=${desc}&` +
                            `location=${info.event.extendedProps.location}`;

                        window.open(link, '_blank');
                    }
                });

                info.el.style.borderColor = info.el.style.borderColor == 'red' ? 'black' : 'red';
            }
        });

        calendar.render();
    });
</script>
