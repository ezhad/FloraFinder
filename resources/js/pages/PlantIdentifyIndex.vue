<script setup lang="ts">
  import { ref, reactive } from 'vue';
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
          }
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
      language?: string;
      preferedReferential?: string;
      bestMatch?: string;
      version?: string;
      remainingIdentificationRequests?: number;
    };
  }

  interface Errors {
    image?: string;
    organ?: string;
    [key: string]: string | undefined;
  }

  //declare prop
  const data = defineProps(['data'])

  console.log('data', data);


  // State
  const form = reactive<FormData>({
    image: null,
    organ: 'flower'
  });

  const imagePreview = ref<string | null>(null);
  const processing = ref<boolean>(false);
  const results = ref<PlantResult | null>(null);
  const errors = reactive<Errors>({});

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
    results.value = null;
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
    results.value = null;

    // Create form data
    const formData = new FormData();
    formData.append('image', form.image);
    formData.append('organ', form.organ);

    // Send request
    try {
      router.post(route('plant-identifier.identify'), formData, {
        onSuccess: (page) => {
          results.value = page.props.flash.data;
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
    if (score > 0.8) return 'bg-green-100 text-green-800';
    if (score > 0.5) return 'bg-yellow-100 text-yellow-800';
    return 'bg-gray-100 text-gray-800';
  };
  </script>

<template>
    <div class="min-h-screen bg-green-50 py-12">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="bg-green-600 p-6">
            <h1 class="text-2xl font-bold text-white">Plant Identifier</h1>
            <p class="text-green-100 mt-1">Upload an image to identify plants using PlantNet API</p>
          </div>

          <!-- Upload Form -->
          <div class="p-6 border-b border-gray-200">
            <form @submit.prevent="identifyPlant" class="space-y-6">
              <!-- Image Upload -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Plant Image
                </label>
                <div
                  class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
                  :class="{ 'border-green-500': imagePreview }"
                >
                  <div class="space-y-1 text-center" v-if="!imagePreview">
                    <svg
                      class="mx-auto h-12 w-12 text-gray-400"
                      stroke="currentColor"
                      fill="none"
                      viewBox="0 0 48 48"
                      aria-hidden="true"
                    >
                      <path
                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                      <label
                        for="file-upload"
                        class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500"
                      >
                        <span>Upload an image</span>
                        <input
                          id="file-upload"
                          name="file-upload"
                          type="file"
                          class="sr-only"
                          @change="onImageChange"
                          accept="image/*"
                        />
                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                  </div>
                  <div v-else class="w-full">
                    <img
                      :src="imagePreview"
                      class="mx-auto max-h-64 object-contain"
                      alt="Plant preview"
                    />
                    <button
                      type="button"
                      class="mt-2 text-sm text-red-600 hover:text-red-800"
                      @click="resetForm"
                    >
                      Remove image
                    </button>
                  </div>
                </div>
                <p v-if="errors.image" class="mt-1 text-sm text-red-600">
                  {{ errors.image }}
                </p>
              </div>

              <!-- Plant Organ Selection -->
              <div>
                <label for="organ" class="block text-sm font-medium text-gray-700">
                  Plant Organ
                </label>
                <select
                  id="organ"
                  v-model="form.organ"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                >
                  <option value="flower">Flower</option>
                  <option value="leaf">Leaf</option>
                  <option value="fruit">Fruit</option>
                  <option value="bark">Bark</option>
                  <option value="habit">Habit (whole plant)</option>
                  <option value="other">Other</option>
                </select>
                <p v-if="errors.organ" class="mt-1 text-sm text-red-600">
                  {{ errors.organ }}
                </p>
              </div>

              <!-- Submit Button -->
              <div>
                <button
                  type="submit"
                  class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                  :disabled="processing"
                >
                  <svg
                    v-if="processing"
                    class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <circle
                      class="opacity-25"
                      cx="12"
                      cy="12"
                      r="10"
                      stroke="currentColor"
                      stroke-width="4"
                    ></circle>
                    <path
                      class="opacity-75"
                      fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                  </svg>
                  {{ processing ? 'Identifying...' : 'Identify Plant' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Results Section -->
          <div v-if="results" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Identification Results</h2>

            <div v-if="!results.success" class="bg-red-50 p-4 rounded-md mb-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg
                    class="h-5 w-5 text-red-400"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-red-800">
                    Error: {{ results.message }}
                  </h3>
                  <div class="mt-2 text-sm text-red-700">
                    <p>{{ results.error }}</p>
                  </div>
                </div>
              </div>
            </div>

            <div v-else-if="results.data && results.data.results">
              <p class="text-sm text-gray-500 mb-4">
                Showing top {{ results.data.results.length }} matches
              </p>

              <div class="space-y-4">
                <div
                  v-for="(plant, index) in results.data.results"
                  :key="index"
                  class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow"
                >
                  <div class="p-4 flex items-start">
                    <!-- Plant Image -->
                    <div class="flex-shrink-0 mr-4" v-if="plant.images && plant.images.length">
                      <img
                        :src="plant.images[0].url.s"
                        class="h-20 w-20 object-cover rounded-md"
                        :alt="plant.species.scientificNameWithoutAuthor"
                      />
                    </div>

                    <!-- Plant Details -->
                    <div class="flex-1">
                      <div class="flex justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                          {{ plant.species.scientificNameWithoutAuthor }}
                        </h3>
                        <span class="text-sm font-medium rounded-full px-2 py-1"
                          :class="getScoreClass(plant.score)">
                          {{ (plant.score * 100).toFixed(1) }}% match
                        </span>
                      </div>
                      <p class="text-sm text-gray-600 italic">
                        {{ plant.species.family.scientificNameWithoutAuthor }}
                      </p>
                      <div class="mt-2">
                        <p class="text-sm text-gray-800" v-if="plant.species.commonNames && plant.species.commonNames.length">
                          Common names: {{ plant.species.commonNames.join(', ') }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="bg-yellow-50 p-4 rounded-md">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg
                    class="h-5 w-5 text-yellow-400"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-yellow-800">
                    No plants identified
                  </h3>
                  <div class="mt-2 text-sm text-yellow-700">
                    <p>Try uploading a clearer image or selecting a different plant organ.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

