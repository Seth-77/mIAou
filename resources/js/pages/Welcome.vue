<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
</script>

<template>
    <Head title="mIAou — Ton Maître du Jeu">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div
        class="relative flex min-h-screen flex-col items-center overflow-hidden bg-[#1a120b] px-6 py-10 text-[#e8d9b5] lg:justify-center"
        style="font-family: 'EB Garamond', serif"
    >
        <!-- Texture / vignette -->
        <div
            class="pointer-events-none absolute inset-0 opacity-60"
            style="
                background:
                    radial-gradient(ellipse at top, rgba(196, 138, 47, 0.18), transparent 55%),
                    radial-gradient(ellipse at bottom, rgba(120, 20, 12, 0.22), transparent 60%);
            "
        ></div>
        <div
            class="pointer-events-none absolute inset-0 opacity-[0.04]"
            style="
                background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2240%22 height=%2240%22><path d=%22M0 0h40v40H0z%22 fill=%22none%22/><circle cx=%2220%22 cy=%2220%22 r=%221%22 fill=%22%23ffffff%22/></svg>');
            "
        ></div>

        <!-- Barre de navigation -->
        <header class="relative z-10 mb-10 w-full max-w-5xl">
            <nav class="flex items-center justify-between">
                <span
                    class="text-2xl tracking-[0.3em] text-[#d4a843]"
                    style="font-family: 'Cinzel', serif; font-weight: 900"
                >
                    mIAou
                </span>


            </nav>
        </header>
        <main class="relative z-10 flex w-full max-w-3xl flex-col items-center text-center">

            <div class="mb-8 flex items-center justify-center">
                <svg
                    viewBox="0 0 100 100"
                    class="h-24 w-24 text-[#d4a843] drop-shadow-[0_0_18px_rgba(212,168,67,0.45)]"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linejoin="round"
                >
                    <polygon points="50,4 92,28 92,72 50,96 8,72 8,28" />
                    <polygon points="50,4 50,38 8,28" />
                    <polygon points="50,4 50,38 92,28" />
                    <polygon points="50,38 8,28 26,66" />
                    <polygon points="50,38 92,28 74,66" />
                    <polygon points="50,38 26,66 74,66" />
                    <polygon points="50,96 26,66 50,38" opacity="0.5" />
                    <polygon points="50,96 74,66 50,38" opacity="0.5" />
                    <text
                        x="50"
                        y="60"
                        text-anchor="middle"
                        font-size="22"
                        fill="currentColor"
                        stroke="none"
                        style="font-family: 'Cinzel', serif; font-weight: 900"
                    >
                        20
                    </text>
                </svg>
            </div>

            <p
                class="mb-3 text-sm tracking-[0.4em] text-[#c0703a] uppercase"
                style="font-family: 'Cinzel', serif"
            >
                ⚔ Une aventure t'attend ⚔
            </p>

            <h1
                class="mb-6 text-5xl leading-tight text-[#f3e6c4] lg:text-6xl"
                style="font-family: 'Cinzel', serif; font-weight: 900; text-shadow: 0 2px 12px rgba(0,0,0,0.6)"
            >
                Convoque ton Maître du Jeu
            </h1>

            <p class="mb-10 max-w-xl text-lg leading-relaxed text-[#e8d9b5]/80">
                Bienvenue dans la taverne de <span class="text-[#d4a843]">mIAou</span>. Pose tes
                questions, lance les dés de l'imagination et laisse ton conteur intelligent tisser
                des récits, répondre à tes énigmes et t'accompagner dans chaque quête.
            </p>

            <!-- Boutons d'action -->
            <div class="flex flex-col gap-4 sm:flex-row">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="group relative overflow-hidden rounded-sm border border-[#d4a843] bg-gradient-to-b from-[#b8841f] to-[#8a5e12] px-10 py-3 text-base tracking-widest text-[#1a120b] uppercase shadow-[0_4px_20px_rgba(212,168,67,0.3)] transition hover:from-[#d4a843] hover:to-[#a06d18]"
                    style="font-family: 'Cinzel', serif; font-weight: 700"
                >
                    Reprendre l'aventure
                </Link>
                <template v-else>
                    <Link
                        :href="register()"
                        class="rounded-sm border border-[#d4a843] bg-gradient-to-b from-[#b8841f] to-[#8a5e12] px-10 py-3 text-base tracking-widest text-[#1a120b] uppercase shadow-[0_4px_20px_rgba(212,168,67,0.3)] transition hover:from-[#d4a843] hover:to-[#a06d18]"
                        style="font-family: 'Cinzel', serif; font-weight: 700"
                    >
                        Entrer dans la taverne
                    </Link>
                    <Link
                        :href="login()"
                        class="rounded-sm border border-[#e8d9b5]/30 px-10 py-3 text-base tracking-widest text-[#e8d9b5] uppercase transition hover:border-[#e8d9b5]/70 hover:bg-[#e8d9b5]/5"
                        style="font-family: 'Cinzel', serif; font-weight: 700"
                    >
                        Reprendre la quête
                    </Link>
                </template>
            </div>

            <!-- Trois "sorts" / features -->
            <div class="mt-20 grid w-full grid-cols-1 gap-6 sm:grid-cols-3">
                <div
                    class="rounded border border-[#d4a843]/25 bg-[#241810]/60 p-6 backdrop-blur-sm transition hover:border-[#d4a843]/60"
                >
                    <div class="mb-3 text-3xl">📜</div>
                    <h3
                        class="mb-2 text-lg text-[#d4a843]"
                        style="font-family: 'Cinzel', serif; font-weight: 700"
                    >
                        Parchemins
                    </h3>
                    <p class="text-sm text-[#e8d9b5]/70">
                        Retrouve toutes tes conversations comme autant de chroniques consignées dans
                        ton grimoire.
                    </p>
                </div>

                <div
                    class="rounded border border-[#d4a843]/25 bg-[#241810]/60 p-6 backdrop-blur-sm transition hover:border-[#d4a843]/60"
                >
                    <div class="mb-3 text-3xl">🔮</div>
                    <h3
                        class="mb-2 text-lg text-[#d4a843]"
                        style="font-family: 'Cinzel', serif; font-weight: 700"
                    >
                        Sortilèges
                    </h3>
                    <p class="text-sm text-[#e8d9b5]/70">
                        Façonne ton conteur avec des instructions personnalisées et choisis le modèle
                        qui t'accompagne.
                    </p>
                </div>

                <div
                    class="rounded border border-[#d4a843]/25 bg-[#241810]/60 p-6 backdrop-blur-sm transition hover:border-[#d4a843]/60"
                >
                    <div class="mb-3 text-3xl">🐉</div>
                    <h3
                        class="mb-2 text-lg text-[#d4a843]"
                        style="font-family: 'Cinzel', serif; font-weight: 700"
                    >
                        Compagnon
                    </h3>
                    <p class="text-sm text-[#e8d9b5]/70">
                        Un Maître du Jeu infatigable, prêt à répondre à la moindre de tes requêtes,
                        de jour comme de nuit.
                    </p>
                </div>
            </div>
        </main>

        <footer
            class="relative z-10 mt-16 text-xs tracking-[0.3em] text-[#e8d9b5]/40 uppercase"
            style="font-family: 'Cinzel', serif"
        >
            mIAou · Forgé à la lueur des bougies
        </footer>
    </div>
</template>
