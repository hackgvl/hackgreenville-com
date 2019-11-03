<template>
	<div>

		<form  @submit.prevent="upload">
			<div class="form-group">
				<label for="image">Upload a file</label>
				<input type="file" v-on:change="handleNewimage" class="form-control-file" name="image" id="image" aria-describedby="helpImage" placeholder="">
				<small id="helpImage" class="form-text text-muted">Upload a carousel image</small>
			</div>

			<button type="submit" class="btn btn-primary">
				<i class="fa fa-floppy-o"></i>
				<span>
					Upload
				</span>
			</button>
		</form>

		<ul>
			<li v-for="slide in carousel.slides">
				{{slide}}
			</li>
		</ul>

	</div>
</template>

<script>
    export default {
        name: "CarouselImages",
        props: ['carousel_id'],
        data() {
            return {
                carousel: {},
	            new_images: []
            };
        },
        methods: {
            handleNewimage(e){
                this.new_images = e.target.files;
            },
            load() {
                this.$http
                    .get(`/api/carousel/${this.carousel_id}`)
                    .then(
                        ({data}) => this.carousel = data,
                        onerror => {
                            console.log({onerror});
                        }
                    );
            },
	        upload(){
                const formData = new FormData();
                console.log({images: this.new_images});
                formData.append('file', this.new_images);

                this.$http
	                .put(`/api/carousel/${this.carousel_id}`, formData, {headers: {'Content-Type': 'multipart/form-data'}})
	                .then(
		                (success) => console.log({success}),
                        onerror => {
                            console.log({onerror});
                        }
	                )
	        }
        },
        mounted() {
            this.load();
        }
    }
</script>

<style scoped>

</style>
