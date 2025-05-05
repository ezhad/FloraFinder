<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'; 
import { computed, ref } from 'vue';

// Forum categories
const categories = ref([
    { id: 1, name: 'Plant Identification', description: 'Help with identifying plants', totalTopics: 32, icon: 'magnifying-glass' },
    { id: 2, name: 'Gardening Tips', description: 'Share advice on plant care', totalTopics: 45, icon: 'sparkles' },
    { id: 3, name: 'Rare Finds', description: 'Discuss rare Malaysian flora', totalTopics: 18, icon: 'star' },
    { id: 4, name: 'Conservation', description: 'Topics about plant conservation', totalTopics: 28, icon: 'globe-asia-australia' },
    { id: 5, name: 'General Discussion', description: 'Other plant-related discussions', totalTopics: 56, icon: 'chat-bubble-left-right' },
]);

// Mock forum topics
const forumTopics = ref([
    {
        id: 1,
        title: 'Found this unusual plant in Taman Negara, any ideas?',
        category: 'Plant Identification',
        author: 'flora_enthusiast',
        date: '2023-10-15',
        replies: 8,
        views: 124,
        lastReplyBy: 'botanist42',
        lastReplyDate: '2023-10-17',
        pinned: true,
    },
    {
        id: 2,
        title: 'Caring tips for Rafflesia buds',
        category: 'Gardening Tips',
        author: 'jungle_explorer',
        date: '2023-10-12',
        replies: 15,
        views: 230,
        lastReplyBy: 'plant_whisperer',
        lastReplyDate: '2023-10-16',
        pinned: false,
    },
    {
        id: 3,
        title: 'Spotted a rare Nepenthes rajah in Cameron Highlands',
        category: 'Rare Finds',
        author: 'mountainclimber88',
        date: '2023-10-10',
        replies: 23,
        views: 312,
        lastReplyBy: 'pitcherplant_lover',
        lastReplyDate: '2023-10-15',
        pinned: false,
    },
    {
        id: 4,
        title: 'New conservation effort for endemic Malaysian orchids',
        category: 'Conservation',
        author: 'orchid_researcher',
        date: '2023-10-08',
        replies: 12,
        views: 186,
        lastReplyBy: 'forestprotector',
        lastReplyDate: '2023-10-14',
        pinned: false,
    },
    {
        id: 5,
        title: 'Introducing myself: New plant enthusiast from Penang',
        category: 'General Discussion',
        author: 'newbie_gardener',
        date: '2023-10-05',
        replies: 9,
        views: 104,
        lastReplyBy: 'gardenmentor',
        lastReplyDate: '2023-10-13',
        pinned: false,
    },
]);

// Active category filter
const activeCategory = ref<number | null>(null);

// New topic form
const showNewTopicForm = ref(false);
const newTopic = ref({
    title: '',
    category: '',
    content: '',
});

// Search functionality
const searchQuery = ref('');
const searchResults = ref([]);

// Filter topics based on active category
const filteredTopics = computed(() => {
    if (!activeCategory.value) return forumTopics.value;

    const category = categories.value.find((c) => c.id === activeCategory.value);
    if (!category) return forumTopics.value;

    return forumTopics.value.filter((topic) => topic.category === category.name);
});

const filterByCategory = (categoryId: number) => {
    activeCategory.value = activeCategory.value === categoryId ? null : categoryId;
};

const submitNewTopic = () => {
    // This would normally submit to a backend API
    console.log('New topic submitted:', newTopic.value);

    // Mock adding the new topic
    const newTopicObj = {
        id: forumTopics.value.length + 1,
        title: newTopic.value.title,
        category: newTopic.value.category,
        author: 'current_user', // would come from auth
        date: new Date().toISOString().split('T')[0],
        replies: 0,
        views: 0,
        lastReplyBy: null,
        lastReplyDate: null,
        pinned: false,
    };

    forumTopics.value.unshift(newTopicObj);

    // Reset form
    newTopic.value = { title: '', category: '', content: '' };
    showNewTopicForm.value = false;
};

