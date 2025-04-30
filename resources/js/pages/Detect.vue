<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, reactive, onMounted, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

// Define types
interface FormData {
  image: File | null;
  organ: string;
}

interface PlantResult {
  success: boolean;
  message?: string;
  error?: string;
  data?: {
    query?: {
      project?: string;
      images?: string[];
      organs?: string[];
      includeRelatedImages?: boolean;
      noReject?: boolean;
      type?: string | null;
    };
    predictedOrgans?: Array<{
      image?: string;
      filename?: string;
      organ?: string;
      score?: number;
    }>;
    language?: string;
    preferedReferential?: string;
    bestMatch?: string;
    results: Array<{
      score: number;
      species: {
        scientificNameWithoutAuthor: string;
        scientificNameAuthorship: string;
        genus: {
          scientificNameWithoutAuthor: string;
          scientificNameAuthorship: string;
          scientificName: string;
        };
        family: {
          scientificNameWithoutAuthor: string;
          scientificNameAuthorship: string;
          scientificName: string;
        };
        commonNames: string[];
        scientificName: string;
      };
      images?: Array<{
        url: {
          s: string;
          m: string;
          l: string;
        };
        organ?: string;
        author?: string;
        license?: string;
        citation?: string;
      }>;
      gbif?: {
        id: string;
      };
      powo?: {
        id: string;
      };
      iucn?: {
        id: string;
        category: string;
      };
    }>;
    version?: string;
    remainingIdentificationRequests?: number;
  };
}

interface Errors {
  image?: string;
  organ?: string;
  [key: string]: string | undefined;
}

// Props
const props = defineProps<{
  plantData?: PlantResult
}>();



// State
const form = reactive<FormData>({
  image: null,
  organ: 'flower'
});

const imagePreview = ref<string | null>(null);
const processing = ref<boolean>(false);
const results = ref<PlantResult | null>(null);
const errors = reactive<Errors>({});
const activeImageIndex = ref<number>(0);
const selectedResultIndex = ref<number>(0);
const fileUploadRef = ref<HTMLInputElement | null>(null);

// Initialize results from props if available
onMounted(() => {
  if (props.plantData && props.plantData.success) {
    results.value = props.plantData as PlantResult;
  }
});


// Computed property to determine if we have results to show
const hasResults = computed(() => {
  return results.value &&
         results.value.success &&
         results.value.data &&
         results.value.data.results &&
         results.value.data.results.length > 0;
});

// Computed property for the selected result
const selectedResult = computed(() => {
  if (!hasResults.value) return null;
  return results.value!.data!.results[selectedResultIndex.value];
});

// Methods
const onImageChange = (e: Event): void => {
  const target = e.target as HTMLInputElement;
  if (!target.files || !target.files.length) return;

  const file = target.files[0];
  form.image = file;
  imagePreview.value = URL.createObjectURL(file);
  errors.image = undefined;
};

const resetForm = (): void => {
  form.image = null;
  imagePreview.value = null;

  // Only reset results if they came from API, not from props
  if (!props.initialData) {
    results.value = null;
  } else {
    // If we have initialData props, restore those
    results.value = props.initialData as PlantResult;
  }
};

const openFileUpload = (): void => {
  if (fileUploadRef.value) {
    fileUploadRef.value.click();
  }
};

const identifyPlant = async (): Promise<void> => {
  // Clear previous errors
  Object.keys(errors).forEach(key => delete errors[key]);

  // Validate
  if (!form.image) {
    errors.image = 'Please select an image';
    return;
  }

  processing.value = true;

  // We'll still allow new identification even with initialData
  results.value = null;

  // Create form data
  const formData = new FormData();
  formData.append('image', form.image);
  formData.append('organ', form.organ);

  // Send request
  try {
    router.post(route('plant-identifier.identify'), formData, {
      onSuccess: (page) => {

        console.log('Full page props:', page.props);
        console.log('Flash data structure:', page.props.flash);

        // Check if the flash data exists before accessing it
        if (page.props.flash && page.props.flash.data) {
          results.value = page.props.flash.data;
          selectedResultIndex.value = 0;
          activeImageIndex.value = 0;
        } else {
          results.value = {
            success: false,
            message: 'No response data received',
            error: 'The server returned an empty response'
          };
        }
      },
      onError: (validationErrors) => {
        Object.assign(errors, validationErrors);
      },
      onFinish: () => {
        processing.value = false;
      },
      preserveScroll: true
    });
  } catch (error: any) {
    results.value = {
      success: false,
      message: 'An unexpected error occurred',
      error: error.message
    };
    processing.value = false;
  }
};

