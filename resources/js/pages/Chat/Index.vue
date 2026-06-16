<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import MarkdownRenderer from '@/components/MarkdownRenderer.vue'
import ChatLoader from '@/components/ChatLoader.vue'
import { ref } from 'vue'

const props = defineProps({
    conversations: Array,
    currentConversation: Object,
    messages: Array,
    models: Array,
})

const form = useForm({
    content: '',
})

const modelForm = useForm({
    model: '',
})

const sendMessage = () => {
    if (!form.content.trim()) return

    form.post(`/chat/${props.currentConversation.id}/messages`, {
        preserveScroll: true,
        onSuccess: () => form.reset('content'),
    })
}

const changeModel = (event) => {
    modelForm.model = event.target.value
    modelForm.patch(`/chat/${props.currentConversation.id}/model`, {
        preserveScroll: true,
        preserveState: true,
    })
}

const deleteConversation = (id) => {
    if (confirm('Es-tu sûr de vouloir effacer cette chronique ?')) {
        router.delete(`/chat/${id}`, {
            preserveScroll: true,
        })
    }
}

const sidebarOpen = ref(true)
</script>

<template>
    <Head title="Taverne" />

    <div
        class="flex h-[calc(100vh)] overflow-hidden bg-[#1a120b] text-[#e8d9b5]"
        style="font-family: 'EB Garamond', serif"
    >
        <!-- Sidebar : liste des chroniques -->
        <aside
            class="flex flex-col border-r border-[#d4a843]/25 bg-[#241810] transition-all duration-300"
            :class="sidebarOpen ? 'w-64' : 'w-0 overflow-hidden'"
        >
            <div class="border-b border-[#d4a843]/25 p-4">
                <Link
                    href="/chat"
                    method="post"
                    as="button"
                    class="block w-full cursor-pointer rounded-md border border-[#d4a843] bg-gradient-to-b from-[#b8841f] to-[#8a5e12] px-3 py-2 text-center text-sm font-bold tracking-wide text-[#1a120b] uppercase transition hover:from-[#d4a843] hover:to-[#a06d18]"
                    style="font-family: 'Cinzel', serif"
                >
                    + Nouvelle quête
                </Link>
            </div>

            <nav class="flex-1 overflow-y-auto p-2">
                <div
                    v-for="conv in props.conversations"
                    :key="conv.id"
                    class="group mb-1 flex items-center gap-1 rounded-md pr-1 transition hover:bg-[#3a2817]"
                    :class="{ 'bg-[#3a2817]': props.currentConversation?.id === conv.id }"
                >
                    <Link
                        :href="`/chat/${conv.id}`"
                        class="flex-1 truncate px-3 py-2 text-sm"
                    >
                        {{ conv.title ?? 'Chronique sans nom' }}
                    </Link>

                    <button
                        @click="deleteConversation(conv.id)"
                        class="shrink-0 cursor-pointer rounded p-1 text-[#e8d9b5]/40 opacity-0 transition hover:text-[#d9603a] group-hover:opacity-100"
                        title="Effacer"
                    >
                        🗑
                    </button>
                </div>

                <p
                    v-if="props.conversations.length === 0"
                    class="px-3 py-2 text-sm text-[#e8d9b5]/40"
                >
                    Aucune chronique consignée
                </p>
            </nav>

            <!-- Pied de sidebar : grimoire + départ -->
            <div class="space-y-1 border-t border-[#d4a843]/25 p-2">
                <Link
                    href="/settings/instructions"
                    class="flex items-center gap-2 rounded-md px-3 py-2 text-sm text-[#e8d9b5]/70 transition hover:bg-[#3a2817] hover:text-[#e8d9b5]"
                >
                    ⚙️ Le Grimoire
                </Link>
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="flex w-full cursor-pointer items-center gap-2 rounded-md px-3 py-2 text-left text-sm text-[#e8d9b5]/70 transition hover:bg-[#3a2817] hover:text-[#d9603a]"
                >
                    🚪 Quitter la taverne
                </Link>
            </div>
        </aside>

        <!-- Zone principale : messages -->
        <main class="flex min-h-0 flex-1 flex-col overflow-hidden">
            <!-- Barre supérieure : repli sidebar + titre + sélecteur de modèle -->
            <div class="flex items-center gap-3 border-b border-[#d4a843]/25 px-4 py-2">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="cursor-pointer rounded p-2 text-[#e8d9b5]/70 transition hover:bg-[#3a2817] hover:text-[#e8d9b5]"
                    title="Replier/déplier le menu"
                >
                    ☰
                </button>

                <h1
                    v-if="props.currentConversation"
                    class="flex-1 truncate font-bold tracking-wide text-[#d4a843]"
                    style="font-family: 'Cinzel', serif"
                >
                    {{ props.currentConversation.title ?? 'Chronique sans nom' }}
                </h1>
                <div v-else class="flex-1"></div>

                <select
                    v-if="props.currentConversation"
                    :value="props.currentConversation.model"
                    @change="changeModel"
                    class="rounded-md border border-[#d4a843]/40 bg-[#241810] p-2 text-xs text-[#e8d9b5]"
                >
                    <option
                        v-for="model in props.models"
                        :key="model.id"
                        :value="model.id"
                    >
                        {{ model.name }}
                    </option>
                </select>
            </div>

            <!-- Aucune conversation sélectionnée -->
            <div
                v-if="!props.currentConversation"
                class="flex flex-1 items-center justify-center px-6 text-center text-[#e8d9b5]/50"
            >
                Choisis une chronique ou lance une nouvelle quête pour commencer ton aventure.
            </div>

            <!-- Conversation active -->
            <template v-else>
                <!-- Fil de discussion : colonne centrée et étroite -->
                <div class="min-h-0 flex-1 overflow-y-auto">
                    <div class="mx-auto w-full max-w-3xl space-y-6 px-4 py-8">
                        <div
                            v-for="message in props.messages"
                            :key="message.id"
                            class="flex"
                            :class="message.role === 'user' ? 'justify-end' : 'justify-start'"
                        >
                            <div
                                class="rounded-2xl px-4 py-3 leading-relaxed"
                                :class="
                                    message.role === 'user'
                                        ? 'max-w-[80%] bg-gradient-to-b from-[#b8841f] to-[#8a5e12] text-[#1a120b]'
                                        : 'max-w-full border border-[#d4a843]/20 bg-[#241810]/70 text-[#e8d9b5]'
                                "
                            >
                                <MarkdownRenderer :content="message.content" />
                            </div>
                        </div>

                        <!-- Loader pendant l'attente de la réponse -->
                        <div v-if="form.processing" class="flex justify-start">
                            <div class="rounded-2xl border border-[#d4a843]/20 bg-[#241810]/70 px-4 py-3">
                                <ChatLoader />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Champ de saisie : boîte centrée avec d20 intégré -->
                <div class="px-4 pb-6">
                    <div class="mx-auto w-full max-w-3xl">
                        <div
                            class="relative flex items-end rounded-2xl border border-[#d4a843]/40 bg-[#241810] shadow-[0_4px_24px_rgba(0,0,0,0.4)] focus-within:border-[#d4a843] transition"
                        >
                            <textarea
                                v-model="form.content"
                                rows="1"
                                placeholder="Adresse ta requête au Maître du Jeu..."
                                class="max-h-48 flex-1 resize-none bg-transparent py-4 pr-16 pl-5 text-sm text-[#e8d9b5] placeholder:text-[#e8d9b5]/40 focus:outline-none"
                                @keydown.enter.exact.prevent="sendMessage"
                            />

                            <!-- Bouton plume -->
                            <button
                                @click="sendMessage"
                                :disabled="form.processing || !form.content.trim()"
                                class="quill-btn absolute right-2.5 bottom-2 grid h-12 w-12 cursor-pointer place-items-center rounded-full transition hover:bg-[#f0c850]/15 disabled:cursor-not-allowed disabled:opacity-30"
                                title="Confier ton message"
                            >
                                <svg
                                    viewBox="0 0 100 100"
                                    class="quill-svg h-8 w-8"
                                    :class="{ 'quill-writing': form.processing }"
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
                                    <!-- Pointe + trait d'écriture -->
                                    <path d="M22 78 L 14 86" />
                                    <path d="M30 84 L 52 84" stroke-width="4" opacity="0.7" />
                                </svg>
                            </button>
                        </div>

                        <p v-if="form.errors.content" class="mt-2 px-2 text-sm text-[#d9603a]">
                            {{ form.errors.content }}
                        </p>
                    </div>
                </div>
            </template>
        </main>
    </div>
</template>

<style scoped>
/* Lueur dorée permanente pour que la plume ressorte sur le fond sombre */
.quill-svg {
    filter: drop-shadow(0 0 5px rgba(240, 200, 80, 0.55));
    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    transform-origin: 80% 20%;
}
/* Au survol : la plume se redresse légèrement, prête à écrire */
.quill-btn:hover:not(:disabled) .quill-svg {
    transform: rotate(-12deg) scale(1.1);
}
/* Pendant l'envoi : petit mouvement de va-et-vient comme si elle écrivait */
.quill-writing {
    animation: quillwrite 0.9s ease-in-out infinite;
}
@keyframes quillwrite {
    0% {
        transform: rotate(0deg) translateX(0);
    }
    25% {
        transform: rotate(-8deg) translateX(-1px);
    }
    50% {
        transform: rotate(-3deg) translateX(1px);
    }
    75% {
        transform: rotate(-10deg) translateX(-1px);
    }
    100% {
        transform: rotate(0deg) translateX(0);
    }
}
</style>
