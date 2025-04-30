<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

// Breadcrumbs setup
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '/',
    },
    {
        title: 'Appointment Booking',
        href: '/booking',
    },
];

// Define service types
const services = ref([
    { id: 1, name: 'Dental Cleaning', duration: 60, price: 120, description: 'Regular dental cleaning and check-up' },
    { id: 2, name: 'Teeth Whitening', duration: 90, price: 250, description: 'Professional teeth whitening treatment' },
    { id: 3, name: 'Dental Examination', duration: 45, price: 100, description: 'Comprehensive dental examination' },
    { id: 4, name: 'Root Canal', duration: 120, price: 800, description: 'Root canal treatment' },
    { id: 5, name: 'Tooth Extraction', duration: 60, price: 180, description: 'Simple tooth extraction' },
    { id: 6, name: 'Dental Filling', duration: 45, price: 150, description: 'Dental filling treatment' },
]);

// Define dentists
const dentists = ref([
    {
        id: 1,
        name: 'Dr. Martinez',
        specialty: 'General Dentistry',
        avatar: 'https://randomuser.me/api/portraits/men/54.jpg',
        available: true,
    },
    {
        id: 2,
        name: 'Dr. Johnson',
        specialty: 'Orthodontics',
        avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
        available: true,
    },
    {
        id: 3,
        name: 'Dr. Wilson',
        specialty: 'Endodontics',
        avatar: 'https://randomuser.me/api/portraits/men/1.jpg',
        available: true,
    },
    {
        id: 4,
        name: 'Dr. Garcia',
        specialty: 'Periodontics',
        avatar: 'https://randomuser.me/api/portraits/men/69.jpg',
        available: true,
    },
]);

// Booking form state
const form = reactive({
    service: null as number | null,
    dentist: null as number | null,
    date: '',
    time: '',
    notes: '',
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    isNewPatient: true,
});

// Generate available dates (next 14 days)
const availableDates = computed(() => {
    const dates = [];
    const today = new Date();

    for (let i = 1; i <= 14; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);

        // Skip Sundays (assuming clinic is closed)
        if (date.getDay() !== 0) {
            dates.push({
                value: date.toISOString().split('T')[0],
                display: date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' }),
            });
        }
    }

    return dates;
});

// Generate available times based on selected date
const availableTimes = computed(() => {
    // Sample time slots - in real-world, these would be fetched based on dentist availability
    return ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];
});

// Selected service and dentist for display
const selectedService = computed(() => services.value.find((s) => s.id === form.service) || null);

const selectedDentist = computed(() => dentists.value.find((d) => d.id === form.dentist) || null);

// Current step in booking process
const currentStep = ref(1);
const totalSteps = 3;

