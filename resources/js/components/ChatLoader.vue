<template>
    <div class="flex items-center gap-3 text-neutral-400">
        <svg class="spinner" viewBox="0 0 50 50" width="32" height="32">
            <circle
                class="ring"
                cx="25"
                cy="25"
                r="20"
                fill="none"
                stroke-width="4"
            />
            <ellipse cx="25" cy="25" rx="6" ry="11" fill="#4ade80" opacity="0.9" />
        </svg>
        <span class="text-sm">L'assistant réfléchit...</span>
    </div>
</template>

<style scoped>
.spinner {
    animation: spin 1s linear infinite;
}

.ring {
    stroke: #4ade80;
    stroke-linecap: round;
    stroke-dasharray: 90 150;
    opacity: 0.5;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>