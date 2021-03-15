<template>
    <div>
        <h3 class="text-center">
            {{title}}
        </h3>
        <ul :class="{'timeline': events.length>0}">
          <li class="list-unstyled" v-if="loading>0">
            Loading events <i class="fa fa-spinner fa-spin fa-2x" />
          </li>
          <li v-else-if="events.length == 0">
            <strong>No</strong> events to display.
          </li>
          <li class="timeline-inverted" v-for="event in events">
            <div class="timeline-badge bg-success"><i class="fa fa-calendar" /></div>
            <div class="timeline-panel">
              <div class="timeline-heading">
                      <h4 class="timeline-title">
                        <span v-if="event.cancelled" class="text-danger">[CANCELLED] </span>
                        {{ event.title }}
                      </h4>
                      <p class="timeline-subtitle h6">{{ event.group_name }}</p>
                      <p><small :title="event.active_at" class="text-muted"><i class="fa fa-calendar" />
                        {{ event.active_at_ftm }}</small></p>
                    </div>
                    <div class="timeline-body">
                        <div>
                            <!--<div v-html="event.short_description"/>
                            <br>-->
                            <button @click="showMore(event)" class="btn btn-secondary" type="button">READ MORE</button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    import moment from 'moment';
    // import swal from 'swal';

    export default {
        name: "HgTimeline",
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
          showMore ({ title, group_name, active_at_ftm, active_at, description, uri, cancelled }) {
            const datetime_format = 'MM/DD hh:mm A';
            const desc
                = `<div class="text-center">`
                + `<p class="h4">Hosted by ${group_name}</p>`
                + `</div>`
                + `<div class="text-left">`
                + `<strong>Starts:</strong> ${active_at_ftm} on ${moment(active_at).format(datetime_format)}`
                + '<br /><br />'
                + (cancelled ? '<h3 class="text-danger">This event has been cancelled</h3><br />' : '') + description
                + '</div>';

                let conf = {
                  title: (cancelled ? '<span class="text-danger">[CANCELLED] </span>' : '') + title,
                  html: desc,
                  type: 'info',
                  showCancelButton: false,
                  customClass: 'swal-wide',
                  showCloseButton: true,
                };

            if (uri && !cancelled) {
              conf.confirmButtonText = 'Visit Event Page';
              conf.preConfirm = () => {
                window.open(uri, '_blank');
              };
            }

                swal.fire(conf);
            }
        },
        async mounted() {
            await this.load();
            this.loading = 0;
        }
    }
</script>

<style lang="scss" scoped>
    $color_1: #fff;
    $color_2: inherit;
    $background_color_1: #eeeeee;
    $background_color_2: #999999;

    .timeline {
        list-style: none;
        padding: 20px 0 20px;
        position: relative;

        /*max-height: 500px;*/
        /*overflow: auto;*/

        &:before {
            top: 0;
            bottom: 0;
            position: absolute;
            content: " ";
            width: 3px;
            background-color: $background_color_1;
            /*left: 50%;*/
            margin-left: -15.5px;
        }

        > li {
            margin-bottom: 20px;
            position: relative;

            &:before {
                content: " ";
                display: table;
            }

            &:after {
                content: " ";
                display: table;
                clear: both;
            }

            > .timeline-panel {
                /*width: 46%;*/
                /*float: left;*/
                border: 1px solid #d4d4d4;
                border-radius: 2px;
                padding: 20px;
                position: relative;
                -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
                box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);

                &:before {
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

                &:after {
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
            }

            > .timeline-badge {
                display: none;
                color: $color_1;
                width: 50px;
                height: 50px;
                line-height: 50px;
                font-size: 1.4em;
                text-align: center;
                position: absolute;
                top: 16px;
                /*left: 50%;*/
                margin-left: -25px;
                background-color: $background_color_2;
                z-index: 100;
                border-radius: 50%;
            }
        }

        > li.timeline-inverted {
            > .timeline-panel {
                /*float: right;*/
                &:before {
                    border-left-width: 0;
                    border-right-width: 15px;
                    left: -15px;
                    right: auto;
                }

                &:after {
                    border-left-width: 0;
                    border-right-width: 14px;
                    left: -14px;
                    right: auto;
                }
            }
        }
    }

    .timeline-title {
        margin-top: 0;
        color: $color_2;
    }

    .timeline-body {
        > p {
            margin-bottom: 0;
            overflow: hidden;
            max-width: 100%;

            & + p {
                margin-top: 5px;
            }
        }

        > ul {
            margin-bottom: 0;
            overflow: hidden;
            max-width: 100%;
        }
    }

    @media (max-width: 767px) {
        ul.timeline {
            &:before {
                left: 40px;
            }

            > li {
                > .timeline-panel {
                    width: calc(100% - 90px);
                    width: -moz-calc(100% - 90px);
                    width: -webkit-calc(100% - 90px);
                    float: right;

                    &:before {
                        border-left-width: 0;
                        border-right-width: 15px;
                        left: -15px;
                        right: auto;
                    }

                    &:after {
                        border-left-width: 0;
                        border-right-width: 14px;
                        left: -14px;
                        right: auto;
                    }
                }

                > .timeline-badge {
                    left: 15px;
                    margin-left: 0;
                    top: 16px;
                }
            }
        }
    }


</style>
