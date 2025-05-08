<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Leaf, Camera, BookOpen, Search, ChevronRight } from "lucide-vue-next";

const plantOfTheDay = {
  name: "Garcinia mangostana",
  commonName: "Mangosteen",
  description:
    'Native to tropical Malaysia, the mangosteen produces sweet-tart fruit with thick purple rind. Its distinctive appearance and delicious flavor have earned it the nickname "Queen of Fruits".',
  conservationStatus: "Least Concern",
  image: "https://images.unsplash.com/photo-1605165566807-508fb529cf3e?q=80&w=1000",
};
</script>

<template>
  <AppLayout title="FloraFinder">
    <!-- Hero Section - Simplified and more elegant -->
    <div class="relative overflow-hidden">
      <!-- Subtle pattern background with gradient overlay -->
      <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-900 dark:to-gray-900 opacity-90"></div>
      <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2220%22 height=%2220%22 viewBox=%220 0 20 20%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath d=%22M0 0c2.8 0 5 2.2 5 5s-2.2 5-5 5v5h5c0-2.8 2.2-5 5-5s5 2.2 5 5v-5c-2.8 0-5-2.2-5-5s2.2-5 5-5H0z%22 fill=%22%23047857%22 fill-opacity=%220.05%22 fill-rule=%22evenodd%22/%3E%3C/svg%3E')] opacity-30 dark:opacity-10"></div>

      <div class="relative z-10 max-w-4xl mx-auto px-4 py-20 flex flex-col items-center text-center">
        <!-- Logo and title -->
        <div class="flex items-center mb-6">
          <div class="p-3 bg-white dark:bg-gray-800 rounded-full shadow-sm">
            <Leaf class="w-7 h-7 text-green-600 dark:text-green-400" />
          </div>
          <h1 class="ml-3 text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
            FloraFinder
          </h1>
        </div>

        <!-- Tagline with improved typography -->
        <p class="text-lg sm:text-xl text-gray-700 dark:text-gray-300 max-w-2xl mb-10 leading-relaxed">
          Discover and identify Malaysian plants with precision and elegance
        </p>

        <!-- Action buttons with refined styling -->
        <div class="flex flex-col sm:flex-row gap-3 w-full max-w-md mb-16">
          <Link
            href="/detect"
            class="flex items-center justify-center gap-2 px-6 py-3 text-base font-medium text-white bg-green-600 rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors w-full"
          >
            <Camera class="w-5 h-5" />
            <span>Identify Plant</span>
          </Link>

          <Link
            href="/explore"
            class="flex items-center justify-center gap-2 px-6 py-3 text-base font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-750 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors w-full"
          >
            <Search class="w-5 h-5" />
            <span>Browse Plants</span>
          </Link>
        </div>
      </div>
    </div>

    <!-- Plant of the Day - Elegantly styled card -->
    <div class="max-w-5xl mx-auto px-4 -mt-12 mb-12">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="sm:flex">
          <!-- Image section -->
          <div class="sm:w-2/5 relative">
            <div class="absolute top-4 left-4">
              <div class="px-3 py-1 text-xs font-medium bg-white/90 dark:bg-gray-800/90 rounded-full text-green-600 dark:text-green-400">
                Plant of the Day
              </div>
            </div>

            <img
              :src="plantOfTheDay.image"
              :alt="plantOfTheDay.commonName"
              class="w-full h-64 sm:h-full object-cover"
            />

            <div class="absolute bottom-4 left-4">
              <div class="px-3 py-1 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/60 text-yellow-800 dark:text-yellow-300 rounded-full">
                {{ plantOfTheDay.conservationStatus }}
              </div>
            </div>
          </div>

          <!-- Content section -->
          <div class="p-6 sm:p-8 sm:w-3/5">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
              {{ plantOfTheDay.commonName }}
            </h2>

            <div class="text-sm italic text-gray-600 dark:text-gray-400 mb-4 font-serif">
              {{ plantOfTheDay.name }}
            </div>

            <p class="text-gray-700 dark:text-gray-300 mb-6 text-sm leading-relaxed">
              {{ plantOfTheDay.description }}
            </p>

            <Link
              href="/plants/mangosteen"
              class="inline-flex items-center text-sm text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-medium group"
            >
              <BookOpen class="w-4 h-4 mr-1.5" />
              Learn more
              <ChevronRight class="w-4 h-4 ml-0.5 transition-transform duration-200 group-hover:translate-x-0.5" />
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section - Minimal and elegant approach -->
    <div class="bg-gray-50 dark:bg-gray-900/30 py-12">
      <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Feature Card 1 -->
          <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
              <Camera class="w-5 h-5 text-green-600 dark:text-green-400" />
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Instant Identification</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">
              Upload a photo and get accurate plant identifications in seconds
            </p>
          </div>

          <!-- Feature Card 2 -->
          <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
              <Leaf class="w-5 h-5 text-green-600 dark:text-green-400" />
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Native Species</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">
              Explore Malaysia's rich biodiversity of endemic plant species
            </p>
          </div>

          <!-- Feature Card 3 -->
          <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
              <BookOpen class="w-5 h-5 text-green-600 dark:text-green-400" />
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Educational Resource</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">
              Learn about plant characteristics, habitats, and conservation status
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
