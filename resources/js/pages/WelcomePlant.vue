<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// Recent identifications
const recentIdentifications = ref([
    {
        id: 1,
        plantName: 'Rafflesia arnoldii',
        commonName: 'Corpse Lily',
        imageUrl: 'https://images.unsplash.com/photo-1619266465172-02a857c3556d?q=80&w=1000',
        identifiedAt: '2 days ago',
        probability: 98,
    },
    {
        id: 2,
        plantName: 'Nepenthes rajah',
        commonName: 'Kinabalu Pitcher Plant',
        imageUrl: 'https://images.unsplash.com/photo-1619266465172-02a857c3556d?q=80&w=1000',
        identifiedAt: '5 days ago',
        probability: 95,
    },
    {
        id: 3,
        plantName: 'Musa acuminata',
        commonName: 'Wild Banana',
        imageUrl: 'https://images.unsplash.com/photo-1566393028639-d108a42c46a7?q=80&w=1000',
        identifiedAt: '1 week ago',
        probability: 96,
    },
]);

// Plant stats
const stats = ref({
    totalIdentifications: 2347,
    rarePlantsFound: 38,
    communityMembers: 562,
    databasePlants: 12500,
});

// Featured forum posts
const featuredPosts = ref([
    {
        id: 1,
        title: 'Found this unusual plant in Taman Negara, any ideas?',
        author: 'flora_enthusiast',
        replies: 8,
        date: '3 days ago',
    },
    {
        id: 2,
        title: 'Caring tips for Rafflesia buds',
        author: 'jungle_explorer',
        replies: 15,
        date: '5 days ago',
    },
    {
        id: 3,
        title: 'Spotted a rare Nepenthes rajah in Cameron Highlands',
        author: 'mountainclimber88',
        replies: 23,
        date: '1 week ago',
    },
]);

// Conservation articles
const conservationArticles = ref([
    {
        id: 1,
        title: "Protecting Malaysia's Endemic Flora",
        excerpt: "Learn about ongoing conservation efforts for Malaysia's unique plant species.",
        imageUrl: 'https://images.unsplash.com/photo-1444392061186-9fc38f84f726?q=80&w=1000',
    },
    {
        id: 2,
        title: 'Climate Change Impact on Malaysian Rainforests',
        excerpt: 'How rising temperatures affect plant biodiversity in tropical regions.',
        imageUrl: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1000',
    },
]);

// For the "Plant of the Day" feature
const plantOfTheDay = ref({
    name: 'Garcinia mangostana',
    commonName: 'Mangosteen',
    description:
        'Native to tropical Malaysia, the mangosteen produces sweet-tart fruit with thick purple rind. Its distinctive appearance and delicious flavor have earned it the nickname "Queen of Fruits".',
    conservationStatus: 'Least Concern',
    image: 'https://images.unsplash.com/photo-1605165566807-508fb529cf3e?q=80&w=1000',
});
</script>

