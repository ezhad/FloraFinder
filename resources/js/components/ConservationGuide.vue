<script setup lang="ts">
import Icon from "@/components/Icon.vue";

// Define ConservationInfo type directly in component 
interface ConservationInfo {
  status: string;
  color: string;
  description: string;
  guide: string;
}

defineProps<{
  conservationInfo: ConservationInfo | null;
  isLoadingConservation: boolean;
}>();
</script>

<template>
  <div
    class="p-6 mt-4 shadow rounded-2xl bg-gradient-to-br from-green-100/80 to-green-200/60 dark:from-green-900/30 dark:to-green-800/30"
  >
    <div class="flex items-center justify-between mb-3">
      <h4
        class="font-semibold tracking-tight text-green-700 dark:text-green-300"
      >
        Conservation Status
      </h4>
      <div class="flex items-center">
        <span
          v-if="isLoadingConservation"
          class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 shadow dark:bg-gray-800 dark:text-gray-200"
        >
          <Icon name="loader-2" class="w-3 h-3 mr-1 animate-spin" />
          Loading...
        </span>
        <span
          v-else-if="conservationInfo"
          class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold shadow"
          :class="{
            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300': 
              ['endangered', 'critically endangered', 'extinct in the wild', 'extinct'].includes(conservationInfo.status.toLowerCase()),
            'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300': 
              conservationInfo.status.toLowerCase() === 'vulnerable',
            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300': 
              conservationInfo.status.toLowerCase() === 'near threatened',
            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300': 
              conservationInfo.status.toLowerCase() === 'least concern',
            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300': 
              conservationInfo.status.toLowerCase() === 'data deficient',
            'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300': 
              ['unknown', 'not evaluated'].includes(conservationInfo.status.toLowerCase()),
          }"
        >
          <Icon name="alert-circle" class="w-3 h-3 mr-1" />
          {{ conservationInfo.status }}
        </span>
      </div>
    </div>
    <p v-if="conservationInfo?.description && !isLoadingConservation" class="mb-3 italic text-xs text-sage-600 dark:text-sage-400">
      {{ conservationInfo.description }}
    </p>
    <div v-if="isLoadingConservation" class="flex items-center justify-center py-6">
      <Icon name="loader-2" class="w-6 h-6 animate-spin text-green-500" />
      <span class="ml-2 text-sm text-sage-600 dark:text-sage-400">
        Fetching conservation information...
      </span>
    </div>
    <template v-else>
      <h5 class="mt-4 mb-1 text-sm font-medium text-sage-700 dark:text-sage-300">
        Conservation Guide
      </h5>
      <p class="text-sm text-sage-800 dark:text-sage-200">
        {{ conservationInfo?.guide }}
      </p>
      <p class="mt-2 text-xs text-sage-500 dark:text-sage-400">
        This guide is based on IUCN data. For critical conservation, consult
        local experts.
      </p>
    </template>
  </div>
</template>
