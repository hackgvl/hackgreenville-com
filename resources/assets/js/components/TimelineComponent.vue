<template>
	<div>
		<h3 class="text-center">
			{{title}}
		</h3>
		<ul :class="{'timeline p-3': events.length>0}">
			<li class="list-unstyled" v-if="loading>0">
				Loading events <i class="fa fa-spinner fa-spin fa-2x"></i>
			</li>
			<li v-else-if="events.length == 0">
				<strong>No</strong> events to display.
			</li>
			<li :class="{'timeline-inverted': index % 2 !== 0}" v-for="(event, index) in events">
				<div class="timeline-badge bg-success"><i class="fa fa-calendar"></i></div>
				<div class="timeline-panel">
					<div class="timeline-heading">
						<h4 class="timeline-title">{{event.title}}</h4>
						<p><small class="text-muted"><i class="fa fa-calendar"></i> {{event.active_at_ftm}}</small></p>
					</div>
					<div class="timeline-body">
						<p>
							{{event.short_description}}
							<br>
							<button @click="showMore(event)" class="btn btn-secondary" type="button">READ MORE</button>
						</p>
					</div>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
    import moment from 'moment';

    export default {
        name: "TimelineComponent",
        props: {
            event_data_route: {
                type: String,
                required: true,
            },
            title: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                loading: 1,
                events: [],
            };
        },
        methods: {
            async load() {
                this.loading++;
                const {data: events = []} = await window.axios.get(this.event_data_route) || {};
                this.events = events;
                this.loading--;
            },
            showMore({title, active_at_ftm, active_at, description}) {
                const datetime_format = 'MM/DD hh:mm A';
                const desc
                    = `<div class="text-left">`
                    + `<strong>Starts:</strong> ${active_at_ftm} on ${moment(active_at).format(datetime_format)}`
                    + "<br /><br />"
                    + description
                    + "</div>";

                swal.fire({
                    title: title,
                    html: desc,
                    type: 'info',
                    showCancelButton: false,
                    customClass: 'swal-wide',
                    showCloseButton: false,
                })
            }
        },
        async mounted() {
            await this.load();
            this.loading = 0;
        }
    }
</script>

<style scoped>
	.timeline {
		list-style: none;
		padding: 20px 0 20px;
		position: relative;
	}

	.timeline:before {
		top: 0;
		bottom: 0;
		position: absolute;
		content: " ";
		width: 3px;
		background-color: #eeeeee;
		left: 50%;
		margin-left: -1.5px;
	}

	.timeline > li {
		margin-bottom: 20px;
		position: relative;
	}

	.timeline > li:before,
	.timeline > li:after {
		content: " ";
		display: table;
	}

	.timeline > li:after {
		clear: both;
	}

	.timeline > li:before,
	.timeline > li:after {
		content: " ";
		display: table;
	}

	.timeline > li:after {
		clear: both;
	}

	.timeline > li > .timeline-panel {
		width: 46%;
		float: left;
		border: 1px solid #d4d4d4;
		border-radius: 2px;
		padding: 20px;
		position: relative;
		-webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
		box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
	}

	.timeline > li > .timeline-panel:before {
		position: absolute;
		top: 26px;
		right: -15px;
		display: inline-block;
		border-top: 15px solid transparent;
		border-left: 15px solid #ccc;
		border-right: 0 solid #ccc;
		border-bottom: 15px solid transparent;
		content: " ";
	}

	.timeline > li > .timeline-panel:after {
		position: absolute;
		top: 27px;
		right: -14px;
		display: inline-block;
		border-top: 14px solid transparent;
		border-left: 14px solid #fff;
		border-right: 0 solid #fff;
		border-bottom: 14px solid transparent;
		content: " ";
	}

	.timeline > li > .timeline-badge {
		color: #fff;
		width: 50px;
		height: 50px;
		line-height: 50px;
		font-size: 1.4em;
		text-align: center;
		position: absolute;
		top: 16px;
		left: 50%;
		margin-left: -25px;
		background-color: #999999;
		z-index: 100;
		border-top-right-radius: 50%;
		border-top-left-radius: 50%;
		border-bottom-right-radius: 50%;
		border-bottom-left-radius: 50%;
	}

	.timeline > li.timeline-inverted > .timeline-panel {
		float: right;
	}

	.timeline > li.timeline-inverted > .timeline-panel:before {
		border-left-width: 0;
		border-right-width: 15px;
		left: -15px;
		right: auto;
	}

	.timeline > li.timeline-inverted > .timeline-panel:after {
		border-left-width: 0;
		border-right-width: 14px;
		left: -14px;
		right: auto;
	}

	.timeline-title {
		margin-top: 0;
		color: inherit;
	}

	.timeline-body > p,
	.timeline-body > ul {
		margin-bottom: 0;
		overflow: hidden;
		max-width: 100%;
	}

	.timeline-body > p + p {
		margin-top: 5px;
	}

	@media (max-width: 767px) {
		ul.timeline:before {
			left: 40px;
		}

		ul.timeline > li > .timeline-panel {
			width: calc(100% - 90px);
			width: -moz-calc(100% - 90px);
			width: -webkit-calc(100% - 90px);
		}

		ul.timeline > li > .timeline-badge {
			left: 15px;
			margin-left: 0;
			top: 16px;
		}

		ul.timeline > li > .timeline-panel {
			float: right;
		}

		ul.timeline > li > .timeline-panel:before {
			border-left-width: 0;
			border-right-width: 15px;
			left: -15px;
			right: auto;
		}

		ul.timeline > li > .timeline-panel:after {
			border-left-width: 0;
			border-right-width: 14px;
			left: -14px;
			right: auto;
		}
	}

</style>
