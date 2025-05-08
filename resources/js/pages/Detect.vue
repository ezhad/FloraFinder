<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { ref, reactive, onMounted, computed } from "vue";
import { router } from "@inertiajs/vue3";
import { Card, CardHeader, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import Icon from "@/components/Icon.vue";
import { useToast } from "@/composables/useToast";

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
  plantData?: PlantResult;
}>();

const { toast } = useToast();

// State
const form = reactive<FormData>({
  image: null,
  organ: "flower",
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
  return (
    results.value &&
    results.value.success &&
    results.value.data &&
    results.value.data.results &&
    results.value.data.results.length > 0
  );
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

const handleDragOver = (e: DragEvent): void => {
  if (e.target instanceof HTMLElement) {
    e.target.classList.add("ring-2", "ring-green-400");
  }
};

const handleDragLeave = (e: DragEvent): void => {
  if (e.target instanceof HTMLElement) {
    e.target.classList.remove("ring-2", "ring-green-400");
  }
};

const handleDrop = (e: DragEvent): void => {
  if (e.target instanceof HTMLElement) {
    e.target.classList.remove("ring-2", "ring-green-400");
  }

  if (!processing.value && e.dataTransfer) {
    const files = e.dataTransfer.files;
    if (files && files.length) {
      form.image = files[0];
      imagePreview.value = URL.createObjectURL(files[0]);
      errors.image = undefined;
    }
  }
};

const identifyPlant = async (): Promise<void> => {
  // Clear previous errors
  Object.keys(errors).forEach((key) => delete errors[key]);

  // Validate
  if (!form.image) {
    errors.image = "Please select an image";
    return;
  }

  processing.value = true;

  // Reset results before new identification
  results.value = null;

  // Create form data
  const formData = new FormData();
  formData.append("image", form.image);
  formData.append("organ", form.organ);

  // Send request
  try {
    router.post(route("plant-identifier.identify"), formData, {
      onSuccess: (page) => {
        // Access the plant data directly from page props
        if (page.props.plantData) {
          results.value = page.props.plantData as PlantResult;

          // If identification was successful, reset indices
          if (results.value.success && results.value.data) {
            selectedResultIndex.value = 0;
            activeImageIndex.value = 0;
            toast({
              title: "Plant Identified",
              description: "We've found potential matches for your plant.",
              variant: "success",
            });
          }
        } else {
          // Handle the case where no plant data was returned
          results.value = {
            success: false,
            message: "No plant data was returned from the server",
          };
        }
      },
      onError: (validationErrors) => {
        Object.assign(errors, validationErrors);
        toast({
          title: "Identification Failed",
          description: "Please check your inputs and try again.",
          variant: "destructive",
        });
      },
      onFinish: () => {
        processing.value = false;
      },
      preserveScroll: true,
    });
  } catch (error: any) {
    results.value = {
      success: false,
      message: "An unexpected error occurred",
      error: error.message,
    };
    processing.value = false;
    toast({
      title: "Something went wrong",
      description: error.message || "An unexpected error occurred.",
      variant: "destructive",
    });
  }
};

const setActiveImage = (index: number): void => {
  activeImageIndex.value = index;
};

const selectResult = (index: number): void => {
  selectedResultIndex.value = index;
  activeImageIndex.value = 0;
};

const organs = [
  { value: "flower", label: "Flower", icon: "flower" },
  { value: "leaf", label: "Leaf", icon: "leaf" },
  { value: "fruit", label: "Fruit", icon: "apple" },
  { value: "bark", label: "Bark", icon: "tree" },
  { value: "habit", label: "Whole Plant", icon: "sprout" },
  { value: "other", label: "Other", icon: "help-circle" },
];

// Dummy conservation guide function
function getConservationAdvice(scientificName: string): string {
  // You can expand this with more species or logic
  switch (scientificName.toLowerCase()) {
    case "rosa canina":
      return "Protect from overharvesting. Encourage growth by pruning and avoid use of pesticides. Support local pollinators.";
    case "quercus robur":
      return "Preserve mature trees, avoid soil compaction around roots, and support natural regeneration.";
    default:
      return "Maintain natural habitat, avoid overharvesting, and support local biodiversity. Consult local experts for more details.";
  }
}
</script>

<template>
  <AppLayout title="Plant Identifier">
    <!-- Main Content -->
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
        <!-- Left Column: Upload Panel -->
        <div class="md:col-span-5 lg:col-span-4">
          <Card
            class="overflow-hidden border-0 shadow-xl rounded-3xl backdrop-blur-md bg-white/70 dark:bg-moss-900/60 ring-1 ring-moss-200 dark:ring-moss-800"
          >
            <CardHeader
              class="pb-6 border-b-0 shadow-sm text-moss-900 dark:text-white bg-gradient-to-r from-moss-100/80 to-moss-200/60 dark:from-moss-900/80 dark:to-moss-800/60 rounded-t-3xl"
            >
              <div class="flex items-center justify-center">
                <Icon name="camera" class="w-5 h-5 mr-2 text-moss-400" />
                <h2 class="text-lg font-semibold tracking-tight">Upload Plant Image</h2>
              </div>
            </CardHeader>
            <CardContent class="px-8 py-8 space-y-8 bg-transparent">
              <!-- Image Upload Area -->
              <div>
                <div
                  class="group relative cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed border-moss-300 dark:border-moss-700 bg-white/60 dark:bg-moss-900/40 hover:bg-moss-100/60 dark:hover:bg-moss-800/40 transition-all shadow-inner min-h-[180px] flex flex-col items-center justify-center backdrop-blur-md"
                  :class="{ 'border-none': imagePreview }"
                  @click="!processing && openFileUpload()"
                  @dragover.prevent="handleDragOver"
                  @dragleave.prevent="handleDragLeave"
                  @drop.prevent="handleDrop"
                >
                  <div
                    v-if="!imagePreview"
                    class="flex flex-col items-center justify-center px-6 py-10 text-center"
                  >
                    <p
                      class="mb-2 text-base font-medium tracking-tight text-moss-700 dark:text-moss-200"
                    >
                      Drag & drop or click to upload
                    </p>
                    <p class="text-xs text-moss-500 dark:text-moss-400">
                      JPG, PNG or GIF (max 10MB)
                    </p>
                    <div
                      class="flex items-center gap-2 mt-4 text-xs text-moss-400 dark:text-moss-400"
                    >
                      <Icon name="info" class="w-3 h-3" />
                      <span>Clear, well-lit photos yield the best results</span>
                    </div>
                  </div>
                  <div
                    v-else
                    class="relative aspect-[4/3] w-full overflow-hidden rounded-2xl shadow-lg"
                  >
                    <img
                      :src="imagePreview"
                      alt="Plant preview"
                      class="object-cover w-full h-full transition-transform duration-200 group-hover:scale-105"
                    />
                    <div
                      class="absolute inset-0 flex flex-col items-center justify-center gap-2 transition-opacity opacity-0 bg-black/30 group-hover:opacity-100 backdrop-blur-sm"
                    >
                      <Button
                        variant="outline"
                        size="sm"
                        class="rounded-full shadow text-moss-800 bg-white/90 hover:bg-white"
                        @click.stop="openFileUpload"
                      >
                        <Icon name="image" class="w-4 h-4 mr-1" />
                        Change Image
                      </Button>
                      <Button
                        variant="outline"
                        size="sm"
                        class="text-red-700 border-red-300 rounded-full shadow bg-white/90 hover:bg-red-50"
                        @click.stop="resetForm"
                      >
                        <Icon name="x" class="w-4 h-4 mr-1" />
                        Clear Image
                      </Button>
                    </div>
                  </div>
                </div>
                <input
                  ref="fileUploadRef"
                  type="file"
                  class="hidden"
                  accept="image/*"
                  @change="onImageChange"
                />
                <p
                  v-if="errors.image"
                  class="mt-2 text-xs font-medium text-red-600 dark:text-red-400"
                >
                  {{ errors.image }}
                </p>
                <div v-if="imagePreview" class="flex justify-center mt-3 md:hidden">
                  <Button
                    variant="outline"
                    size="sm"
                    class="text-red-700 border-red-300 rounded-full shadow bg-white/90 hover:bg-red-50"
                    @click="resetForm"
                  >
                    <Icon name="x" class="w-4 h-4 mr-1" />
                    Clear Image
                  </Button>
                </div>
              </div>
              <!-- Plant Part Selector -->
              <div>
                <h3
                  class="mb-3 text-sm font-semibold tracking-tight text-sage-700 dark:text-sage-200"
                >
                  Select Plant Part
                </h3>
                <div class="grid grid-cols-3 gap-3" @click.stop>
                  <div
                    v-for="organ in organs"
                    :key="organ.value"
                    class="relative cursor-pointer group"
                    @click.stop="!processing && (form.organ = organ.value)"
                    :aria-pressed="form.organ === organ.value"
                    role="button"
                    tabindex="0"
                    @keydown.enter.space="!processing && (form.organ = organ.value)"
                  >
                    <div
                      class="flex flex-col items-center justify-center p-3 transition-all rounded-xl border shadow-sm duration-200"
                      :class="
                        form.organ === organ.value
                          ? 'border-moss-600 bg-moss-50/90 dark:border-moss-400 dark:bg-moss-900/60 shadow-lg ring-2 ring-moss-400 scale-105'
                          : 'border-sage-200 bg-white/70 hover:border-moss-300 hover:bg-moss-50/60 dark:border-sage-700 dark:bg-sage-900/40 dark:hover:border-moss-500 dark:hover:bg-moss-900/30'
                      "
                    >
                      <span
                        :class="
                          form.organ === organ.value ? 'text-3xl' : 'text-2xl opacity-80'
                        "
                      >
                        {{
                          organ.value === "flower"
                            ? "🌸"
                            : organ.value === "leaf"
                            ? "🍃"
                            : organ.value === "fruit"
                            ? "🍎"
                            : organ.value === "bark"
                            ? "🌳"
                            : organ.value === "habit"
                            ? "🌱"
                            : "❓"
                        }}
                      </span>
                      <span
                        class="mt-1 text-xs font-medium transition-colors duration-200"
                        :class="
                          form.organ === organ.value
                            ? 'text-moss-900 dark:text-moss-200 font-semibold'
                            : 'text-sage-600 dark:text-sage-400'
                        "
                      >
                        {{ organ.label }}
                      </span>
                      <span
                        v-if="form.organ === organ.value"
                        class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-moss-500 shadow-md border-2 border-white dark:border-moss-900"
                        aria-label="Selected"
                      ></span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Identify Button -->
              <Button
                class="w-full text-white transition-all duration-200 shadow-lg bg-gradient-to-r from-black to-black/80 hover:from-black/90 hover:to-black dark:text-white rounded-xl"
                size="lg"
                :disabled="processing || !imagePreview"
                @click="identifyPlant"
              >
                <div class="flex items-center justify-center">
                  <template v-if="processing">
                    <Icon name="loader-2" class="w-4 h-4 mr-2 animate-spin" />
                    Identifying...
                  </template>
                  <template v-else>
                    <Icon name="search" class="w-4 h-4 mr-2" />
                    Identify Plant
                  </template>
                </div>
              </Button>
            </CardContent>
          </Card>
        </div>

        <!-- Right Column: Results -->
        <div class="space-y-6 md:col-span-7 lg:col-span-8">
          <!-- Loading State -->
          <Card
            v-if="processing"
            class="overflow-hidden border-0 shadow-xl bg-white/80 dark:bg-black/60 rounded-3xl backdrop-blur-md"
          >
            <CardContent
              class="flex flex-col items-center justify-center py-20 text-center"
            >
              <div
                class="p-4 mb-4 rounded-full shadow-lg animate-bounce bg-gradient-to-br from-green-400/40 to-green-700/40"
              >
                <Icon name="loader-2" class="w-10 h-10 text-green-500 animate-spin" />
              </div>
              <h3
                class="text-lg font-semibold tracking-tight text-sage-900 dark:text-white"
              >
                Identifying Your Plant
              </h3>
              <p class="mt-2 text-base text-black dark:text-black">
                We're analyzing your image using AI to determine the plant species.
              </p>
            </CardContent>
          </Card>

          <!-- Error State -->
          <Card
            v-else-if="results && !results.success"
            class="overflow-hidden border-0 shadow-xl bg-white/80 dark:bg-black/60 rounded-3xl backdrop-blur-md"
          >
            <CardContent
              class="flex flex-col items-center justify-center py-20 text-center"
            >
              <div class="p-4 mb-4 rounded-full shadow bg-red-900/30">
                <Icon name="x-circle" class="w-10 h-10 text-red-500" />
              </div>
              <h3 class="text-lg font-semibold tracking-tight text-red-500">
                Identification Failed
              </h3>
              <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                {{ results.message || "An error occurred while identifying your plant." }}
              </p>
              <p
                v-if="results.error"
                class="mt-4 text-xs text-gray-600 dark:text-gray-400"
              >
                {{ results.error }}
              </p>
              <Button
                variant="outline"
                size="sm"
                class="mt-6 text-gray-700 border-gray-300 rounded-full hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800"
                @click="resetForm"
              >
                <Icon name="refresh-cw" class="w-4 h-4 mr-2" /> Try Again
              </Button>
            </CardContent>
          </Card>

          <!-- Results Display -->
          <template v-else-if="hasResults">
            <!-- Primary Result -->
            <Card
              class="overflow-hidden border-0 shadow-xl rounded-3xl backdrop-blur-md bg-white/80 dark:bg-sage-900/60 ring-1 ring-sage-200 dark:ring-sage-800"
            >
              <CardHeader
                class="pb-6 border-b-0 shadow-sm text-sage-900 dark:text-white bg-gradient-to-r from-sage-100/80 to-sage-200/60 dark:from-sage-900/80 dark:to-sage-800/60 rounded-t-3xl"
              >
                <div class="flex items-center">
                  <Icon name="check-circle" class="w-5 h-5 mr-2 text-moss-400" />
                  <h2 class="text-lg font-semibold tracking-tight">
                    Identification Result
                  </h2>
                  <span
                    v-if="selectedResult"
                    class="ml-auto rounded-full px-2 py-0.5 text-xs font-medium shadow"
                    :class="
                      (selectedResult.score || 0) > 0.5
                        ? 'bg-green-600 text-white'
                        : (selectedResult.score || 0) > 0.25
                        ? 'bg-yellow-400 text-yellow-900'
                        : 'bg-red-600 text-white'
                    "
                  >
                    {{ Math.round((selectedResult?.score || 0) * 100) }}% confidence
                  </span>
                </div>
              </CardHeader>
              <CardContent class="px-8 py-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
                  <!-- Image Gallery -->
                  <div class="md:col-span-5">
                    <div class="space-y-6">
                      <div
                        class="overflow-hidden shadow-lg rounded-2xl bg-white/60 dark:bg-sage-900/40"
                      >
                        <img
                          v-if="selectedResult && selectedResult.images?.length"
                          :src="selectedResult.images[activeImageIndex].url.m"
                          class="object-cover w-full h-72 md:h-96 rounded-2xl"
                          alt="Plant image"
                        />
                        <div
                          v-else
                          class="flex items-center justify-center w-full bg-gray-100 h-72 rounded-2xl md:h-96 dark:bg-gray-700"
                        >
                          <Icon
                            name="image-off"
                            class="w-12 h-12 text-gray-400 dark:text-gray-500"
                          />
                        </div>
                      </div>

                      <!-- Image Thumbnails -->
                      <div
                        v-if="
                          selectedResult &&
                          selectedResult.images &&
                          selectedResult.images.length > 1
                        "
                        class="flex pb-1 space-x-3 overflow-auto"
                      >
                        <button
                          v-for="(img, idx) in selectedResult.images"
                          :key="idx"
                          @click="setActiveImage(idx)"
                          class="flex-shrink-0 overflow-hidden transition-all border-2 rounded-lg shadow-sm hover:scale-105"
                          :class="
                            activeImageIndex === idx
                              ? 'border-green-500 dark:border-green-400 shadow-md'
                              : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'
                          "
                        >
                          <img
                            :src="img.url.s"
                            class="object-cover w-16 h-16 rounded-lg"
                            alt="Thumbnail"
                          />
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Plant Information -->
                  <div class="md:col-span-7">
                    <div class="space-y-6">
                      <div>
                        <h3
                          class="flex items-center gap-2 text-2xl font-bold leading-tight tracking-tight text-sage-900 dark:text-white"
                        >
                          {{ selectedResult?.species.commonNames?.[0] || "Unknown" }}
                          <span
                            class="ml-2 text-lg italic font-normal text-sage-500 dark:text-sage-300"
                            >({{ selectedResult?.species.scientificName }})</span
                          >
                        </h3>
                        <div class="flex flex-wrap gap-2 mt-3">
                          <span
                            v-if="selectedResult?.iucn?.category"
                            :class="[
                              'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold shadow',
                              selectedResult.iucn.category === 'Endangered'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-yellow-100 text-yellow-800',
                            ]"
                          >
                            <Icon name="alert-triangle" class="w-3 h-3 mr-1" />
                            {{ selectedResult.iucn.category }}
                          </span>
                          <span
                            class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800 shadow"
                          >
                            <Icon name="leaf" class="w-3 h-3 mr-1" />
                            Habitat: Forest
                          </span>
                          <span
                            class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800 shadow"
                          >
                            <Icon name="clock" class="w-3 h-3 mr-1" />
                            Lifespan: Perennial
                          </span>
                          <span
                            class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-800 shadow"
                          >
                            <Icon name="watering-can" class="w-3 h-3 mr-1" />
                            Care: Moderate
                          </span>
                        </div>
                      </div>
                      <div
                        class="grid grid-cols-2 gap-6 p-6 text-sm shadow rounded-2xl bg-sage-50/80 dark:bg-sage-800/40"
                      >
                        <div>
                          <p
                            class="text-xs font-semibold tracking-wide uppercase text-sage-500 dark:text-sage-400"
                          >
                            Family
                          </p>
                          <p class="mt-1 font-medium text-sage-900 dark:text-white">
                            {{
                              selectedResult?.species.family.scientificNameWithoutAuthor
                            }}
                          </p>
                        </div>
                        <div>
                          <p
                            class="text-xs font-semibold tracking-wide uppercase text-sage-500 dark:text-sage-400"
                          >
                            Genus
                          </p>
                          <p class="mt-1 font-medium text-sage-900 dark:text-white">
                            {{
                              selectedResult?.species.genus.scientificNameWithoutAuthor
                            }}
                          </p>
                        </div>
                      </div>
                      <!-- External Links -->
                      <div class="flex flex-wrap gap-2">
                        <a
                          v-if="selectedResult?.gbif?.id"
                          :href="`https://www.gbif.org/species/${selectedResult.gbif.id}`"
                          target="_blank"
                          rel="noopener noreferrer"
                          class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full shadow text-sage-800 bg-sage-100 hover:bg-sage-200 dark:bg-sage-700 dark:text-sage-200 dark:hover:bg-sage-600"
                        >
                          <Icon name="external-link" class="w-3 h-3 mr-1" /> GBIF Database
                        </a>
                        <a
                          v-if="selectedResult?.powo?.id"
                          :href="`http://powo.science.kew.org/taxon/urn:lsid:ipni.org:names:${selectedResult.powo.id}`"
                          target="_blank"
                          rel="noopener noreferrer"
                          class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full shadow text-sage-800 bg-sage-100 hover:bg-sage-200 dark:bg-sage-700 dark:text-sage-200 dark:hover:bg-sage-600"
                        >
                          <Icon name="external-link" class="w-3 h-3 mr-1" /> Kew Science
                        </a>
                      </div>
                      <!-- Conservation Guide Section -->
                      <div
                        class="p-6 mt-4 shadow rounded-2xl bg-gradient-to-br from-green-100/80 to-green-200/60 dark:from-green-900/30 dark:to-green-800/30"
                      >
                        <h4
                          class="mb-2 font-semibold tracking-tight text-green-700 dark:text-green-300"
                        >
                          Conservation Guide
                        </h4>
                        <p class="text-sm text-sage-800 dark:text-sage-200">
                          {{
                            getConservationAdvice(
                              selectedResult?.species.scientificNameWithoutAuthor || ""
                            )
                          }}
                        </p>
                        <p class="mt-2 text-xs text-sage-500 dark:text-sage-400">
                          This is a general guide. For critical conservation, consult
                          local experts.
                        </p>
                      </div>
                      <!-- Action Buttons -->
                      <div class="flex gap-3 mt-4">
                        <Button
                          variant="outline"
                          class="flex items-center gap-1 transition-all rounded-full shadow-sm hover:bg-green-50 dark:hover:bg-green-900/30"
                        >
                          <Icon name="bookmark" class="w-4 h-4" /> Bookmark
                        </Button>
                        <Button
                          variant="outline"
                          class="flex items-center gap-1 transition-all rounded-full shadow-sm hover:bg-green-50 dark:hover:bg-green-900/30"
                        >
                          <Icon name="map-pin" class="w-4 h-4" /> Report Sighting
                        </Button>
                        <Button
                          variant="outline"
                          class="flex items-center gap-1 transition-all rounded-full shadow-sm hover:bg-green-50 dark:hover:bg-green-900/30"
                        >
                          <Icon name="info" class="w-4 h-4" /> More Info
                        </Button>
                      </div>
                      <!-- User Feedback Section -->
                      <div
                        class="p-6 mt-6 rounded-2xl bg-sage-50/80 dark:bg-sage-800/40 shadow"
                      >
                        <div class="flex items-center gap-3 mb-2">
                          <span
                            class="font-medium text-sage-700 dark:text-sage-200 tracking-tight"
                            >Was this identification accurate?</span
                          >
                          <button
                            class="p-2 transition rounded-full hover:bg-green-100 dark:hover:bg-green-900/30"
                          >
                            <Icon name="thumbs-up" class="w-5 h-5 text-green-600" />
                          </button>
                          <button
                            class="p-2 transition rounded-full hover:bg-red-100 dark:hover:bg-red-900/30"
                          >
                            <Icon name="thumbs-down" class="w-5 h-5 text-red-500" />
                          </button>
                        </div>
                        <textarea
                          rows="2"
                          placeholder="Add a comment or correction..."
                          class="w-full px-3 py-2 text-sm transition border rounded-lg text-sage-800 bg-white/80 border-sage-200 dark:border-sage-700 dark:bg-sage-900/40 dark:text-sage-100 focus:ring-2 focus:ring-green-400"
                        ></textarea>
                        <div class="flex justify-end mt-2">
                          <Button
                            size="sm"
                            class="text-white bg-black rounded-full shadow hover:bg-black/80"
                            >Submit Feedback</Button
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Alternative Results -->
            <div v-if="results?.data?.results && results.data.results.length > 1">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                  Alternative Matches
                </h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                  {{ results.data.results.length - 1 }} other possibilities
                </span>
              </div>

              <div
                class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
              >
                <Card
                  v-for="(result, idx) in results.data.results"
                  :key="idx"
                  :class="[
                    'cursor-pointer overflow-hidden hover:shadow-md transition-shadow bg-white dark:bg-gray-800/60',
                    selectedResultIndex === idx
                      ? 'ring-2 ring-green-500 dark:ring-green-400'
                      : '',
                  ]"
                  @click="selectResult(idx)"
                >
                  <div class="relative">
                    <img
                      v-if="result.images && result.images.length"
                      :src="result.images[0].url.s"
                      class="object-cover w-full h-32 rounded-t-lg"
                      alt="Plant image"
                    />
                    <div
                      v-else
                      class="flex items-center justify-center w-full h-32 bg-gray-100 rounded-t-lg dark:bg-gray-700"
                    >
                      <Icon
                        name="image-off"
                        class="w-8 h-8 text-gray-400 dark:text-gray-500"
                      />
                    </div>
                    <span
                      class="absolute top-2 right-2 rounded-full px-2 py-0.5 text-xs font-medium shadow"
                      :class="
                        (result.score || 0) > 0.5
                          ? 'bg-green-600 text-white'
                          : (result.score || 0) > 0.25
                          ? 'bg-yellow-400 text-yellow-900'
                          : 'bg-red-600 text-white'
                      "
                    >
                      {{ Math.round((result.score || 0) * 100) }}%
                    </span>
                  </div>
                  <div class="p-3">
                    <div class="font-semibold truncate text-sage-900 dark:text-white">
                      {{ result.species.commonNames?.[0] || "Unknown" }}
                    </div>
                    <div class="text-xs italic truncate text-sage-500 dark:text-sage-300">
                      {{ result.species.scientificName }}
                    </div>
                  </div>
                </Card>
              </div>
            </div>
          </template>

          <!-- Empty / Initial State -->
          <Card
            v-else-if="!processing"
            class="overflow-hidden border-0 shadow-xl bg-white/80 dark:bg-gray-800/60 rounded-3xl backdrop-blur-md"
          >
            <CardContent
              class="flex flex-col items-center justify-center py-20 text-center sm:py-32"
            >
              <span class="text-2xl">🔍</span>
              <h3
                class="text-lg font-semibold tracking-tight text-sage-900 dark:text-sage-100"
              >
                Ready to Identify Plants
              </h3>
              <p class="max-w-md mt-2 text-base text-sage-600 dark:text-sage-400">
                Upload a clear image of a plant to get started. For best results, use a
                well-lit photo focusing on distinguishing features like flowers or leaves.
              </p>
              <Button class="mt-8 shadow-lg rounded-xl" size="lg" @click="openFileUpload">
                <Icon name="upload" class="w-4 h-4 mr-2" />
                Upload Plant Image
              </Button>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
