<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, reactive, onMounted, computed } from 'vue';
import { router } from '@inertiajs/vue3';

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
    results.value = props.plantData;
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

  // Restore results from props if available
  if (props.plantData) {
    results.value = props.plantData;
  } else {
    results.value = null;
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

  // Reset results before new identification
  results.value = null;

  // Create form data
  const formData = new FormData();
  formData.append('image', form.image);
  formData.append('organ', form.organ);

  // Send request
  try {
    router.post(route('plant-identifier.identify'), formData, {
      onSuccess: (page) => {
        // Access the plant data directly from page props
        if (page.props.plantData) {
          results.value = page.props.plantData as PlantResult;

          // If identification was successful, reset indices
          if (results.value.success && results.value.data) {
            selectedResultIndex.value = 0;
            activeImageIndex.value = 0;
          }
        } else {
          // Handle the case where no plant data was returned
          results.value = {
            success: false,
            message: 'No plant data was returned from the server',
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
      <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
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
            <div class="overflow-hidden bg-white shadow-md rounded-xl dark:bg-gray-800">
              <div class="p-5 border-b border-gray-200 bg-gradient-to-br from-green-500 to-emerald-600 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-white">Upload Image</h2>
                <p class="mt-1 text-sm text-green-100">Take a clear photo for best results</p>
              </div>

              <div class="p-5">
                <form @submit.prevent="identifyPlant" class="space-y-5">
                  <!-- Image Upload -->
                  <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Plant Image</label>
                    <div
                      class="flex justify-center px-6 pt-5 pb-6 mt-1 transition-colors border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-gray-400 dark:border-gray-600 dark:hover:border-gray-500"
                      :class="{ 'border-green-500 dark:border-green-500': imagePreview }"
                      @click="!imagePreview && openFileUpload()"
                    >
                      <div v-if="!imagePreview" class="space-y-2 text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full dark:bg-green-900/30">
                          <svg
                            class="w-6 h-6 text-green-600 dark:text-green-400"
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
                            class="relative font-medium text-green-600 rounded-md cursor-pointer hover:text-green-500 dark:text-green-400 dark:hover:text-green-300"
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
                        <img :src="imagePreview" class="object-contain mx-auto rounded-md max-h-56" alt="Plant preview" />
                        <button
                          type="button"
                          class="absolute top-0 right-0 p-1 text-red-600 bg-red-100 rounded-full hover:bg-red-200 hover:text-red-800 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50"
                          @click.stop="resetForm"
                        >
                          <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                              fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 111.414 1.414L11.414 10l4.293 4.293a1 1 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 01-1.414-1.414L8.586 10 4.293 5.707a1 1 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </div>
                    </div>
                    <p v-if="errors.image" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.image }}</p>
                  </div>

                  <!-- Plant Organ Selection -->
                  <div>
                    <label for="organ" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Plant Part</label>
                    <div class="grid grid-cols-3 gap-2">
                      <div
                        v-for="organ in organs"
                        :key="organ.value"
                        @click="form.organ = organ.value"
                        class="flex flex-col items-center p-3 transition-all border rounded-md cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-750"
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
                      class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition-colors bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-700 dark:hover:bg-green-600"
                      :disabled="processing"
                    >
                      <svg
                        v-if="processing"
                        class="w-5 h-5 mr-3 -ml-1 text-white animate-spin"
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
          </div>

          <!-- Results Panel -->
          <div class="col-span-1 lg:col-span-2">
            <div v-if="hasResults" class="overflow-hidden bg-white shadow-md rounded-xl dark:bg-gray-800">
              <div class="p-5 border-b border-gray-200 bg-gradient-to-br from-green-500 to-emerald-600 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-white">Identification Results</h2>
                <p class="mt-1 text-sm text-green-100">Here are the plants that match your image</p>
              </div>

              <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                <!-- Main Image Display -->
                <div class="col-span-1">
                  <div v-if="selectedResult && selectedResult.images && selectedResult.images.length > 0" class="relative h-64 overflow-hidden rounded-lg">
                    <img
                      :src="selectedResult.images[activeImageIndex].url.m"
                      class="object-cover w-full h-full"
                      alt="Plant Image"
                    />
                    <div class="absolute bottom-0 left-0 right-0 p-2 text-xs text-white bg-black bg-opacity-50">
                      {{ selectedResult.images[activeImageIndex].author || 'Unknown author' }}
                    </div>
                  </div>
                  <div v-else class="flex items-center justify-center h-64 bg-gray-100 rounded-lg dark:bg-gray-700">
                    <p class="text-gray-500 dark:text-gray-400">No image available</p>
                  </div>

                  <!-- Image Thumbnails -->
                  <div v-if="selectedResult && selectedResult.images && selectedResult.images.length > 1" class="flex mt-2 space-x-2 overflow-x-auto">
                    <button
                      v-for="(image, index) in selectedResult.images"
                      :key="index"
                      @click="setActiveImage(index)"
                      class="flex-shrink-0 w-16 h-16 overflow-hidden rounded-md focus:outline-none"
                      :class="{ 'ring-2 ring-green-500': activeImageIndex === index }"
                    >
                      <img :src="image.url.s" class="object-cover w-full h-full" alt="Thumbnail" />
                    </button>
                  </div>
                </div>

                <!-- Result Details -->
                <div class="col-span-1">
                  <div v-if="selectedResult" class="space-y-4">
                    <div>
                      <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        {{ selectedResult.species.scientificName }}
                      </h3>
                      <p class="text-sm text-gray-600 dark:text-gray-400" v-if="selectedResult.species.commonNames && selectedResult.species.commonNames.length">
                        {{ selectedResult.species.commonNames.join(', ') }}
                      </p>
                    </div>

                    <div>
                      <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Confidence:</span>
                        <span
                          class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full"
                          :class="getScoreClass(selectedResult.score)"
                        >
                          {{ Math.round(selectedResult.score * 100) }}%
                        </span>
                      </div>
                    </div>

                    <div class="space-y-1">
                      <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Taxonomy</p>
                      <div class="p-3 text-sm rounded-md bg-gray-50 dark:bg-gray-750">
                        <div class="grid grid-cols-2 gap-y-1">
                          <span class="text-gray-600 dark:text-gray-400">Family:</span>
                          <span class="font-medium text-gray-900 dark:text-white">{{ selectedResult.species.family.scientificNameWithoutAuthor }}</span>

                          <span class="text-gray-600 dark:text-gray-400">Genus:</span>
                          <span class="font-medium text-gray-900 dark:text-white">{{ selectedResult.species.genus.scientificNameWithoutAuthor }}</span>

                          <span class="text-gray-600 dark:text-gray-400">Species:</span>
                          <span class="font-medium text-gray-900 dark:text-white">{{ selectedResult.species.scientificNameWithoutAuthor }}</span>
                        </div>
                      </div>
                    </div>

                    <!-- External links if available -->
                    <div v-if="selectedResult.gbif || selectedResult.powo || selectedResult.iucn" class="flex flex-wrap gap-2">
                      <a
                        v-if="selectedResult.gbif"
                        :href="`https://www.gbif.org/species/${selectedResult.gbif.id}`"
                        target="_blank"
                        class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400"
                      >
                        GBIF Database
                      </a>
                      <a
                        v-if="selectedResult.powo"
                        :href="`https://powo.science.kew.org/taxon/urn:lsid:ipni.org:names:${selectedResult.powo.id}`"
                        target="_blank"
                        class="px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400"
                      >
                        Plants of the World
                      </a>
                      <a
                        v-if="selectedResult.iucn"
                        :href="`https://www.iucnredlist.org/species/${selectedResult.iucn.id}`"
                        target="_blank"
                        class="px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400"
                      >
                        IUCN Conservation Status: {{ selectedResult.iucn.category }}
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Other Results -->
              <div class="p-5 border-t border-gray-200 dark:border-gray-700">
                <h3 class="mb-3 text-lg font-medium text-gray-900 dark:text-white">Other Possibilities</h3>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
                  <button
                    v-for="(result, index) in results.data.results"
                    :key="index"
                    @click="selectResult(index)"
                    class="flex flex-col items-start p-3 text-left transition-colors border rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-750"
                    :class="{
                      'border-green-500 bg-green-50 dark:border-green-500/20 dark:bg-green-900/20': selectedResultIndex === index,
                      'border-gray-200 dark:border-gray-700': selectedResultIndex !== index
                    }"
                  >
                    <div class="flex items-center justify-between w-full mb-2">
                      <span class="font-medium text-gray-900 truncate dark:text-white" style="max-width: 80%;">
                        {{ result.species.scientificNameWithoutAuthor }}
                      </span>
                      <span
                        class="px-2 py-0.5 text-xs font-medium rounded-full"
                        :class="getScoreClass(result.score)"
                      >
                        {{ Math.round(result.score * 100) }}%
                      </span>
                    </div>
                    <span class="text-xs text-gray-600 truncate dark:text-gray-400" style="max-width: 100%;">
                      {{ result.species.commonNames && result.species.commonNames.length ? result.species.commonNames[0] : 'No common name' }}
                    </span>
                  </button>
                </div>
              </div>
            </div>

            <!-- No Results State -->
            <div v-else-if="results && !results.success" class="p-5 overflow-hidden bg-white shadow-md rounded-xl dark:bg-gray-800">
              <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="flex items-center justify-center w-16 h-16 mb-4 bg-red-100 rounded-full dark:bg-red-900/20">
                  <svg class="w-8 h-8 text-red-600 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9h2V7H9v2zm0 4h2v-2H9v2z" clip-rule="evenodd" />
                  </svg>
                </div>
                <h3 class="mb-1 text-lg font-medium text-gray-900 dark:text-white">Identification Failed</h3>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">{{ results.message || results.error || 'We couldn\'t identify your plant. Please try again with a clearer photo.' }}</p>
                <button
                  @click="resetForm"
                  class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-700 dark:hover:bg-green-600"
                >
                  Try Again
                </button>
              </div>
            </div>

            <!-- Processing State -->
            <div v-else-if="processing" class="p-5 overflow-hidden bg-white shadow-md rounded-xl dark:bg-gray-800">
              <div class="flex flex-col items-center justify-center py-8 text-center">
                <svg class="w-12 h-12 mb-4 text-green-600 animate-spin dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <h3 class="mb-1 text-lg font-medium text-gray-900 dark:text-white">Analyzing Your Plant</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">This might take a few moments.</p>
              </div>
            </div>

            <!-- Waiting for Upload State -->
            <div v-else class="p-5 overflow-hidden bg-white shadow-md rounded-xl dark:bg-gray-800">
              <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="flex items-center justify-center w-16 h-16 mb-4 bg-green-100 rounded-full dark:bg-green-900/20">
                  <svg class="w-8 h-8 text-green-600 dark:text-green-400" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <h3 class="mb-1 text-lg font-medium text-gray-900 dark:text-white">Ready to Identify</h3>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Upload a plant photo to start the identification process.</p>
                <button
                  @click="openFileUpload"
                  class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-700 dark:hover:bg-green-600"
                >
                  Upload Photo
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
