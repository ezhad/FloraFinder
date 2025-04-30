<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { onMounted, ref } from 'vue';

// Define types for Teachable Machine objects
interface TeachableMachineImage {
    load: (modelURL: string, metadataURL: string) => Promise<TeachableMachineModel>;
    Webcam: new (width: number, height: number, flip: boolean) => TeachableMachineWebcam;
}

interface TeachableMachineModel {
    getTotalClasses: () => number;
    predict: (canvas: HTMLCanvasElement | HTMLImageElement) => Promise<Array<{ className: string; probability: number }>>;
}

interface TeachableMachineWebcam {
    canvas: HTMLCanvasElement;
    setup: () => Promise<void>;
    play: () => Promise<void>;
    update: () => void;
}

// Variables with proper typing
const model = ref<TeachableMachineModel | null>(null);
const webcam = ref<TeachableMachineWebcam | null>(null);
const labelContainer = ref<HTMLElement | null>(null);
const maxPredictions = ref<number>(0);
const isModelLoaded = ref<boolean>(false);
const isWebcamActive = ref<boolean>(false);
const uploadedImage = ref<HTMLImageElement | null>(null);
const loadingLibrary = ref<boolean>(false);

// The model URL
const URL = 'https://teachablemachine.withgoogle.com/models/ChxSDnzGL/';

// Load Teachable Machine library
const loadTeachableMachineLib = () => {
    return new Promise((resolve, reject) => {
        if ((window as any).tmImage) {
            resolve(true);
            return;
        }

        loadingLibrary.value = true;
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/@teachablemachine/image@0.8/dist/teachablemachine-image.min.js';
        script.onload = () => {
            loadingLibrary.value = false;
            resolve(true);
        };
        script.onerror = (err) => {
            loadingLibrary.value = false;
            reject(err);
        };
        document.head.appendChild(script);
    });
};

// Load the model
async function loadModel() {
    try {
        // Load the Teachable Machine library first
        await loadTeachableMachineLib();

        const modelURL = URL + 'model.json';
        const metadataURL = URL + 'metadata.json';

        // Access the teachable machine libraries
        const tmImage = (window as any).tmImage as TeachableMachineImage;
        if (!tmImage) {
            throw new Error('Teachable Machine library not loaded correctly');
        }

        // Load the model and metadata
        model.value = await tmImage.load(modelURL, metadataURL);
        maxPredictions.value = model.value.getTotalClasses();

        // Create label container divs
        labelContainer.value = document.getElementById('label-container');
        if (labelContainer.value) {
            labelContainer.value.innerHTML = ''; // Clear existing content
            for (let i = 0; i < maxPredictions.value; i++) {
                labelContainer.value.appendChild(document.createElement('div'));
            }
        }

        isModelLoaded.value = true;
        return true;
    } catch (error) {
        console.error('Error loading model:', error);
        return false;
    }
}

// Initialize webcam
async function startWebcam() {
    if (!isModelLoaded.value) {
        const modelLoaded = await loadModel();
        if (!modelLoaded) return;
    }

    try {
        // Stop any existing webcam
        if (isWebcamActive.value && webcam.value) {
            // There's no built-in stop method, but we can remove the canvas
            const webcamContainer = document.getElementById('webcam-container');
            if (webcamContainer && webcam.value.canvas) {
                webcamContainer.removeChild(webcam.value.canvas);
            }
        }

        // Access the teachable machine libraries
        const tmImage = (window as any).tmImage as TeachableMachineImage;

        // Setup webcam
        const flip = true; // whether to flip the webcam
        webcam.value = new tmImage.Webcam(400, 400, flip);
        await webcam.value.setup();
        await webcam.value.play();

        // Append elements to the DOM
        const webcamContainer = document.getElementById('webcam-container');
        if (webcamContainer && webcam.value) {
            // Clear container first
            webcamContainer.innerHTML = '';
            webcamContainer.appendChild(webcam.value.canvas);
        }

        isWebcamActive.value = true;
        uploadedImage.value = null;
        window.requestAnimationFrame(loop);
    } catch (error) {
        console.error('Error initializing webcam:', error);
    }
}

// Handle file upload
function handleFileUpload(event: Event) {
    const input = event.target as HTMLInputElement;
    if (!input.files || input.files.length === 0) return;

    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = async (e) => {
        if (!e.target?.result) return;

        // Stop webcam if it's running
        if (isWebcamActive.value) {
            isWebcamActive.value = false;
        }

        // Load model if not already loaded
        if (!isModelLoaded.value) {
            const modelLoaded = await loadModel();
            if (!modelLoaded) return;
        }

        // Create and display the uploaded image
        const img = new Image();
        img.onload = () => {
            uploadedImage.value = img;

            const webcamContainer = document.getElementById('webcam-container');
            if (webcamContainer) {
                webcamContainer.innerHTML = ''; // Clear existing content
                webcamContainer.appendChild(img);

                // Style the image
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                img.style.borderRadius = '0.5rem';

                // Run prediction on the image
                predict(img);
            }
        };
        img.src = e.target.result as string;
    };

    reader.readAsDataURL(file);
}

