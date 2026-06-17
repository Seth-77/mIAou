<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useStream } from '@laravel/stream-vue';
import MarkdownRenderer from '@/components/MarkdownRenderer.vue';

const props = defineProps<{
    models: Array<{ id: string; name: string }>;
    selectedModel: string;
}>();

// State
const message = ref('');
const model = ref(props.selectedModel);
const temperature = ref(1.0);

/**
 * useStream hook - Le hook concatène automatiquement dans `data`
 */
const { data, isFetching, isStreaming, send } = useStream('/ask-stream', {
    onData: () => {
        // Callback appelé pour chaque chunk reçu
        // Le contenu est automatiquement concaténé dans `data`
    },
    onFinish: () => {
        // Appelé quand le stream se termine
        message.value = '';
    },
    onError: (err: Error) => {
        console.error('Erreur streaming:', err);
    },
});

/**
 * Submit handler - Envoie la requête de streaming
 */
const submit = () => {
    if (!message.value.trim() || isStreaming.value) return;

    send({
        message: message.value,
        model: model.value,
        temperature: temperature.value,
    });
};
</script>

<template>
    <Head title="Oracle en direct" />

    <div
        class="min-h-screen bg-[#1a120b] text-[#e8d9b5]"
        style="font-family: 'EB Garamond', serif"
    >
        <div class="mx-auto max-w-3xl space-y-6 px-4 py-10">
            <h1
                class="text-2xl font-bold tracking-wide text-[#d4a843]"
                style="font-family: 'Cinzel', serif"
            >
                Oracle en direct (streaming)
            </h1>

            <!-- Sélecteur de modèle -->
            <div>
                <label class="mb-1 block text-sm font-medium">Oracle</label>
                <select
                    v-model="model"
                    class="w-full rounded-md border border-[#d4a843]/40 bg-[#241810] p-2 text-[#e8d9b5]"
                >
                    <option v-for="m in props.models" :key="m.id" :value="m.id">
                        {{ m.name }}
                    </option>
                </select>
            </div>

            <!-- Champ message -->
            <div>
                <label class="mb-1 block text-sm font-medium">Ta requête</label>
                <textarea
                    v-model="message"
                    rows="3"
                    class="w-full rounded-md border border-[#d4a843]/40 bg-[#241810] p-2 text-[#e8d9b5] placeholder:text-[#e8d9b5]/40"
                    placeholder="Pose ta question à l'oracle..."
                    @keydown.enter.exact.prevent="submit"
                />
            </div>

            <!-- Bouton -->
            <button
                @click="submit"
                :disabled="isStreaming"
                class="rounded-md border border-[#d4a843] bg-gradient-to-b from-[#b8841f] to-[#8a5e12] px-4 py-2 font-bold tracking-wide text-[#1a120b] uppercase transition hover:from-[#d4a843] hover:to-[#a06d18] disabled:opacity-50"
                style="font-family: 'Cinzel', serif"
            >
                {{ isStreaming ? 'Invocation en cours...' : "Consulter l'oracle" }}
            </button>

            <!-- Phase de connexion (avant la première goutte) -->
            <p v-if="isFetching" class="text-sm text-[#e8d9b5]/60">
                Connexion à l'oracle...
            </p>

            <!-- Affichage du contenu principal -->
            <div
                v-if="data"
                class="rounded-xl border border-[#d4a843]/30 bg-[#241810]/60 p-4"
            >
                <MarkdownRenderer :content="data" />
            </div>
        </div>
    </div>
</template>
