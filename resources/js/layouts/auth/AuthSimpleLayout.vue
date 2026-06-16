<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div
        class="relative flex min-h-svh flex-col items-center justify-center overflow-hidden bg-[#1a120b] p-6 text-[#e8d9b5] md:p-10"
        style="font-family: 'EB Garamond', serif"
    >

        <div
            class="pointer-events-none absolute inset-0 opacity-60"
            style="
                background:
                    radial-gradient(ellipse at top, rgba(196, 138, 47, 0.18), transparent 55%),
                    radial-gradient(ellipse at bottom, rgba(120, 20, 12, 0.22), transparent 60%);
            "
        ></div>

        <div class="relative z-10 w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-3"
                    >
                        <svg
                            viewBox="0 0 100 100"
                            class="h-16 w-16 text-[#d4a843] drop-shadow-[0_0_14px_rgba(212,168,67,0.45)]"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            stroke-linejoin="round"
                        >
                            <polygon points="50,4 92,28 92,72 50,96 8,72 8,28" />
                            <polygon points="50,4 50,38 8,28" />
                            <polygon points="50,4 50,38 92,28" />
                            <polygon points="50,38 8,28 26,66" />
                            <polygon points="50,38 92,28 74,66" />
                            <polygon points="50,38 26,66 74,66" />
                        </svg>
                        <span
                            class="text-xl tracking-[0.3em] text-[#d4a843]"
                            style="font-family: 'Cinzel', serif; font-weight: 900"
                        >
                            mIAou
                        </span>
                        <span class="sr-only">{{ title }}</span>
                    </Link>

                    <div class="space-y-2 text-center">
                        <h1
                            class="text-2xl text-[#f3e6c4]"
                            style="font-family: 'Cinzel', serif; font-weight: 700"
                        >
                            {{ title }}
                        </h1>
                        <p class="text-center text-sm text-[#e8d9b5]/70">
                            {{ description }}
                        </p>
                    </div>
                </div>

                <div
                    class="rounded-lg border border-[#d4a843]/30 bg-[#241810]/70 p-6 shadow-[0_8px_40px_rgba(0,0,0,0.5)] backdrop-blur-sm"
                >
                    <slot />
                </div>
            </div>
        </div>

        <footer
            class="relative z-10 mt-10 text-xs tracking-[0.3em] text-[#e8d9b5]/40 uppercase"
            style="font-family: 'Cinzel', serif"
        >
            mIAou · Forgé à la lueur des bougies
        </footer>
    </div>
</template>
