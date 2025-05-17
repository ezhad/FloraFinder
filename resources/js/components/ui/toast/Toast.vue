<script setup lang="ts">
import { cn } from '@/lib/utils';
import { CheckCircle, AlertCircle, XCircle, X } from 'lucide-vue-next';
import { computed, type HTMLAttributes } from 'vue';
import type { Toast } from '@/composables/useToast';

const props = defineProps<{
  toast: Toast;
  class?: HTMLAttributes['class'];
}>();

const emit = defineEmits<{
  dismiss: [id: string];
}>();

const handleDismiss = () => {
  emit('dismiss', props.toast.id);
};

const variantIcon = computed(() => {
  switch (props.toast.variant) {
    case 'success':
      return CheckCircle;
    case 'destructive':
      return XCircle;
    default:
      return AlertCircle;
  }
});

const variantStyles = computed(() => {
  switch (props.toast.variant) {
    case 'success':
      return 'border-green-500 bg-green-50 text-green-800 dark:border-green-600 dark:bg-green-950/50 dark:text-green-300';
    case 'destructive':
      return 'border-red-500 bg-red-50 text-red-800 dark:border-red-600 dark:bg-red-950/50 dark:text-red-300';
    default:
      return 'border-gray-200 bg-white text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100';
  }
});

const iconColor = computed(() => {
  switch (props.toast.variant) {
    case 'success':
      return 'text-green-600 dark:text-green-400';
    case 'destructive':
      return 'text-red-600 dark:text-red-400';
    default:
      return 'text-gray-600 dark:text-gray-400';
  }
});
</script>

<template>
  <div
    :class="cn(
      'pointer-events-auto flex w-full items-center justify-between space-x-4 overflow-hidden rounded-lg border p-4 shadow-md transition-all',
      variantStyles,
      props.class
    )"
    data-state="open"
  >
    <div class="flex items-start gap-3">
      <component :is="variantIcon" class="h-5 w-5" :class="iconColor" />
      <div class="grid gap-1">
        <div v-if="toast.title" class="text-sm font-semibold">{{ toast.title }}</div>
        <div v-if="toast.description" class="text-sm opacity-90">{{ toast.description }}</div>
      </div>
    </div>
    <button
      class="ml-auto flex h-6 w-6 items-center justify-center rounded-md hover:bg-gray-200/80 dark:hover:bg-gray-700/80"
      @click="handleDismiss"
    >
      <X class="h-4 w-4" />
    </button>
  </div>
</template>