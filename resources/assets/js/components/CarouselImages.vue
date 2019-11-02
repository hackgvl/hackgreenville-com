<template>
	<div>{{carousel}}</div>
</template>

<script>
    export default {
        name: "CarouselImages",
        props: ['carousel_id'],
        data() {
            return {carousel: {}};
        },
        methods: {
            load() {
                console.log(this.$http);
                this.$http
                    .get(`/api/carousel/${this.carousel_id}`)
                    .then(
                        ({data}) => this.carousel = data,
                        onerror => {
                            console.log({onerror});
                        }
                    );
            }
        },
        mounted() {
            this.load();
        }
    }
</script>

<style scoped>

</style>
