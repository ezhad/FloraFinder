<script setup lang="ts">
import { useToast } from '@/composables/useToast';
import { computed, TransitionGroup } from 'vue';
import Toast from './Toast.vue';

const { toasts, dismiss } = useToast();

// Compute unique keys for animation
const toastKeys = computed(() => toasts.value.map(toast => toast.id));
</script>

<template>
  <div 
    aria-live="assertive" 
    class="pointer-events-none fixed inset-0 z-50 flex flex-col items-end gap-2 px-4 py-6 sm:p-6"
  >
    <div class="flex w-full flex-col items-end gap-2 sm:max-w-sm">
      <TransitionGroup name="toast">
        <Toast
          v-for="toast in toasts"
          :key="toast.id"
          :toast="toast"
          class="pointer-events-auto"
          @dismiss="dismiss"
        />
      </TransitionGroup>
    </div>
  </div>
</template>

<style scoped>
.toast-enter-active, .toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  transform: translateY(10px);
  opacity: 0;
}

.toast-leave-to {
  transform: translateX(100%);
  opacity: 0;
}
</style>