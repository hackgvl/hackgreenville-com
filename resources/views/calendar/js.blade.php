<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['dayGrid'],
            eventSources: [{events: {$events}}],
            eventClick: function (info) {
                console.log(info);
                // change the border color just for fun

                info.el.style.borderColor = info.el.style.borderColor == 'red' ? 'black' : 'red';
            }
        });

        calendar.render();
    });
</script>
