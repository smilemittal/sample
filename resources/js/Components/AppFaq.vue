<template>
    <div class="bg-gray-200 mt-5 rounded-lg">
        <div class="max-w-screen-xl mx-auto pb-16 pt-8 sm:pt-16 sm:pb-20 px-4 sm:px-6 lg:pb-28 lg:px-8">
            <h2 class="text-3xl leading-9 font-extrabold text-gray-800">
                {{ trans('lang.labels.faq_title') }}
            </h2>
            <div class="mt-6 border-t-2 border-gray-800 pt-10">
                <dl class="md:grid md:grid-cols-2 md:gap-8">
                    <div>
                        <div>
                            <dt class="text-lg leading-6 font-medium text-gray-800">
                                {{ trans('lang.labels.what_is_xme') }}
                            </dt>
                            <dd class="mt-2">
                                <p class="text-base leading-6 text-gray-600">
                                    {{ trans('lang.labels.xme_content') }}
                                </p>
                            </dd>
                        </div>
                        <div class="mt-8">
                            <dt class="text-lg leading-6 font-medium text-gray-800">
                                {{ trans('lang.labels.cancelation_of_subscription') }}
                            </dt>
                            <dd class="mt-2">
                                <p class="text-base leading-6 text-gray-600">
                                    {{ trans('lang.labels.cancel_content') }}
                                </p>
                            </dd>
                        </div>
                    </div>
                    <div class="mt-12 md:mt-0">
                        <div class="">
                            <dt class="text-lg leading-6 font-medium text-gray-800">
                                {{ trans('lang.labels.own_videos') }}
                            </dt>
                            <dd class="mt-2">
                                <p class="text-base leading-6 text-gray-600">
                                    {{ trans('lang.labels.content_own_videos') }}
                                </p>
                            </dd>
                        </div>
                        <div class="mt-12">
                            <dt class="text-lg leading-6 font-medium text-gray-800">
                                {{ trans('lang.labels.pricing_future') }}
                            </dt>
                            <dd class="mt-2">
                                <p class="text-base leading-6 text-gray-600">
                                    {{ trans('lang.labels.content_pricing_future') }}
                                </p>
                            </dd>
                        </div>
                        <!-- <div class="mt-12">
                            <dt class="text-lg leading-6 font-medium text-gray-800">
                                {{ trans('lang.labels.pricing_future_title') }}
                            </dt>
                            <dd class="mt-2">
                                <p class="text-base leading-6 text-gray-600">
                                    {{ trans('lang.labels.pricing_future_content') }}
                                </p>
                            </dd>
                        </div> -->
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>
<script>
export default {}
</script>
