<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dental Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-5 p-5">
            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    class="flex items-center rounded-2xl border border-indigo-100/50 bg-white p-5 shadow-sm transition-all hover:shadow-md dark:border-indigo-900/30 dark:bg-gray-800 dark:hover:border-indigo-800/50"
                >
                    <div
                        class="mr-5 flex h-14 w-14 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Patients</p>
                        <p class="text-xl font-semibold text-gray-800 dark:text-white">1,254</p>
                    </div>
                </div>

                <div
                    class="flex items-center rounded-2xl border border-teal-100/50 bg-white p-5 shadow-sm transition-all hover:shadow-md dark:border-teal-900/30 dark:bg-gray-800 dark:hover:border-teal-800/50"
                >
                    <div
                        class="mr-5 flex h-14 w-14 items-center justify-center rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Appointments</p>
                        <p class="text-xl font-semibold text-gray-800 dark:text-white">
                            42 <span class="text-sm font-normal text-gray-500 dark:text-gray-400">this week</span>
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center rounded-2xl border border-amber-100/50 bg-white p-5 shadow-sm transition-all hover:shadow-md dark:border-amber-900/30 dark:bg-gray-800 dark:hover:border-amber-800/50"
                >
                    <div
                        class="mr-5 flex h-14 w-14 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Treatments</p>
                        <p class="text-xl font-semibold text-gray-800 dark:text-white">
                            156 <span class="text-sm font-normal text-gray-500 dark:text-gray-400">this month</span>
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center rounded-2xl border border-rose-100/50 bg-white p-5 shadow-sm transition-all hover:shadow-md dark:border-rose-900/30 dark:bg-gray-800 dark:hover:border-rose-800/50"
                >
                    <div
                        class="mr-5 flex h-14 w-14 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"
                            />
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</p>
                        <p class="text-xl font-semibold text-gray-800 dark:text-white">$24,350</p>
                    </div>
                </div>
            </div>

            <!-- Middle Section -->
            <div class="grid gap-5 lg:grid-cols-3">
                <!-- Appointments Card -->
                <div class="dark:bg-sidebar-background rounded-2xl border border-sidebar-border/70 bg-white p-6 shadow-sm dark:border-sidebar-border">
                    <div class="mb-5 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Upcoming Appointments</h3>
                        <button
                            class="rounded-lg bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-600 transition-colors hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50"
                        >
                            View All
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div
                            class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50"
                        >
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 flex-shrink-0 rounded-full bg-indigo-100 text-center leading-10 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300"
                                >
                                    JD
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-gray-800 dark:text-white">John Doe</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Teeth Cleaning</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">10:30 AM</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Today</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50"
                        >
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 flex-shrink-0 rounded-full bg-rose-100 text-center leading-10 text-rose-600 dark:bg-rose-900/30 dark:text-rose-300"
                                >
                                    SS
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-gray-800 dark:text-white">Sarah Smith</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Dental Consultation</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">2:15 PM</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Today</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50"
                        >
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 flex-shrink-0 rounded-full bg-amber-100 text-center leading-10 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300"
                                >
                                    MJ
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-gray-800 dark:text-white">Mike Johnson</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Root Canal</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">9:00 AM</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Tomorrow</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Treatment Categories Card -->
                <div class="dark:bg-sidebar-background rounded-2xl border border-sidebar-border/70 bg-white p-6 shadow-sm dark:border-sidebar-border">
                    <div class="mb-5 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Treatment Categories</h3>
                        <button
                            class="rounded-lg bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-600 transition-colors hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50"
                        >
                            Details
                        </button>
                    </div>
                    <div class="space-y-5">
                        <div class="w-full">
                            <div class="mb-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="mr-3 h-3 w-3 rounded-full bg-indigo-500"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Cleaning</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">45%</span>
                            </div>
                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                <div class="h-2.5 rounded-full bg-indigo-500" style="width: 45%"></div>
                            </div>
                        </div>

                        <div class="w-full">
                            <div class="mb-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="mr-3 h-3 w-3 rounded-full bg-blue-500"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Fillings</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">30%</span>
                            </div>
                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                <div class="h-2.5 rounded-full bg-blue-500" style="width: 30%"></div>
                            </div>
                        </div>

                        <div class="w-full">
                            <div class="mb-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="mr-3 h-3 w-3 rounded-full bg-amber-500"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Root Canal</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">15%</span>
                            </div>
                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                <div class="h-2.5 rounded-full bg-amber-500" style="width: 15%"></div>
                            </div>
                        </div>

                        <div class="w-full">
                            <div class="mb-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="mr-3 h-3 w-3 rounded-full bg-emerald-500"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Others</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">10%</span>
                            </div>
                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                <div class="h-2.5 rounded-full bg-emerald-500" style="width: 10%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Staff Performance Card -->
                <div class="dark:bg-sidebar-background rounded-2xl border border-sidebar-border/70 bg-white p-6 shadow-sm dark:border-sidebar-border">
                    <div class="mb-5 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Staff Performance</h3>
                        <button
                            class="rounded-lg bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-600 transition-colors hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50"
                        >
                            View All
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <img
                                src="https://randomuser.me/api/portraits/women/54.jpg"
                                alt="Dr. Martinez"
                                class="h-10 w-10 rounded-full object-cover"
                            />
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-800 dark:text-white">Dr. Martinez</p>
                                    <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">98%</p>
                                </div>
                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div class="h-1.5 rounded-full bg-indigo-500" style="width: 98%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Dr. Johnson" class="h-10 w-10 rounded-full object-cover" />
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-800 dark:text-white">Dr. Johnson</p>
                                    <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">94%</p>
                                </div>
                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div class="h-1.5 rounded-full bg-indigo-500" style="width: 94%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <img
                                src="https://randomuser.me/api/portraits/women/68.jpg"
                                alt="Dr. Wilson"
                                class="h-10 w-10 rounded-full object-cover"
                            />
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-800 dark:text-white">Dr. Wilson</p>
                                    <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">92%</p>
                                </div>
                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div class="h-1.5 rounded-full bg-indigo-500" style="width: 92%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Dr. Garcia" class="h-10 w-10 rounded-full object-cover" />
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-800 dark:text-white">Dr. Garcia</p>
                                    <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">89%</p>
                                </div>
                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div class="h-1.5 rounded-full bg-indigo-500" style="width: 89%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Patient Records -->
            <div class="dark:bg-sidebar-background rounded-2xl border border-sidebar-border/70 bg-white p-6 shadow-sm dark:border-sidebar-border">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Patient Records</h3>
                    <button
                        class="rounded-lg bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-600 transition-colors hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50"
                    >
                        View All
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="border-b text-left text-sm dark:border-gray-700">
                                <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Patient</th>
                                <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Treatment</th>
                                <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Doctor</th>
                                <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Date</th>
                                <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Status</th>
                                <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b text-sm dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Emma Wilson</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">Teeth Cleaning</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">Dr. Martinez</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">May 10, 2023</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300"
                                        >Completed</span
                                    >
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <button class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        View
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b text-sm dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Robert Brown</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">Root Canal</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">Dr. Johnson</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">May 9, 2023</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300"
                                        >Follow-up</span
                                    >
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <button class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        View
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b text-sm dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Lisa Taylor</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">Tooth Extraction</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">Dr. Martinez</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">May 8, 2023</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300"
                                        >Completed</span
                                    >
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <button class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        View
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b text-sm dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Daniel Cooper</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">Dental Crown</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">Dr. Wilson</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">May 7, 2023</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300"
                                        >In Progress</span
                                    >
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <button class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
