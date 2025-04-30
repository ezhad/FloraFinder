<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { onMounted, ref } from 'vue';

interface TreatmentColor {
    name: string;
    color: string;
}

interface Tooth {
    id: number;
    name: string;
    status: string;
    type: 'incisor' | 'canine' | 'premolar' | 'molar';
    jaw: 'upper' | 'lower';
    group: 'primary' | 'secondary';
}

const treatmentColors = ref<TreatmentColor[]>([
    { name: 'Healthy', color: '#FFFFFF' },
    { name: 'Needs Cleaning', color: '#FFD700' },
    { name: 'Cavity', color: '#FF6347' },
    { name: 'Root Canal', color: '#DC143C' },
    { name: 'Crown', color: '#4169E1' },
    { name: 'Bridge', color: '#9932CC' },
    { name: 'Implant', color: '#2E8B57' },
]);

const selectedTreatment = ref<TreatmentColor>(treatmentColors.value[0]);
const selectedGroup = ref<'primary' | 'secondary'>('primary');
const isLoading = ref(true);
const loadingProgress = ref(0);

const teeth = ref<Tooth[]>([
    // Primary Teeth (Deciduous)
    { id: 1, name: 'A', status: 'Healthy', type: 'incisor', jaw: 'upper', group: 'primary' },
    { id: 2, name: 'B', status: 'Healthy', type: 'incisor', jaw: 'upper', group: 'primary' },
    { id: 3, name: 'C', status: 'Healthy', type: 'canine', jaw: 'upper', group: 'primary' },
    { id: 4, name: 'D', status: 'Healthy', type: 'molar', jaw: 'upper', group: 'primary' },
    { id: 5, name: 'E', status: 'Healthy', type: 'molar', jaw: 'upper', group: 'primary' },
    { id: 6, name: 'F', status: 'Healthy', type: 'incisor', jaw: 'lower', group: 'primary' },
    { id: 7, name: 'G', status: 'Healthy', type: 'incisor', jaw: 'lower', group: 'primary' },
    { id: 8, name: 'H', status: 'Healthy', type: 'canine', jaw: 'lower', group: 'primary' },
    { id: 9, name: 'I', status: 'Healthy', type: 'molar', jaw: 'lower', group: 'primary' },
    { id: 10, name: 'J', status: 'Healthy', type: 'molar', jaw: 'lower', group: 'primary' },

    // Secondary Teeth (Permanent)
    { id: 11, name: '1', status: 'Healthy', type: 'incisor', jaw: 'upper', group: 'secondary' },
    { id: 12, name: '2', status: 'Healthy', type: 'incisor', jaw: 'upper', group: 'secondary' },
    { id: 13, name: '3', status: 'Healthy', type: 'canine', jaw: 'upper', group: 'secondary' },
    { id: 14, name: '4', status: 'Healthy', type: 'premolar', jaw: 'upper', group: 'secondary' },
    { id: 15, name: '5', status: 'Healthy', type: 'premolar', jaw: 'upper', group: 'secondary' },
    { id: 16, name: '6', status: 'Healthy', type: 'molar', jaw: 'upper', group: 'secondary' },
    { id: 17, name: '7', status: 'Healthy', type: 'molar', jaw: 'upper', group: 'secondary' },
    { id: 18, name: '8', status: 'Healthy', type: 'incisor', jaw: 'lower', group: 'secondary' },
    { id: 19, name: '9', status: 'Healthy', type: 'incisor', jaw: 'lower', group: 'secondary' },
    { id: 20, name: '10', status: 'Healthy', type: 'canine', jaw: 'lower', group: 'secondary' },
    { id: 21, name: '11', status: 'Healthy', type: 'premolar', jaw: 'lower', group: 'secondary' },
    { id: 22, name: '12', status: 'Healthy', type: 'premolar', jaw: 'lower', group: 'secondary' },
    { id: 23, name: '13', status: 'Healthy', type: 'molar', jaw: 'lower', group: 'secondary' },
    { id: 24, name: '14', status: 'Healthy', type: 'molar', jaw: 'lower', group: 'secondary' },
]);

onMounted(() => {
    simulateLoading();
});

function simulateLoading() {
    const interval = setInterval(() => {
        loadingProgress.value += 10;
        if (loadingProgress.value >= 100) {
            clearInterval(interval);
            isLoading.value = false;
        }
    }, 100);
}

function getToothPath(tooth: Tooth): string {
    const typePositions = {
        incisor: 0,
        canine: 1,
        premolar: 2,
        molar: 3,
    };

    const col = typePositions[tooth.type] + (tooth.group === 'secondary' ? 4 : 0);
    const row = tooth.jaw === 'upper' ? 1 : 3;
    const x = 100 + col * 120;
    const y = tooth.group === 'primary' ? 100 : 300 + (row - 1) * 80;
    const width = 40;
    const height = 60;

    return `
    M ${x} ${y}
    Q ${x + width / 2} ${y - (tooth.jaw === 'upper' ? height : -height)} ${x + width} ${y}
    Q ${x + width / 2} ${y + (tooth.jaw === 'upper' ? height / 3 : -height / 3)} ${x} ${y}
    Z
  `;
}

function handleToothClick(tooth: Tooth) {
    tooth.status = selectedTreatment.value.name;
}

function resetTeeth() {
    teeth.value.forEach((tooth) => {
        tooth.status = 'Healthy';
    });
}
</script>