const getScoreClass = (score: number): string => {
  if (score > 0.7) return 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300';
  if (score > 0.4) return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300';
  return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
};

const setActiveImage = (index: number): void => {
  activeImageIndex.value = index;
};

const selectResult = (index: number): void => {
  selectedResultIndex.value = index;
  activeImageIndex.value = 0;
};

const organs = [
  { value: 'flower', label: 'Flower', icon: '🌸' },
  { value: 'leaf', label: 'Leaf', icon: '🍃' },
  { value: 'fruit', label: 'Fruit', icon: '🍎' },
  { value: 'bark', label: 'Bark', icon: '🌳' },
  { value: 'habit', label: 'Whole Plant', icon: '🪴' },
  { value: 'other', label: 'Other', icon: '❓' },
];


</script>

<template>
  <AppLayout title="Plant Identification">
    <div class="py-6">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Plant Identification</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">
            Upload a photo of a plant to identify it using our advanced AI technology.
          </p>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <!-- Upload Panel -->
          <div class="col-span-1 space-y-6 lg:col-span-1">
            <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
              <div class="border-b border-gray-200 bg-gradient-to-br from-green-500 to-emerald-600 p-5 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-white">Upload Image</h2>
                <p class="mt-1 text-sm text-green-100">Take a clear photo for best results</p>
              </div>

              <div class="p-5">
                <form @submit.prevent="identifyPlant" class="space-y-5">
                  <!-- Image Upload -->
                  <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Plant Image</label>
                    <div
                      class="mt-1 flex cursor-pointer justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 pb-6 pt-5 transition-colors hover:border-gray-400 dark:border-gray-600 dark:hover:border-gray-500"
                      :class="{ 'border-green-500 dark:border-green-500': imagePreview }"
                      @click="!imagePreview && openFileUpload()"
                    >
                      <div v-if="!imagePreview" class="space-y-2 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                          <svg
                            class="h-6 w-6 text-green-600 dark:text-green-400"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                          </svg>
                        </div>
                        <div class="flex justify-center text-sm">
                          <label
                            for="file-upload"
                            class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300"
                          >
                            <span>Upload a photo</span>
                            <input
                              id="file-upload"
                              ref="fileUploadRef"
                              name="file-upload"
                              type="file"
                              class="sr-only"
                              @change="onImageChange"
                              accept="image/*"
                            />
                          </label>
                          <p class="pl-1 text-gray-500 dark:text-gray-400">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 10MB</p>
                      </div>
                      <div v-else class="relative w-full">
                        <img :src="imagePreview" class="mx-auto max-h-56 rounded-md object-contain" alt="Plant preview" />
                        <button
                          type="button"
                          class="absolute right-0 top-0 rounded-full bg-red-100 p-1 text-red-600 hover:bg-red-200 hover:text-red-800 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50"
                          @click.stop="resetForm"
                        >
                          <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                              fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </div>
                    </div>
                    <p v-if="errors.image" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.image }}</p>
                  </div>

                  <!-- Rest of the template remains the same -->

                  <!-- Plant Organ Selection -->
                  <div>
                    <label for="organ" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Plant Part</label>
                    <div class="grid grid-cols-3 gap-2">
                      <div
                        v-for="organ in organs"
                        :key="organ.value"
                        @click="form.organ = organ.value"
                        class="flex cursor-pointer flex-col items-center rounded-md border p-3 transition-all hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-750"
                        :class="{
                          'border-green-500 bg-green-50 dark:border-green-500 dark:bg-green-900/20': form.organ === organ.value,
                          'border-gray-200 dark:border-gray-700': form.organ !== organ.value
                        }"
                      >
                        <span class="text-xl">{{ organ.icon }}</span>
                        <span class="mt-1 text-xs text-gray-700 dark:text-gray-300">{{ organ.label }}</span>
                      </div>
                    </div>
                    <p v-if="errors.organ" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.organ }}</p>
                  </div>

                  <!-- Submit Button -->
                  <div>
                    <button
                      type="submit"
                      class="flex w-full items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-sm transition-colors hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-700 dark:hover:bg-green-600"
                      :disabled="processing"
                    >
                      <svg
                        v-if="processing"
                        class="mr-3 -ml-1 h-5 w-5 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                      >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path
                          class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                      </svg>
                      <span>{{ processing ? 'Analyzing Image...' : 'Identify Plant' }}</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Rest of your template remains unchanged -->
          </div>

          <!-- Results Panel - Keep as is -->
          <div class="col-span-1 lg:col-span-2">
            <!-- Keep the rest of your results panel as is -->
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
