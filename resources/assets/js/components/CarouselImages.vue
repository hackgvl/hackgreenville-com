<template>
	<div>
		<button @click="load">Reload data</button>

		<form @submit.prevent="upload">
			<div v-if="loading>0">Loading . . .</div>
			<div v-if="page_loaded == true">

				<div class="form-group">
					<label for="image">Upload a file</label>
					<input
							type="file"
							ref="carousel_input"
							:disabled="this.loading>0"
							v-on:change="handleNewImage"
							class="form-control-file"
							name="image"
							id="image"
							aria-describedby="helpImage"
							placeholder=""
							multiple="multiple"
					/>
					<small id="helpImage" class="form-text text-muted">Upload a carousel image</small>
				</div>

				<button type="submit" class="btn btn-primary mb-3" :disabled="this.loading>0">
					<i class="fa fa-floppy-o"></i>
					<span>
					Upload
				</span>
				</button>

				<hr>
			</div>
		</form>

		<div v-if="page_loaded == true">

			<draggable v-if="carousel.slides.length>0" v-model="carousel.slides" group="slides" @start="drag=true" @end="drag=false">
				<div v-for="(slide, index) in carousel.slides" :key="index.id" class="draggable border-info">
					<div class="pull-right">
						<button @click="remove_image(index)" class="btn btn-danger">
							<i class="fa fa-trash"></i>
						</button>
					</div>
					<div class="row">
						<div class="col-1">
							<img :src="slide" alt="Slide preview" height="50"/>
						</div>
						<div class="col-11">
							{{index}} - {{slide}}
						</div>
					</div>
				</div>
			</draggable>
			<div v-else>
				No slides to show
			</div>
		</div>

	</div>
</template>

<script>
    import draggable from 'vuedraggable';

    export default {
        name: "CarouselImages",
        components: {draggable},
        props: ['carousel_id'],
        data() {
            return {
                page_loaded: false,
                drag: false,
                loading: 0,
                carousel: {slides: []},
                new_images: []
            };
        },
        methods: {
            handleNewImage(e) {
                this.new_images = this.$refs.carousel_input.files;
                this.upload();
            },
            saveSort: _.debounce(async function (){
                const carousel = this.carousel;
                await this.$http.put(`/api/carousel/${carousel.id}`, {slides: carousel.slides});

                window.toastr['info']('Carousel has been updated');
            }, 1000),
            resetCarouselInput() {
                const input = this.$refs.carousel_input;
                input.type = 'text';
                input.type = 'file';
            },
            async load() {
                this.loading++;
                const {data} = await this.$http.get(`/api/carousel/${this.carousel_id}`);
                this.carousel = data;
                this.loading--;
                this.page_loaded=true;
            },
	        async remove_image(index){
				this.carousel.slides.splice(index, 1);
				this.saveSort();
	        },
            async upload() {
                this.loading++;

                let formData = new FormData();

                _.forEach(this.new_images, (file, index) => formData.append(`carousel_files[${index}]`, file, file.name));

                await this.$http.post(`/api/carousel/${this.carousel_id}/images`, formData);
                await this.load();
                this.loading--;
                this.resetCarouselInput();

                toastr['success']('Carousel image uploaded');
            }
        },
        watch: {
            drag(value) {
                if (value == false) {
                    this.saveSort();
                }
            },
        },
        mounted() {
            this.load();
        }
    }
</script>

<style scoped lang="scss">
	.form-control-file{
		padding: 10px;
		&:hover {
			box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05), 0 3px 6px rgba(0, 0, 0, 0.05);
		}
	}
	.draggable {
		cursor: pointer;
		padding: 3px;

		&:hover {
			box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05), 0 3px 6px rgba(0, 0, 0, 0.05);
		}
	}
</style>
