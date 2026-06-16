<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import MarkdownRenderer from '@/components/MarkdownRenderer.vue'

const props = defineProps({
    models: Array,
    selectedModel: String,
    message: String,
    response: String,
    error: String,
})

const form = useForm({
    message: props.message ?? '',
    model: props.selectedModel,
})

const submit = () => {
    form.post('/ask', {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Consulter l'oracle" />

    <div
        class="min-h-screen bg-[#1a120b] text-[#e8d9b5]"
        style="font-family: 'EB Garamond', serif"
    >
        <div class="mx-auto max-w-3xl space-y-6 px-4 py-10">
            <h1
                class="text-2xl font-bold tracking-wide text-[#d4a843]"
                style="font-family: 'Cinzel', serif"
            >
                Consulter l'oracle
            </h1>

            <!-- Formulaire -->
            <div class="space-y-4">
                <!-- Sélecteur de modèle -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-[#e8d9b5]">Oracle</label>
                    <select
                        v-model="form.model"
                        class="w-full rounded-md border border-[#d4a843]/40 bg-[#241810] p-2 text-[#e8d9b5]"
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

                <!-- Champ question -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-[#e8d9b5]">Ta requête</label>
                    <textarea
                        v-model="form.message"
                        rows="4"
                        class="w-full rounded-md border border-[#d4a843]/40 bg-[#241810] p-2 text-[#e8d9b5] placeholder:text-[#e8d9b5]/40"
                        placeholder="Pose ta question à l'oracle..."
                    />
                    <p v-if="form.errors.message" class="mt-1 text-sm text-[#d9603a]">
                        {{ form.errors.message }}
                    </p>
                </div>

                <!-- Bouton -->
                <button
                    @click="submit"
                    :disabled="form.processing"
                    class="rounded-md border border-[#d4a843] bg-gradient-to-b from-[#b8841f] to-[#8a5e12] px-4 py-2 font-bold tracking-wide text-[#1a120b] uppercase transition hover:from-[#d4a843] hover:to-[#a06d18] disabled:opacity-50"
                    style="font-family: 'Cinzel', serif"
                >
                    {{ form.processing ? 'Invocation...' : "Consulter l'oracle" }}
                </button>
            </div>

            <!-- Erreur API -->
            <div
                v-if="props.error"
                class="rounded-md border border-[#d9603a]/40 bg-[#3a1810]/60 p-4 text-[#d9603a]"
            >
                Le sort a échoué : {{ props.error }}
            </div>

            <!-- Réponse -->
            <div
                v-if="props.response"
                class="rounded-xl border border-[#d4a843]/30 bg-[#241810]/60 p-4"
            >
                <MarkdownRenderer :content="props.response" />
            </div>
        </div>
    </div>
</template>
