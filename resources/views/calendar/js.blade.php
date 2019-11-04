<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['dayGrid'],
            eventSources: [{events: {!! $events !!} }],
            eventClick: function (info) {
                console.log(info);
                // change the border color just for fun

                swal.fire({
                    title: 'Are you sure you would like to add this event to your calendar?',
                    text: info.event.title,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add it!'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                    }
                });

                info.el.style.borderColor = info.el.style.borderColor == 'red' ? 'black' : 'red';
            }
        });

        calendar.render();
    });
</script>
