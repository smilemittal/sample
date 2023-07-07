<template>
    <!-- Assign Default -->
    <div>
        <div v-if="form.is_assigned_default">
            <div class="flex items-center mb-4 mt-2">
                <input id="belong_xme_ca" type="checkbox" value="4" v-model="form.default_assignned_roles"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="belong_xme_ca" class="ml-2 text-sm font-medium text-amber-500">
                    Company Admin</label>
            </div>
            <div class="flex items-center mb-4 mt-2">
                <input id="belong_xme_ba" type="checkbox" value="5" v-model="form.default_assignned_roles"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="belong_xme_ba" class="ml-2 text-sm font-medium text-amber-500">
                    Business Admin
                </label>
            </div>
            <div class="flex items-center mb-4 mt-2">
                <input id="belong_xme_ma" type="checkbox" value="7" v-model="form.default_assignned_roles"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="belong_xme_ma" class="ml-2 text-sm font-medium text-amber-500">Member</label>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
import {
    XCircleIcon
} from '@heroicons/vue/24/solid'

export default {
    name: 'Assign Admin',
    components: {
        XCircleIcon
    },
    props: ['form'],
    data() {
        return {

        }
    },
    methods: {
    
    },
    created() {

    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
