<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <nav class="px-4 flex items-center justify-center sm:px-0">
        <div class="block">
            <button type="button" :disabled="currentPage <= 1" @click="changePage(firstPage)"
                class="flex items-center justify-center text-sm font-medium text-gray-500 hover:text-gray-700 h-8 rounded-lg bg-cardtop px-3 mr-2"
                :class="{ 'opacity-20': currentPage <= 1 }">
                <span class="text-gray-400 mr-2">First</span>
                <ChevronDoubleLeftIcon class="h-5 w-5 text-white" aria-hidden="true" />
            </button>
        </div>
        <div class="block">
            <button type="button" :disabled="currentPage <= 1" @click="changePage(currentPage - 1)"
                class="flex items-center justify-center text-sm font-medium text-gray-500 hover:text-gray-700 w-8 h-8 rounded-lg bg-body"
                :class="{ 'opacity-20': currentPage <= 1 }">
                <ChevronLeftIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </button>
        </div>
        <div class="hidden md:-mt-px md:flex">
            <button type="button" v-for="page in pages" :key="page" @click="changePage(page)" :class="{
                'flex items-center justify-center text-sm text-neutral-300 font-medium focus:outline-none w-8 h-8 bg-body rounded-lg hover:bg-amber-500 hover:text-white ml-2':
                    page != currentPage,
                'flex items-center justify-center text-sm font-medium focus:outline-none w-8 h-8 bg-amber-500 rounded-lg text-white ml-2':
                    page === currentPage,
            }">
                {{ page }}
            </button>
        </div>
        <div class="md:-mt-px flex md:hidden">
            <button type="button" v-for="page in current_page" :key="page" :class="{
                'hidden':
                    page != currentPage,
                'flex items-center justify-center text-sm font-medium focus:outline-none w-8 h-8 bg-amber-500 rounded-lg text-white ml-2':
                    page === currentPage,
            }">
                {{ page }}
            </button>
        </div>
        <div class="block ml-2">
            <button type="button" :disabled="currentPage >= lastPage" @click="changePage(currentPage + 1)"
                class="flex items-center justify-center text-sm font-medium text-gray-500 hover:text-gray-700 w-8 h-8 rounded-lg bg-body"
                :class="{ 'opacity-20': currentPage >= lastPage }">
                <ChevronRightIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </button>
        </div>
        <div class="block ml-2">
            <button type="button" :disabled="currentPage >= lastPage" @click="changePage(lastPage)"
                class="flex items-center justify-center text-sm font-medium text-gray-500 hover:text-gray-700 h-8 rounded-lg bg-cardtop px-3"
                :class="{ 'opacity-20': currentPage >= lastPage }">
                <span class="text-gray-400 mr-2">Last</span>
                <ChevronDoubleRightIcon class="h-5 w-5 text-white" aria-hidden="true" />
            </button>
        </div>
    </nav>
</template>

<script>
import {
    ChevronDoubleLeftIcon,
    ChevronDoubleRightIcon,
    ChevronLeftIcon,
    ChevronRightIcon
} from '@heroicons/vue/24/solid'
export default {
    components: {
        ChevronDoubleLeftIcon,
        ChevronDoubleRightIcon,
        ChevronLeftIcon,
        ChevronRightIcon
    },
    props: {
        currentPage: Number,
        lastPage: Number,
        total: Number,
        maxVisibleButtons: {
            type: Number,
            default: 10
        },
        firstPage: String
    },
    computed: {
        startPage() {
            if (this.currentPage === 1) {
                return 1;
            }

            if (this.currentPage === this.lastPage) {
                return Math.max(1, this.lastPage - this.maxVisibleButtons + 1);
            }
            let min = Math.min(this.currentPage - 1, (this.lastPage - (this.maxVisibleButtons - 1)));
            return Math.max(1, min)
        },
        endPage() {
            return Math.min(this.startPage + this.maxVisibleButtons - 1, this.lastPage);
        },
        pages() {
            const range = [];

            for (let i = this.startPage; i <= this.endPage; i += 1) {
                range.push(i);
            }
            return range;
        },
        current_page() {
            return this.currentPage;
        },
    },
    methods: {
        changePage(page) {
            this.$emit("refreshTable", page);
        },
    },
    data() {
        return {};
    },
};
</script>