const searchTopics = () => {
    if (!searchQuery.value.trim()) {
        searchResults.value = [];
        return;
    }

    const query = searchQuery.value.toLowerCase();
    searchResults.value = forumTopics.value.filter(
        (topic) => topic.title.toLowerCase().includes(query) || topic.category.toLowerCase().includes(query),
    );
};

// Mock forum stats
const forumStats = ref({
    totalTopics: 179,
    totalPosts: 2345,
    totalMembers: 563,
    newestMember: 'plant_lover123',
});
</script>

<template>
    <AppLayout title="Forum">
        <div class="py-6">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">FloraFinder Forum</h1>

                    <div class="flex space-x-3">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="searchQuery"
                                @input="searchTopics"
                                placeholder="Search topics..."
                                class="dark:bg-sidebar-item-active block w-full rounded-md border-0 py-1.5 pl-10 pr-3 text-gray-900 focus:ring-2 focus:ring-indigo-600 dark:text-white dark:focus:ring-indigo-500 sm:text-sm"
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>

                        <button
                            @click="showNewTopicForm = true"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            New Topic
                        </button>
                    </div>
                </div>

                <!-- Forum Categories -->
                <div class="mb-8 overflow-hidden bg-white border shadow-sm rounded-2xl border-sidebar-border/70 dark:bg-sidebar">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white">Categories</h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div
                            v-for="category in categories"
                            :key="category.id"
                            class="flex items-start p-6 transition-colors cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                            :class="{ 'dark:bg-sidebar-item-active bg-gray-50': activeCategory === category.id }"
                            @click="filterByCategory(category.id)"
                        >
                            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full dark:bg-indigo-900/30">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-indigo-600 dark:text-indigo-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"
                                        v-if="category.icon === 'magnifying-glass'"
                                    />
                                    <path
                                        d="M5 3a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0118 15H2a1 1 0 01-.707-1.707l.804-.804L2.22 12H2a2 2 0 01-2-2V5a2 2 0 012-2h3z"
                                        v-if="category.icon === 'chat-bubble-left-right'"
                                    />
                                    <path
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                        v-if="category.icon === 'sparkles'"
                                    />
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                        v-if="category.icon === 'star'"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z"
                                        clip-rule="evenodd"
                                        v-if="category.icon === 'globe-asia-australia'"
                                    />
                                </svg>
                            </div>
                            <div class="flex-1 ml-4">
                                <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{ category.name }}</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ category.description }}</p>
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-500">{{ category.totalTopics }} topics</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Forum Topics -->
                <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-sidebar-border/70 dark:bg-sidebar">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                            {{ activeCategory ? categories.find((c) => c.id === activeCategory)?.name + ' Topics' : 'Recent Topics' }}
                        </h2>
                        <button
                            v-if="activeCategory"
                            @click="activeCategory = null"
                            class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
                        >
                            Show All
                        </button>
                    </div>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Search results -->
                        <div v-if="searchQuery && searchResults.length > 0" class="p-3 bg-indigo-50 dark:bg-indigo-900/20">
                            <p class="text-sm text-gray-700 dark:text-gray-300">Found {{ searchResults.length }} results for "{{ searchQuery }}"</p>
                        </div>

                        <!-- Topics list -->
                        <div
                            v-for="topic in searchQuery ? searchResults : filteredTopics"
                            :key="topic.id"
                            class="p-6 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800"
                        >
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <span v-if="topic.pinned" class="inline-flex mr-2">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4 text-indigo-500"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M9.828.722a.5.5 0 01.354 0l9 3A.5.5 0 0120 4v2.5a.5.5 0 01-.5.5h-.55c-.57 0-1.08.188-1.537.527-.46.34-.868.817-1.16 1.394-.29.576-.473 1.218-.525 1.826-.052.608.02 1.143.15 1.553.132.41.337.75.591.975.254.225.566.361.905.361H18.5a.5.5 0 01.5.5V16a.5.5 0 01-.5.5h-9a.5.5 0 01-.5-.5v-2.5a.5.5 0 01.5-.5h1.052c.34 0 .65-.136.905-.362.254-.224.459-.564.59-.974.132-.41.203-.945.15-1.553-.051-.608-.233-1.25-.525-1.826-.29-.577-.698-1.054-1.158-1.394-.46-.34-.968-.527-1.537-.527H9.5a.5.5 0 01-.5-.5V4a.5.5 0 01.497-.5H9l.308.102L9.828.722zM9.5 9h1c1.105 0 2 .672 2 1.5S11.605 12 10.5 12h-1a.5.5 0 00-.5.5v.5h2a.5.5 0 010 1H9a.5.5 0 01-.5-.5V12a.5.5 0 01.5-.5h1c.552 0 1-.225 1-.5s-.448-.5-1-.5h-1a.5.5 0 00-.5.5v1a.5.5 0 01-1 0v-1c0-1.105.895-2 2-2zm-4 2a2.5 2.5 0 100 5 2.5 2.5 0 000-5z"
                                                />
                                            </svg>
                                        </span>
                                        <h3
                                            class="text-lg font-medium text-gray-800 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400"
                                        >
                                            <a href="#">{{ topic.title }}</a>
                                        </h3>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300"
                                        >
                                            {{ topic.category }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Posted by {{ topic.author }} on {{ topic.date }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end ml-4 text-sm text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 mr-1"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                                            />
                                        </svg>
                                        {{ topic.replies }} replies
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 mr-1"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                        {{ topic.views }} views
                                    </div>
                                    <div v-if="topic.lastReplyBy" class="mt-2 text-xs">
                                        <span
                                            >Last reply by <span class="font-medium">{{ topic.lastReplyBy }}</span> on {{ topic.lastReplyDate }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="filteredTopics.length === 0 && !searchQuery" class="p-8 text-center">
                            <p class="text-gray-600 dark:text-gray-400">No topics found in this category.</p>
                        </div>

                        <div v-if="searchQuery && searchResults.length === 0" class="p-8 text-center">
                            <p class="text-gray-600 dark:text-gray-400">No results found for "{{ searchQuery }}"</p>
                        </div>
                    </div>
                </div>

                <!-- Forum Stats -->
                <div class="p-6 mt-8 bg-white border shadow-sm rounded-2xl border-sidebar-border/70 dark:bg-sidebar">
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Topics</p>
                            <p class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white">{{ forumStats.totalTopics }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Posts</p>
                            <p class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white">{{ forumStats.totalPosts }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Members</p>
                            <p class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white">{{ forumStats.totalMembers }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Newest Member</p>
                            <p class="mt-2 text-xl font-semibold text-indigo-600 dark:text-indigo-400">{{ forumStats.newestMember }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Topic Modal -->
        <div v-if="showNewTopicForm" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75"
                    aria-hidden="true"
                    @click="showNewTopicForm = false"
                ></div>

                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-sidebar sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
                >
                    <div class="px-4 pt-5 pb-4 bg-white dark:bg-sidebar sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">Create New Topic</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="topic-title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                        <input
                                            type="text"
                                            id="topic-title"
                                            v-model="newTopic.title"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-sidebar-item-active focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:text-white sm:text-sm"
                                            placeholder="Enter topic title"
                                        />
                                    </div>

                                    <div>
                                        <label for="topic-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                            >Category</label
                                        >
                                        <select
                                            id="topic-category"
                                            v-model="newTopic.category"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-sidebar-item-active focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:text-white sm:text-sm"
                                        >
                                            <option value="" disabled>Select a category</option>
                                            <option v-for="category in categories" :key="category.id" :value="category.name">
                                                {{ category.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="topic-content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                                        <textarea
                                            id="topic-content"
                                            v-model="newTopic.content"
                                            rows="5"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-sidebar-item-active focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:text-white sm:text-sm"
                                            placeholder="Write your post content here..."
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-t bg-gray-50 dark:border-gray-700 dark:bg-sidebar sm:flex sm:flex-row-reverse sm:px-6">
                        <button
                            type="button"
                            @click="submitNewTopic"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                            :disabled="!newTopic.title || !newTopic.category || !newTopic.content"
                        >
                            Post Topic
                        </button>
                        <button
                            type="button"
                            @click="showNewTopicForm = false"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-sidebar-item-active dark:hover:bg-sidebar-item-active hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:text-gray-300 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.divide-y > div:not(:first-child) {
    border-top-width: 1px;
}
</style>