// Methods
const nextStep = () => {
    if (currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const submitBooking = () => {
    // In a real application, you would send this data to your backend
    alert('Booking submitted! You will receive a confirmation email shortly.');
    // Reset form or redirect as needed
};

// Progress percentage for progress bar
const progressPercentage = computed(() => {
    return ((currentStep.value - 1) / (totalSteps - 1)) * 100;
});
</script>

<template>
    <Head title="Book an Appointment" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Book Your Dental Appointment</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Schedule your visit to our dental clinic in just a few easy steps</p>
            </div>

            <!-- Progress bar -->
            <div class="mb-8">
                <div class="relative mb-2">
                    <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700"></div>
                    <div
                        class="absolute top-0 h-2 rounded-full bg-indigo-600 transition-all duration-300"
                        :style="{ width: `${progressPercentage}%` }"
                    ></div>
                </div>
                <div class="flex justify-between">
                    <div class="text-xs font-medium" :class="currentStep >= 1 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500'">
                        Service & Dentist
                    </div>
                    <div class="text-xs font-medium" :class="currentStep >= 2 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500'">
                        Date & Time
                    </div>
                    <div class="text-xs font-medium" :class="currentStep >= 3 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500'">
                        Your Information
                    </div>
                </div>
            </div>

            <!-- Form container -->
            <div
                class="dark:bg-sidebar-background overflow-hidden rounded-2xl border border-sidebar-border/70 bg-white shadow-sm dark:border-sidebar-border"
            >
                <!-- Step 1: Choose service and dentist -->
                <div v-if="currentStep === 1" class="p-6">
                    <h2 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Select Service & Dentist</h2>

                    <!-- Services -->
                    <div class="mb-8">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"> Choose a Service: </label>
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div
                                v-for="service in services"
                                :key="service.id"
                                @click="form.service = service.id"
                                :class="[
                                    'cursor-pointer rounded-lg border p-4 transition-all',
                                    form.service === service.id
                                        ? 'border-indigo-600 bg-indigo-50 dark:border-indigo-400 dark:bg-indigo-900/30'
                                        : 'border-gray-200 hover:border-indigo-300 dark:border-gray-700 dark:hover:border-indigo-700',
                                ]"
                            >
                                <div class="flex justify-between">
                                    <h3 class="font-medium text-gray-800 dark:text-white">{{ service.name }}</h3>
                                    <span class="text-indigo-600 dark:text-indigo-400">${{ service.price }}</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ service.description }}</p>
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Duration: {{ service.duration }} mins</div>
                            </div>
                        </div>
                        <p v-if="!form.service" class="mt-2 text-xs text-red-600 dark:text-red-400">Please select a service to continue</p>
                    </div>

                    <!-- Dentists -->
                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"> Choose a Dentist: </label>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div
                                v-for="dentist in dentists"
                                :key="dentist.id"
                                @click="form.dentist = dentist.id"
                                :class="[
                                    'cursor-pointer rounded-lg border p-4 transition-all',
                                    form.dentist === dentist.id
                                        ? 'border-indigo-600 bg-indigo-50 dark:border-indigo-400 dark:bg-indigo-900/30'
                                        : 'border-gray-200 hover:border-indigo-300 dark:border-gray-700 dark:hover:border-indigo-700',
                                ]"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                        <img
                                            :src="dentist.avatar"
                                            alt=""
                                            class="h-full w-full object-cover"
                                            onError="this.src='/images/avatars/placeholder.jpg'"
                                        />
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-800 dark:text-white">{{ dentist.name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ dentist.specialty }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-if="!form.dentist" class="mt-2 text-xs text-red-600 dark:text-red-400">Please select a dentist to continue</p>
                    </div>
                </div>

                <!-- Step 2: Choose date and time -->
                <div v-if="currentStep === 2" class="p-6">
                    <h2 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Select Date & Time</h2>

                    <!-- Date selection -->
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"> Select a Date: </label>
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <div
                                v-for="date in availableDates"
                                :key="date.value"
                                @click="form.date = date.value"
                                :class="[
                                    'cursor-pointer rounded-lg border p-3 text-center transition-all',
                                    form.date === date.value
                                        ? 'border-indigo-600 bg-indigo-50 dark:border-indigo-400 dark:bg-indigo-900/30'
                                        : 'border-gray-200 hover:border-indigo-300 dark:border-gray-700 dark:hover:border-indigo-700',
                                ]"
                            >
                                {{ date.display }}
                            </div>
                        </div>
                        <p v-if="!form.date" class="mt-2 text-xs text-red-600 dark:text-red-400">Please select a date to continue</p>
                    </div>

                    <!-- Time selection -->
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"> Select a Time: </label>
                        <div class="grid gap-3 sm:grid-cols-4">
                            <button
                                v-for="time in availableTimes"
                                :key="time"
                                @click="form.time = time"
                                type="button"
                                :class="[
                                    'rounded-md border px-4 py-2 text-center text-sm transition-all',
                                    form.time === time
                                        ? 'border-indigo-600 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-900/30 dark:text-indigo-300'
                                        : 'border-gray-200 text-gray-700 hover:border-indigo-300 dark:border-gray-700 dark:text-gray-300 dark:hover:border-indigo-700',
                                ]"
                            >
                                {{ time }}
                            </button>
                        </div>
                        <p v-if="!form.time" class="mt-2 text-xs text-red-600 dark:text-red-400">Please select a time to continue</p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Additional Information (Optional):
                        </label>
                        <textarea
                            id="notes"
                            v-model="form.notes"
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            placeholder="Tell us about any specific concerns or requests..."
                        ></textarea>
                    </div>
                </div>

                <!-- Step 3: Contact information -->
                <div v-if="currentStep === 3" class="p-6">
                    <h2 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Your Information</h2>

                    <div class="mb-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="firstName" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> First Name * </label>
                            <input
                                id="firstName"
                                v-model="form.firstName"
                                type="text"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="Enter your first name"
                                required
                            />
                        </div>
                        <div>
                            <label for="lastName" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Last Name * </label>
                            <input
                                id="lastName"
                                v-model="form.lastName"
                                type="text"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="Enter your last name"
                                required
                            />
                        </div>
                    </div>

                    <div class="mb-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Email Address * </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="your.email@example.com"
                                required
                            />
                        </div>
                        <div>
                            <label for="phone" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Phone Number * </label>
                            <input
                                id="phone"
                                v-model="form.phone"
                                type="tel"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="(123) 456-7890"
                                required
                            />
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex items-center">
                            <input
                                id="isNewPatient"
                                v-model="form.isNewPatient"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800"
                            />
                            <label for="isNewPatient" class="ml-2 text-sm text-gray-700 dark:text-gray-300"> I am a new patient </label>
                        </div>
                    </div>

                    <!-- Booking Summary -->
                    <div class="mb-6 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                        <h3 class="mb-3 text-base font-medium text-gray-800 dark:text-white">Appointment Summary</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Service:</span>
                                <span class="font-medium text-gray-800 dark:text-white">{{ selectedService?.name || 'Not selected' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Dentist:</span>
                                <span class="font-medium text-gray-800 dark:text-white">{{ selectedDentist?.name || 'Not selected' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Date:</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{
                                        form.date
                                            ? new Date(form.date).toLocaleDateString('en-US', {
                                                  weekday: 'long',
                                                  month: 'long',
                                                  day: 'numeric',
                                              })
                                            : 'Not selected'
                                    }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Time:</span>
                                <span class="font-medium text-gray-800 dark:text-white">{{ form.time || 'Not selected' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Price:</span>
                                <span class="font-medium text-gray-800 dark:text-white">${{ selectedService?.price || '0' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Navigation -->
                <div class="border-t border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                    <div class="flex justify-between">
                        <button
                            v-if="currentStep > 1"
                            @click="prevStep"
                            type="button"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                        >
                            Previous
                        </button>
                        <div></div>
                        <!-- Spacer -->
                        <button
                            v-if="currentStep < totalSteps"
                            @click="nextStep"
                            type="button"
                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-indigo-500 dark:hover:bg-indigo-600"
                            :disabled="(currentStep === 1 && (!form.service || !form.dentist)) || (currentStep === 2 && (!form.date || !form.time))"
                        >
                            Next Step
                        </button>
                        <button
                            v-else
                            @click="submitBooking"
                            type="button"
                            class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-500 dark:hover:bg-green-600"
                            :disabled="!form.firstName || !form.lastName || !form.email || !form.phone"
                        >
                            Confirm Booking
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
