<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { edit } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Atours',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Atours" />

    <h1 class="sr-only">Atours</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            title="Atours"
            description="L'apparence de ta taverne"
        />

        <div class="flex items-center gap-3 rounded-lg border border-[#d4a843]/30 bg-[#241810]/60 p-4">
            <span class="text-2xl">🕯️</span>
            <div>
                <p class="font-medium text-[#e8d9b5]">Thème de la taverne</p>
                <p class="text-sm text-muted-foreground">
                    mIAou se vit à la lueur des bougies : le thème sombre est le seul disponible.
                </p>
            </div>
        </div>
    </div>
</template>
