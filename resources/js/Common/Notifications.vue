<!-- This example requires Tailwind CSS v2.0+ -->
<template>
	<!-- Global notification live region, render this permanently at the end of the document -->
	<!-- <Teleport to="body">
		<div aria-live="assertive" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start" style="z-index: 99999991;">
			<div class="w-full flex flex-col items-center space-y-4 sm:items-end">
				<transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
					<div v-if="show" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
						<div class="p-4">
							<div class="flex items-start">
								<div class="flex-shrink-0">
									<BanIcon v-if="notification.type=='error'" class="h-6 w-6 text-red-400" aria-hidden="true" />
									<CheckCircleIcon v-if="notification.type=='success'" class="h-6 w-6 text-green-400" aria-hidden="true" />
								</div>
								<div class="ml-3 w-0 flex-1 pt-0.5">
									<p v-if="notification" class="text-sm font-medium text-gray-900" v-html="notification.heading">
									</p>
									<p v-if="notification" class="mt-1 text-sm text-gray-500" v-html="notification.subHeading">
									</p>
								</div>
								<div class="ml-4 flex-shrink-0 flex">
									<button @click="closeNotification" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" ref="closeBtn">
										<span class="sr-only">{{ trans('lang.labels.close') }}</span>
										<XIcon class="h-5 w-5" aria-hidden="true" />
									</button>
								</div>
							</div>
						</div>
					</div>
				</transition>
			</div>
		</div>
	</Teleport> -->
	<Teleport to="body">
		<notifications style="z-index: 999999999;" width="378px">
			<template #body="props">
				<div aria-live="assertive" class="inset-0 flex items-end pl-4 pr-1 py-1 pointer-events-none sm:pl-6 sm:pr-1 sm:py-1 sm:items-start">
					<div class="w-full flex flex-col items-center space-y-4 sm:items-end">
						<div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
							<div class="p-4">
								<div class="flex items-start">
									<div class="flex-shrink-0">
										<BanIcon v-if="props.item.type=='error'" class="h-6 w-6 text-red-400" aria-hidden="true" />
										<CheckCircleIcon v-if="props.item.type=='success'" class="h-6 w-6 text-green-400" aria-hidden="true" />
									</div>
									<div class="ml-3 w-0 flex-1 pt-0.5">
										<p v-if="props.item.title" class="text-sm font-medium text-gray-900" v-html="props.item.title">
										</p>
										<p v-if="props.item.text" class="mt-1 text-sm text-gray-500" v-html="props.item.text">
										</p>
									</div>
									<div class="ml-4 flex-shrink-0 flex">
										<button @click.stop="$notify.close(props.item.id)" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-900" ref="closeBtn">
											<span class="sr-only">{{ trans('lang.labels.close') }}</span>
											<XIcon class="h-5 w-5" aria-hidden="true" />
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</template>
		</notifications>
	</Teleport>
</template>

<script>
	import { CheckCircleIcon, BanIcon } from '@heroicons/vue/24/outline'
	import { XIcon } from '@heroicons/vue/24/solid'

	export default {
		components: {
			CheckCircleIcon,
			XIcon,
			BanIcon
		},
		// props: {
		// 	autoClose: {
		// 		type: Boolean,
		// 		default: true
		// 	}
		// },
		// computed: {
		// 	show() {
		// 		let obj = this.$store.state.notification;
		// 		return obj && Object.keys(obj).length !== 0 && obj.constructor === Object;
		// 	},
		// 	...mapState([
		// 		'notification'
		// 	])
		// },
		// watch: {
		// 	show(value) {
		// 		let that = this;
		// 		let autoClose = this.notification.hasOwnProperty('type') && this.notification.type == 'error' ? false : true;
		// 		// let autoClose = this.notification.hasOwnProperty('autoClose') ? this.notification.autoClose : true;
		// 		if (value && autoClose) {
		// 			this.tInstance = setTimeout(function() {
		// 				if (that.$refs.closeBtn) {
		// 					that.$refs.closeBtn.click();
		// 				}
		// 			}, 2500);
		// 		}
		// 	}
		// },
		// methods: {
		// 	closeNotification() {
		// 		this.$store.commit('setNotification', {});
		// 	}
		// },
		// unmounted() {
		// 	clearTimeout(this.tInstance)
		// }
	}
</script>