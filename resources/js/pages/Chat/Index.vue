<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import MarkdownRenderer from '@/components/MarkdownRenderer.vue'
import ChatLoader from '@/components/ChatLoader.vue'

//defineOptions({ layout: null })

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
    if (confirm('Es-tu sûr de vouloir supprimer cette conversation ?')) {
        router.delete(`/chat/${id}`, {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <Head title="Chat" />

    <div class="flex h-[calc(100vh)] overflow-hidden bg-neutral-950 text-neutral-100">
        <!-- Sidebar : liste des conversations -->
        <aside class="flex w-64 flex-col border-r border-neutral-800 bg-neutral-900">
            <div class="border-b border-neutral-800 p-4">
                <Link
                    href="/chat"
                    method="post"
                    as="button"
                    class="block w-full rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-medium text-white transition hover:bg-blue-700"
                >
                    + Nouvelle conversation
                </Link>
            </div>

            <nav class="flex-1 overflow-y-auto p-2">
                <div
                    v-for="conv in props.conversations"
                    :key="conv.id"
                    class="group mb-1 flex items-center gap-1 rounded-md pr-1 transition hover:bg-neutral-800"
                    :class="{ 'bg-neutral-800': props.currentConversation?.id === conv.id }"
                >
                    <Link
                        :href="`/chat/${conv.id}`"
                        class="flex-1 truncate px-3 py-2 text-sm"
                    >
                        {{ conv.title ?? 'Sans titre' }}
                    </Link>

                    <button
                        @click="deleteConversation(conv.id)"
                        class="shrink-0 rounded p-1 text-neutral-500 opacity-0 transition hover:text-red-400 group-hover:opacity-100"
                        title="Supprimer"
                    >
                        🗑
                    </button>
                </div>

                <p
                    v-if="props.conversations.length === 0"
                    class="px-3 py-2 text-sm text-neutral-500"
                >
                    Aucune conversation
                </p>
            </nav>
        </aside>

        <!-- Zone principale : messages -->
        <main class="flex min-h-0 flex-1 flex-col overflow-hidden">
            <!-- Aucune conversation sélectionnée -->
            <div
                v-if="!props.currentConversation"
                class="flex flex-1 items-center justify-center text-neutral-500"
            >
                Sélectionne ou crée une conversation pour commencer.
            </div>

            <!-- Conversation active -->
            <template v-else>
                <header class="flex items-center justify-between border-b border-neutral-800 p-4">
                    <h1 class="font-medium">
                        {{ props.currentConversation.title ?? 'Sans titre' }}
                    </h1>

                    <select
                        :value="props.currentConversation.model"
                        @change="changeModel"
                        class="rounded-md border border-neutral-700 bg-neutral-900 p-2 text-sm"
                    >
                        <option
                            v-for="model in props.models"
                            :key="model.id"
                            :value="model.id"
                        >
                            {{ model.name }}
                        </option>
                    </select>
                </header>

                <div class="min-h-0 flex-1 space-y-4 overflow-y-auto p-6">
                    <div
                        v-for="message in props.messages"
                        :key="message.id"
                        class="flex"
                        :class="message.role === 'user' ? 'justify-end' : 'justify-start'"
                    >
                        <div
                            class="max-w-2xl rounded-2xl px-4 py-2"
                            :class="
                                message.role === 'user'
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-neutral-800 text-neutral-100'
                            "
                        >
                            <MarkdownRenderer :content="message.content" />
                        </div>
                    </div>

                    <!-- Loader pendant l'attente de la réponse -->
                    <div v-if="form.processing" class="flex justify-start">
                        <div class="rounded-2xl bg-neutral-800 px-4 py-3">
                            <ChatLoader />
                        </div>
                    </div>
                </div>

                <!-- Champ de saisie -->
                <footer class="border-t border-neutral-800 p-4">
                    <div class="flex gap-2">
                        <textarea
                            v-model="form.content"
                            rows="1"
                            placeholder="Écris ton message..."
                            class="flex-1 resize-none rounded-md border border-neutral-700 bg-neutral-900 p-2 text-sm"
                            @keydown.enter.exact.prevent="sendMessage"
                        />
                        <button
                            @click="sendMessage"
                            :disabled="form.processing"
                            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ form.processing ? '...' : 'Envoyer' }}
                        </button>
                    </div>
                    <p v-if="form.errors.content" class="mt-2 text-sm text-red-400">
                        {{ form.errors.content }}
                    </p>
                </footer>
            </template>
        </main>
    </div>
</template>