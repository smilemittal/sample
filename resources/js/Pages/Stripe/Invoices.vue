<template>
    <InertiaHead>
        <title>{{ trans("lang.labels.invoice") }} - xme</title>
    </InertiaHead>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ trans("lang.labels.invoice") }}
            </h2>
        </template>
        <div class="mt-5">
            <div class="mx-auto rounded-lg pt-1 grid grid-cols-1 md:grid-cols-6 gap-6">
                <profile-nav-bar :activeTabName="activeTabName" />
                <div class="w-full col-span-4">
                    <div class="custom-width bg-sidebar rounded-md p-6 shadow">
                        <div>
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-neutral-300">
                                    {{ trans("lang.labels.upcoming_invoice") }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{
                                        trans(
                                            "lang.labels.the_default_payment_method_will_be_any_billing_purposes"
                                        )
                                    }}
                                </p>
                            </div>
                            <div class="mt-8 flex flex-col">
                                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                        <div
                                            class="overflow-hidden box-shadow border border-neutral-600 ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                            <table class="min-w-full divide-y divide-neutral-600">
                                                <thead class="bg-neutral-800">
                                                    <tr>
                                                        <th scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-xs uppercase font-medium text-neutral-300 sm:pl-6">
                                                            {{
                                                                trans(
                                                                    "lang.labels.invoice"
                                                                )
                                                            }}
                                                        </th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-center text-xs uppercase font-medium text-neutral-300">
                                                            {{
                                                                trans(
                                                                    "lang.labels.charge"
                                                                )
                                                            }}
                                                        </th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-center text-xs uppercase font-medium text-neutral-300">
                                                            {{
                                                                trans(
                                                                    "lang.labels.date"
                                                                )
                                                            }}
                                                        </th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-center text-xs uppercase font-medium text-neutral-300">
                                                            {{
                                                                trans(
                                                                    "lang.labels.status"
                                                                )
                                                            }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-neutral-800 bg-neutral-900">
                                                    <tr v-if="upcomingInvoiceTable">
                                                        <td
                                                            class="whitespace-nowrap text-center py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                            <div class="flex items-center">
                                                                <file />
                                                                <div class="ml-4 text-left">
                                                                    <h4
                                                                        class="text-sm font-medium text-neutral-300 mb-0.5">
                                                                        <!-- {{
                                                                            upcomingInvoiceTable.plan_name
                                                                        }} - -->
                                                                        {{
                                                                            upcomingInvoiceTable.plan_period
                                                                        }}
                                                                    </h4>
                                                                    <p class="text-xs text-neutral-300 font-medium">
                                                                        {{
                                                                            upcomingInvoiceTable.user_name
                                                                        }}
                                                                        - #{{
                                                                            upcomingInvoiceTable.invoice_no
                                                                        }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap w-44 text-center px-3 py-4 text-sm text-neutral-300 font-medium">
                                                            {{
                                                                upcomingInvoiceTable.total
                                                            }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap w-44 text-center px-3 py-4 text-sm text-neutral-300 font-medium">
                                                            {{
                                                                upcomingInvoiceTable.date
                                                            }}
                                                        </td>
                                                        <td class="whitespace-nowrap w-44 text-center px-3 py-4">
                                                            <button
                                                                class="text-white bg-themeChartColorOne py-0.5 px-3 rounded-full font-medium text-xs">
                                                                {{
                                                                    trans(
                                                                        "lang.labels.pending"
                                                                    )
                                                                }}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr v-else>
                                                        <td class="p-2 text-center text-neutral-300" colspan="4">{{
                                                            trans('lang.messages.no_record_found') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="custom-width bg-sidebar rounded-md p-6 shadow p-6 mt-6 mb-8">
                        <div>
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-neutral-300">
                                    {{ trans("lang.labels.payment_history") }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{
                                        trans(
                                            "lang.labels.the_default_payment_method_will_be_any_billing_purposes"
                                        )
                                    }}
                                </p>
                            </div>
                            <div class="mt-8 flex flex-col">
                                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                        <div
                                            class="overflow-hidden box-shadow border border-neutral-600 ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                            <table class="min-w-full divide-y divide-neutral-600">
                                                <thead class="bg-neutral-800">
                                                    <tr>
                                                        <th scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-xs uppercase font-medium text-neutral-300 sm:pl-6">
                                                            {{
                                                                trans(
                                                                    "lang.labels.invoice"
                                                                )
                                                            }}
                                                        </th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-center text-xs uppercase font-medium text-neutral-300">
                                                            {{
                                                                trans(
                                                                    "lang.labels.amount"
                                                                )
                                                            }}
                                                        </th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-center text-xs uppercase font-medium text-neutral-300">
                                                            {{
                                                                trans(
                                                                    "lang.labels.date"
                                                                )
                                                            }}
                                                        </th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-center text-xs uppercase font-medium text-neutral-300">
                                                            {{
                                                                trans(
                                                                    "lang.labels.status"
                                                                )
                                                            }}
                                                        </th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-center text-xs uppercase font-medium text-neutral-300">
                                                            {{
                                                                trans(
                                                                    "lang.labels.actions"
                                                                )
                                                            }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-neutral-600 bg-neutral-900">
                                                    <template v-if="paymentHistoryTable.length">

                                                        <tr v-for="item in paymentHistoryTable" :key="item.email">
                                                            <td class="whitespace-nowrap text-center py-4 pl-4 pr-3 text-sm font-medium text-netural-900 sm:pl-6"
                                                                style="width: 300px">
                                                                <div class="flex items-center">
                                                                    <pdf-file />
                                                                    <div class="ml-4 text-left">
                                                                        <h4
                                                                            class="text-sm font-medium text-neutral-300 mb-0.5">
                                                                            <!-- {{
                                                                            item.plan_name
                                                                        }} - -->
                                                                            {{
                                                                                item.plan_period
                                                                            }}
                                                                        </h4>
                                                                        <p class="text-xs text-neutral-300 font-medium">
                                                                            {{
                                                                                item.user_name
                                                                            }}
                                                                            - #{{
                                                                                item.invoice_no
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="whitespace-nowrap w-32 text-center px-3 py-4 text-sm text-neutral-300 font-medium">
                                                                {{ item.total }}
                                                            </td>
                                                            <td
                                                                class="whitespace-nowrap text-center px-3 py-4 text-sm text-neutral-300 font-medium">
                                                                {{ item.date }}
                                                            </td>
                                                            <td
                                                                class="whitespace-nowrap w-32 text-center px-3 py-4 text-sm text-neutral-300 font-medium">
                                                                <button :style="{
                                                                    color: item.color,
                                                                    backgroundColor:
                                                                        item.background,
                                                                }"
                                                                    class="text-white bg-themeChartColorOne py-0.5 px-3 rounded-full font-medium text-xs">
                                                                    {{
                                                                        item.status
                                                                    }}
                                                                </button>
                                                            </td>
                                                            <td class="whitespace-nowrap w-40 text-center px-3 py-4">
                                                                <div class="flex items-center">
                                                                    <a :href="item.link
                                                                            " target="_blank"
                                                                        class="text-neutral-300 rounded-full font-medium text-sm">
                                                                        {{
                                                                            trans(
                                                                                "lang.labels.view"
                                                                            )
                                                                        }}
                                                                    </a>
                                                                    <a :href="item.download_link
                                                                            "
                                                                        class="text-neutral-300 rounded-full font-medium text-sm ml-3"
                                                                        target="_blank">
                                                                        {{
                                                                            trans(
                                                                                "lang.labels.download"
                                                                            )
                                                                        }}
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    <template v-else>
                                                        <tr>
                                                            <td class="p-2 text-center text-neutral-300 text-sm" colspan="5">{{
                                                                trans('lang.messages.no_record_found') }}</td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <Pagination v-if="pagination.lastPage != 1" @refreshTable="getPaymentHistory"
                                :currentPage="pagination.currentPage" :lastPage="pagination.lastPage"
                                :total="pagination.total" class="mt-3" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProfileNavBar from "@/Common/ProfileNavBar.vue";
import File from "@/ImageComponents/Profile/File.vue";
import PdfFile from "@/ImageComponents/Profile/PdfFile.vue";
import Pagination from "@/Components/Pagination.vue";

export default {
    components: {
        AuthenticatedLayout,
        ProfileNavBar,
        Pagination,
        File,
        PdfFile,
    },
    data() {
        return {
            activeTabName: "payment-history",
            upcomingInvoiceTable: null,
            paymentHistoryTable: [],
            refresh: false,
            pagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
        };
    },
    mounted() {
        this.getPaymentHistory();
        this.getUpcomingInvoice();
    },
    methods: {
        getPaymentHistory(page) {
            if (page) {
                this.pagination.currentPage = page
            }
            let that = this;
            axios.get("/api/payment-history", { params: { page: this.pagination.currentPage } }).then((res) => {
                that.paymentHistoryTable = res.data.data;
                that.setPagination(res);
            });
        },
        getUpcomingInvoice() {
            let that = this;
            axios.get("/api/upcoming-invoice").then((res) => {
                that.upcomingInvoiceTable = res.data.data;
            });
        },
        setPagination(response) {

            this.pagination.total = response.data.meta.total;
            this.pagination.lastPage = response.data.meta.last_page;
            this.pagination.currentPage = response.data.meta.current_page;
        },
    },
};
</script>
<style scoped>
.custom-width-main {
    width: 900px;
}

@media (min-width: 1200px) {
    .common-width {
        width: 900px;
    }

}

@media (max-width: 991px) {
    .custom-width-main {
        width: 100%;
    }

}
</style>