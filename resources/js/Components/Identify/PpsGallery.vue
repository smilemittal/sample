<template>
	<!-- Main modal -->
	<div id="createCompany" class="modal-open" data-open="modal">
		<div class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-full h-full">
			<div class="relative w-full h-auto max-w-4xl md:h-auto">
				<div class="relative rounded-lg shadow bg-body">
					<!-- Modal header -->
					<div class="flex items-start justify-between p-4 rounded-t">
						<h3 class="text-xl font-semibold text-gray-300">
							Preview
						</h3>
						<button type="button" @click="isClose()" class="">
							<XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
						</button>
					</div>
					<div class="mb-5 p-5">
						<carousel class="w-full" :items-to-show="1" v-if="ppsImages">
							<slide v-for="(image, key) in ppsImages" :key="image">
								<div class="relative w-3/4 p-4">
									<div>
										<img v-bind:ref="'image' + parseInt(key)" alt="" :src="image"
											class="w-full h-72 object-cover rounded-lg ppsGalleryFormImage" />
									</div>
								</div>
							</slide>
							<template #addons>
								<navigation />
							</template>
						</carousel>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import {
	TrashIcon, XCircleIcon
} from '@heroicons/vue/24/solid'
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
export default {
	name: "PpsGallery",
	props: {
		ppsImages: {
			type: Array
		},
	},
	components: {
		TrashIcon, XCircleIcon,
		Carousel, Slide, Navigation, Pagination
	},
	data() {
		return {
			form: {
			},
			preview: null,
			image: null,
			currentSlide: 0,
		};
	},
	methods: {
		isClose() {
			this.$emit("closeImages");
		},
		PpsImageView() {
			for (let i = 0; i < this.ppsImages.length; i++) {
				if( this.ppsImages[i] instanceof Blob ) {
					let reader = new FileReader(); //instantiate a new file reader
					reader.addEventListener('load', function () {
							this.$refs['image' + parseInt(i)][0].src = reader.result;
					}.bind(this), false);  //add event listener
					reader.readAsDataURL(this.ppsImages[i]);
        } 
			}
		},
		slideTo(val) {
			this.currentSlide = val;
		},
		
	},
	created() {
		this.PpsImageView();
	}
};
</script>