<template>
    <AppLayout title="FloraFinder Dashboard">
        <!-- Hero Banner -->
        <div class="relative">
            <div class="absolute inset-0 z-0">
                <img
                    src="https://images.unsplash.com/photo-1470115636492-6d2b56f9146d?q=80&w=2000"
                    alt="Rainforest"
                    class="h-full w-full object-cover brightness-50"
                />
            </div>
            <div class="relative z-10 mx-auto max-w-7xl px-4 py-28 text-center sm:px-6 lg:px-8 lg:py-36">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Discover Malaysian Flora</h1>
                <p class="mx-auto mt-6 max-w-2xl text-xl text-gray-100">
                    Upload plant images for instant identification, learn about native species, and contribute to conservation.
                </p>
                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <Link
                        href="/detect"
                        class="rounded-lg bg-green-600 px-6 py-3 text-base font-medium text-white shadow-lg transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        Identify a Plant
                    </Link>
                    <Link
                        href="/forum"
                        class="rounded-lg bg-white px-6 py-3 text-base font-medium text-green-800 shadow-lg transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white"
                    >
                        Join Community
                    </Link>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl space-y-10 px-4 py-12 sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                    <div class="transform rounded-xl bg-white p-6 text-center shadow-md transition hover:scale-105 dark:bg-gray-800">
                        <div class="mb-2 text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ stats.totalIdentifications }}</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Plants Identified</p>
                    </div>
                    <div class="transform rounded-xl bg-white p-6 text-center shadow-md transition hover:scale-105 dark:bg-gray-800">
                        <div class="mb-2 text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                />
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ stats.rarePlantsFound }}</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rare Species Found</p>
                    </div>
                    <div class="transform rounded-xl bg-white p-6 text-center shadow-md transition hover:scale-105 dark:bg-gray-800">
                        <div class="mb-2 text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                />
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ stats.communityMembers }}</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Community Members</p>
                    </div>
                    <div class="transform rounded-xl bg-white p-6 text-center shadow-md transition hover:scale-105 dark:bg-gray-800">
                        <div class="mb-2 text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                />
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ stats.databasePlants }}</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Plants in Database</p>
                    </div>
                </div>

                <!-- Plant of the Day Section -->
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg dark:bg-gray-800">
                    <div class="md:flex">
                        <div class="md:w-1/2">
                            <img :src="plantOfTheDay.image" :alt="plantOfTheDay.commonName" class="h-full w-full object-cover" />
                        </div>
                        <div class="p-8 md:w-1/2">
                            <div class="text-sm font-semibold uppercase tracking-wide text-green-600 dark:text-green-400">Plant of the Day</div>
                            <h2 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ plantOfTheDay.commonName }}</h2>
                            <p class="mt-1 italic text-gray-500 dark:text-gray-400">{{ plantOfTheDay.name }}</p>
                            <p class="mt-4 text-gray-700 dark:text-gray-300">{{ plantOfTheDay.description }}</p>
                            <div class="mt-6">
                                <span
                                    class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
                                >
                                    {{ plantOfTheDay.conservationStatus }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Identifications -->
                <div>
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Recent Identifications</h2>
                        <Link href="/detect" class="font-medium text-green-600 hover:underline dark:text-green-400"> See All → </Link>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="item in recentIdentifications"
                            :key="item.id"
                            class="overflow-hidden rounded-xl bg-white shadow-md transition hover:shadow-lg dark:bg-gray-800"
                        >
                            <div class="relative">
                                <img :src="item.imageUrl" :alt="item.plantName" class="h-48 w-full object-cover" />
                                <div class="absolute right-2 top-2">
                                    <span class="rounded-full bg-green-600 px-2 py-1 text-xs font-bold text-white">
                                        {{ item.probability }}% Match
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ item.commonName }}</h3>
                                <p class="text-sm italic text-gray-500 dark:text-gray-400">{{ item.plantName }}</p>
                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Identified {{ item.identifiedAt }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Community and Conservation -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Forum Discussions -->
                    <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Community Discussions</h2>
                                <Link href="/forum" class="text-sm font-medium text-green-600 dark:text-green-400">View All</Link>
                            </div>

                            <div class="space-y-4">
                                <div v-for="post in featuredPosts" :key="post.id" class="border-b pb-4 last:border-0 last:pb-0 dark:border-gray-700">
                                    <h3 class="font-medium text-gray-800 hover:text-green-600 dark:text-white dark:hover:text-green-400">
                                        <Link href="#">{{ post.title }}</Link>
                                    </h3>
                                    <div class="mt-2 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>By {{ post.author }}</span>
                                        <span>{{ post.replies }} replies • {{ post.date }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 text-center">
                                <Link href="/forum" class="inline-block rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                    Join the Discussion
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Conservation Articles -->
                    <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Conservation Updates</h2>
                                <Link href="#" class="text-sm font-medium text-green-600 dark:text-green-400">View All</Link>
                            </div>

                            <div class="space-y-6">
                                <div v-for="article in conservationArticles" :key="article.id" class="flex gap-4">
                                    <div class="flex-shrink-0">
                                        <img :src="article.imageUrl" :alt="article.title" class="h-20 w-20 rounded-lg object-cover" />
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-800 hover:text-green-600 dark:text-white dark:hover:text-green-400">
                                            <Link href="#">{{ article.title }}</Link>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ article.excerpt }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Access Section -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <Link href="/detect" class="group">
                        <div class="h-full rounded-xl border-t-4 border-green-500 bg-white p-6 shadow-md transition hover:shadow-lg dark:bg-gray-800">
                            <div class="mb-4 flex items-center">
                                <div class="mr-3 rounded-full bg-green-100 p-2 dark:bg-green-900">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-green-600 dark:text-green-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                                        />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Identify Plants</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300">Upload a photo and get instant plant identification using AI technology</p>
                        </div>
                    </Link>

                    <Link href="#" class="group">
                        <div class="h-full rounded-xl border-t-4 border-blue-500 bg-white p-6 shadow-md transition hover:shadow-lg dark:bg-gray-800">
                            <div class="mb-4 flex items-center">
                                <div class="mr-3 rounded-full bg-blue-100 p-2 dark:bg-blue-900">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                        />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Plant Database</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300">
                                Search our extensive collection of Malaysian native and endemic plant species
                            </p>
                        </div>
                    </Link>

                    <Link href="#" class="group">
                        <div class="h-full rounded-xl border-t-4 border-amber-500 bg-white p-6 shadow-md transition hover:shadow-lg dark:bg-gray-800">
                            <div class="mb-4 flex items-center">
                                <div class="mr-3 rounded-full bg-amber-100 p-2 dark:bg-amber-900">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-amber-600 dark:text-amber-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Report Sightings</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300">
                                Contribute to conservation efforts by reporting plant sightings in the wild
                            </p>
                        </div>
                    </Link>
                </div>

                <!-- About Section -->
                <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                    <div class="md:flex">
                        <div class="p-8 md:w-2/3">
                            <div class="text-sm font-semibold uppercase tracking-wide text-green-600 dark:text-green-400">About FloraFinder</div>
                            <h2 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">Protecting Malaysia's Biodiversity</h2>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">
                                FloraFinder helps identify Malaysian plants using advanced AI technology. Our platform connects plant enthusiasts,
                                conservationists, and researchers to share knowledge and contribute to protecting Malaysia's rich biodiversity.
                            </p>
                            <Link href="#" class="mt-6 inline-flex items-center font-medium text-green-600 dark:text-green-400">
                                Learn more about our mission
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>
                        </div>
                        <div class="flex items-center justify-center bg-gradient-to-br from-green-500 to-emerald-700 p-8 md:w-1/3">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-24 w-24 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
