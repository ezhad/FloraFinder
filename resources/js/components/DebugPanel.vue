<script setup lang="ts">
import { ref, watch } from "vue";
import Icon from "@/components/Icon.vue";

const props = defineProps<{
  active: boolean;
  data: any;
  title?: string;
}>();

const isOpen = ref(false);
const jsonDisplay = ref("");

watch(
  () => props.data,
  (newData) => {
    if (newData) {
      jsonDisplay.value = JSON.stringify(newData, null, 2);
    } else {
      jsonDisplay.value = "No data available";
    }
  },
  { immediate: true }
);
</script>

<template>
  <div
    v-if="active"
    class="mt-4 rounded-lg border border-amber-200 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-800"
  >
    <div class="p-4">
      <div class="flex items-center justify-between">
        <h3 class="text-sm font-medium text-amber-800 dark:text-amber-300">
          {{ title || "Debug Information" }}
        </h3>
        <button
          @click="isOpen = !isOpen"
          class="text-amber-700 dark:text-amber-400 hover:text-amber-900 dark:hover:text-amber-300"
        >
          <Icon :name="isOpen ? 'chevron-up' : 'chevron-down'" class="w-4 h-4" />
        </button>
      </div>
      <div v-if="isOpen" class="mt-2">
        <pre
          class="p-3 text-xs overflow-auto bg-white dark:bg-black/30 rounded border border-amber-200 dark:border-amber-800 max-h-64 text-gray-700 dark:text-gray-300"
          >{{ jsonDisplay }}</pre
        >
      </div>
    </div>
  </div>
</template>