// Handle drag and drop
function handleDragOver(event: DragEvent) {
    event.preventDefault();
    if (event.dataTransfer) {
        event.dataTransfer.dropEffect = 'copy';
    }
}

function handleDrop(event: DragEvent) {
    event.preventDefault();
    if (event.dataTransfer?.files && event.dataTransfer.files.length > 0) {
        const input = document.getElementById('file-upload') as HTMLInputElement;
        input.files = event.dataTransfer.files;
        handleFileUpload({ target: input } as unknown as Event);
    }
}

async function loop() {
    if (webcam.value && isWebcamActive.value) {
        webcam.value.update();
        await predict(webcam.value.canvas);
        window.requestAnimationFrame(loop);
    }
}

// Run the image through the model
async function predict(imageElement: HTMLCanvasElement | HTMLImageElement) {
    if (!model.value || !labelContainer.value) return;

    try {
        // Predict can take in an image, video or canvas html element
        const prediction = await model.value.predict(imageElement);

        for (let i = 0; i < maxPredictions.value; i++) {
            const classPrediction = prediction[i].className + ': ' + (prediction[i].probability * 100).toFixed(2) + '%';

            if (labelContainer.value.childNodes[i]) {
                const element = labelContainer.value.childNodes[i] as HTMLElement;
                element.innerHTML = classPrediction;

                // Highlight the most likely class
                if (prediction[i].probability > 0.7) {
                    element.className = 'high-probability';
                } else if (prediction[i].probability > 0.3) {
                    element.className = 'medium-probability';
                } else {
                    element.className = '';
                }
            }
        }
    } catch (error) {
        console.error('Error making prediction:', error);
    }
}

// Use onMounted to initialize
onMounted(() => {
    loadModel();
});
</script>

<template>
    <AppLayout title="Teachable Machine Model">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-xl dark:bg-gray-800 sm:rounded-lg">
                    <h1 class="mb-4 text-2xl font-bold">Teachable Machine Image Model</h1>

                    <div class="mb-6 flex flex-wrap gap-4">
                        <button
                            type="button"
                            @click="startWebcam"
                            class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-blue-700 focus:border-blue-900 focus:outline-none focus:ring focus:ring-blue-300 active:bg-blue-900 disabled:opacity-25"
                            :disabled="loadingLibrary"
                        >
                            <span v-if="loadingLibrary">Loading...</span>
                            <span v-else>Start Camera</span>
                        </button>

                        <div class="relative">
                            <input
                                type="file"
                                id="file-upload"
                                accept="image/*"
                                @change="handleFileUpload"
                                class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                            />
                            <button
                                type="button"
                                class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-green-700 focus:border-green-900 focus:outline-none focus:ring focus:ring-green-300 active:bg-green-900 disabled:opacity-25"
                                :disabled="loadingLibrary"
                            >
                                <span v-if="loadingLibrary">Loading...</span>
                                <span v-else>Upload Image</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col gap-6 md:flex-row">
                        <div
                            id="webcam-container"
                            class="flex min-h-[400px] w-full items-center justify-center rounded-lg border-2 border-dashed border-gray-300 p-4 dark:border-gray-600 md:w-1/2"
                            @dragover="handleDragOver"
                            @drop="handleDrop"
                        >
                            <div v-if="!isWebcamActive && !uploadedImage" class="text-center">
                                <p class="mb-2 text-gray-500 dark:text-gray-400">Camera will appear here, or</p>
                                <p class="text-gray-500 dark:text-gray-400">Drag and drop image here</p>
                            </div>
                        </div>

                        <div class="w-full md:w-1/2">
                            <h2 class="mb-3 text-xl font-semibold">Predictions</h2>
                            <div id="label-container" class="min-h-[200px] rounded-lg bg-gray-100 p-4 dark:bg-gray-700">
                                <p v-if="!isModelLoaded" class="text-gray-500 dark:text-gray-400">Model is loading or not loaded yet...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
#webcam-container canvas {
    width: 100%;
    height: auto;
    border-radius: 0.5rem;
}

#webcam-container {
    position: relative;
    transition: all 0.2s;
}

#webcam-container:hover {
    border-color: #4f46e5;
}

#label-container div {
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(255, 255, 255, 0.1);
}

#label-container .high-probability {
    background-color: rgba(52, 211, 153, 0.2);
    border-left: 4px solid #34d399;
    font-weight: bold;
}

#label-container .medium-probability {
    background-color: rgba(251, 191, 36, 0.2);
    border-left: 4px solid #fbbf24;
}

input[type='file'] {
    cursor: pointer;
}
</style>
