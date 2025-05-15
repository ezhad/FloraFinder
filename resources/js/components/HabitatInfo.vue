<script setup lang="ts">
import Icon from "@/components/Icon.vue";

// Define HabitatInfo type directly in component
interface HabitatInfo {
  name: string;
  description: string;
  climate: string;
}

defineProps<{
  habitatInfo: HabitatInfo | null;
  isLoadingHabitat: boolean;
}>();
</script>

<template>
  <div
    class="p-6 mt-4 shadow rounded-2xl bg-gradient-to-br from-blue-100/80 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30"
  >
    <div class="flex items-center justify-between mb-3">
      <h4 class="font-semibold tracking-tight text-blue-700 dark:text-blue-300">
        Habitat & Growing Conditions
      </h4>
      <span
        v-if="isLoadingHabitat"
        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 shadow dark:bg-gray-800 dark:text-gray-200"
      >
        <Icon name="loader-2" class="w-3 h-3 mr-1 animate-spin" />
        Loading...
      </span>
    </div>
    <div v-if="isLoadingHabitat" class="flex items-center justify-center py-6">
      <Icon name="loader-2" class="w-6 h-6 animate-spin text-blue-500" />
      <span class="ml-2 text-sm text-sage-600 dark:text-sage-400">
        Fetching habitat information...
      </span>
    </div>
    <div v-else-if="habitatInfo" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      <div>
        <p
          class="text-xs font-semibold tracking-wide uppercase text-blue-600/80 dark:text-blue-400"
        >
          Type
        </p>
        <p class="mt-1 font-medium text-sage-900 dark:text-white">
          {{ habitatInfo.name }}
        </p>
      </div>
      <div>
        <p
          class="text-xs font-semibold tracking-wide uppercase text-blue-600/80 dark:text-blue-400"
        >
          Climate
        </p>
        <p class="mt-1 font-medium text-sage-900 dark:text-white">
          {{ habitatInfo.climate }}
        </p>
      </div>
      <div class="sm:col-span-2">
        <p
          class="text-xs font-semibold tracking-wide uppercase text-blue-600/80 dark:text-blue-400"
        >
          Description
        </p>
        <p class="mt-1 text-sm text-sage-800 dark:text-sage-200">
          {{ habitatInfo.description }}
        </p>
      </div>
      <div class="sm:col-span-2 mt-2">
        <p class="text-xs text-sage-500 dark:text-sage-400">
          Data sourced from GBIF and other biodiversity databases
        </p>
      </div>
    </div>
  </div>
</template>