<template>
    <AppLayout>
        <div class="flex h-[calc(100vh-80px)] flex-col gap-4 bg-gray-50 p-4 dark:bg-gray-900">
            <div class="border-b pb-4 text-center dark:border-gray-700">
                <h1 class="mb-2 text-2xl font-semibold dark:text-gray-100">Dental Chart</h1>
                <p class="text-gray-600 dark:text-gray-400">Primary and Secondary Teeth</p>
            </div>

            <div class="flex gap-4">
                <Button @click="selectedGroup = 'primary'" :variant="selectedGroup === 'primary' ? 'default' : 'outline'"> Primary Teeth </Button>
                <Button @click="selectedGroup = 'secondary'" :variant="selectedGroup === 'secondary' ? 'default' : 'outline'">
                    Secondary Teeth
                </Button>
            </div>

            <div class="flex min-h-[60vh] flex-1 flex-col gap-4 md:flex-row">
                <!-- Control Panel -->
                <div class="order-2 flex w-full flex-col gap-4 rounded-lg bg-white p-4 shadow dark:bg-gray-800 md:order-1 md:w-64">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Treatments</h3>

                    <div class="flex flex-col gap-2">
                        <div
                            v-for="option in treatmentColors"
                            :key="option.name"
                            class="flex cursor-pointer items-center gap-3 rounded p-2 transition-colors"
                            :class="{
                                'bg-gray-100 dark:bg-gray-700': selectedTreatment.name === option.name,
                                'dark:hover:bg-gray-750 hover:bg-gray-50': selectedTreatment.name !== option.name,
                            }"
                            @click="selectedTreatment = option"
                        >
                            <div
                                class="h-5 w-5 rounded-full border border-gray-300 dark:border-gray-600"
                                :style="{ backgroundColor: option.color }"
                            ></div>
                            <span class="dark:text-gray-200">{{ option.name }}</span>
                        </div>
                    </div>

                    <Button class="w-full bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800" @click="resetTeeth">
                        Reset All Teeth
                    </Button>
                </div>

                <!-- Dental Chart -->
                <div class="dark:bg-gray-850 order-1 flex-1 overflow-hidden rounded-lg bg-gray-100 p-4 shadow-inner md:order-2">
                    <div v-if="isLoading" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/90 dark:bg-black/90">
                        <div
                            class="mb-4 h-12 w-12 animate-spin rounded-full border-4 border-gray-200 border-l-blue-500 dark:border-gray-700 dark:border-l-blue-400"
                        ></div>
                        <div class="text-gray-800 dark:text-gray-200">Loading Chart... {{ loadingProgress }}%</div>
                    </div>

                    <svg v-if="!isLoading" viewBox="0 0 1000 600" class="h-full w-full">
                        <!-- Primary Teeth -->
                        <g v-if="selectedGroup === 'primary'">
                            <text x="100" y="50" class="text-lg font-bold">Primary Teeth</text>
                            <text x="100" y="80" class="text-sm">Upper</text>
                            <text x="100" y="280" class="text-sm">Lower</text>

                            <!-- Upper Primary Teeth -->
                            <path
                                v-for="tooth in teeth.filter((t) => t.group === 'primary' && t.jaw === 'upper')"
                                :key="tooth.id"
                                :d="getToothPath(tooth)"
                                :fill="treatmentColors.find((c) => c.name === tooth.status)?.color || '#fff'"
                                stroke="#333"
                                stroke-width="1"
                                class="cursor-pointer transition-all hover:opacity-75"
                                @click="handleToothClick(tooth)"
                            />

                            <!-- Lower Primary Teeth -->
                            <path
                                v-for="tooth in teeth.filter((t) => t.group === 'primary' && t.jaw === 'lower')"
                                :key="tooth.id"
                                :d="getToothPath(tooth)"
                                :fill="treatmentColors.find((c) => c.name === tooth.status)?.color || '#fff'"
                                stroke="#333"
                                stroke-width="1"
                                class="cursor-pointer transition-all hover:opacity-75"
                                @click="handleToothClick(tooth)"
                            />
                        </g>

                        <!-- Secondary Teeth -->
                        <g v-if="selectedGroup === 'secondary'">
                            <text x="100" y="50" class="text-lg font-bold">Secondary Teeth</text>
                            <text x="100" y="80" class="text-sm">Upper</text>
                            <text x="100" y="380" class="text-sm">Lower</text>

                            <!-- Upper Secondary Teeth -->
                            <path
                                v-for="tooth in teeth.filter((t) => t.group === 'secondary' && t.jaw === 'upper')"
                                :key="tooth.id"
                                :d="getToothPath(tooth)"
                                :fill="treatmentColors.find((c) => c.name === tooth.status)?.color || '#fff'"
                                stroke="#333"
                                stroke-width="1"
                                class="cursor-pointer transition-all hover:opacity-75"
                                @click="handleToothClick(tooth)"
                            />

                            <!-- Lower Secondary Teeth -->
                            <path
                                v-for="tooth in teeth.filter((t) => t.group === 'secondary' && t.jaw === 'lower')"
                                :key="tooth.id"
                                :d="getToothPath(tooth)"
                                :fill="treatmentColors.find((c) => c.name === tooth.status)?.color || '#fff'"
                                stroke="#333"
                                stroke-width="1"
                                class="cursor-pointer transition-all hover:opacity-75"
                                @click="handleToothClick(tooth)"
                            />
                        </g>

                        <!-- Labels -->
                        <text x="50" y="120" class="text-sm">Incisors</text>
                        <text x="50" y="170" class="text-sm">Canines</text>
                        <text x="50" y="220" class="text-sm">Premolars</text>
                        <text x="50" y="270" class="text-sm">Molars</text>
                    </svg>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
