<template>
    <div class="flex items-center gap-3 text-[#e8d9b5]/80">
        <svg
            class="quill"
            viewBox="0 0 100 100"
            width="30"
            height="30"
            fill="none"
            stroke="#f0c850"
            stroke-width="5"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <!-- Tige de la plume -->
            <path d="M82 18 C 55 28, 34 48, 22 78" />
            <!-- Barbes de la plume -->
            <path d="M82 18 C 72 30, 60 33, 50 32" />
            <path d="M70 30 C 62 41, 51 45, 41 45" />
            <path d="M58 42 C 50 52, 40 56, 31 57" />
            <!-- Pointe -->
            <path d="M22 78 L 14 86" />
        </svg>
        <span class="text-sm" style="font-family: 'Cinzel', serif">
            Le Maître du Jeu rédige sa réponse<span class="dots"></span>
        </span>
    </div>
</template>

<style scoped>
.quill {
    transform-origin: 80% 20%;
    animation: quillwrite 0.9s ease-in-out infinite;
    filter: drop-shadow(0 0 6px rgba(240, 200, 80, 0.5));
}

/* Mouvement de va-et-vient comme une plume qui trace sur le parchemin */
@keyframes quillwrite {
    0% {
        transform: rotate(0deg) translateX(0);
    }
    25% {
        transform: rotate(-8deg) translateX(-1px);
    }
    50% {
        transform: rotate(-3deg) translateX(1.5px);
    }
    75% {
        transform: rotate(-10deg) translateX(-1px);
    }
    100% {
        transform: rotate(0deg) translateX(0);
    }
}

/* Points de suspension animés */
.dots::after {
    content: '';
    animation: dots 1.4s steps(4, end) infinite;
}

@keyframes dots {
    0% {
        content: '';
    }
    25% {
        content: '.';
    }
    50% {
        content: '..';
    }
    75% {
        content: '...';
    }
    100% {
        content: '';
    }
}
</style>
