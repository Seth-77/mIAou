<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Instructions',
                href: '/settings/instructions',
            },
        ],
    },
})

const props = defineProps<{
    about_me: string | null
    assistant_instructions: string | null
}>()

const form = useForm({
    about_me: props.about_me ?? '',
    assistant_instructions: props.assistant_instructions ?? '',
})

const submit = () => {
    form.patch('/settings/instructions', {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Instructions personnalisées" />

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Instructions personnalisées"
            description="Dites à l'assistant qui vous êtes et comment vous souhaitez qu'il réponde."
        />

        <div class="grid gap-2">
            <Label for="about_me">À propos de vous</Label>
            <textarea
                id="about_me"
                v-model="form.about_me"
                rows="4"
                maxlength="2000"
                class="mt-1 block w-full rounded-md border border-input bg-transparent p-2 text-sm"
                placeholder="Ex : Je suis développeur PHP débutant, j'aime les explications avec des exemples de code..."
            />
            <InputError class="mt-2" :message="form.errors.about_me" />
        </div>

        <div class="grid gap-2">
            <Label for="assistant_instructions">Comportement de l'assistant</Label>
            <textarea
                id="assistant_instructions"
                v-model="form.assistant_instructions"
                rows="4"
                maxlength="2000"
                class="mt-1 block w-full rounded-md border border-input bg-transparent p-2 text-sm"
                placeholder="Ex : Réponds de manière concise, va droit au but, utilise des listes à puces..."
            />
            <InputError class="mt-2" :message="form.errors.assistant_instructions" />
        </div>

        <div class="flex items-center gap-4">
            <Button :disabled="form.processing" @click="submit">
                Enregistrer
            </Button>
            <span v-if="form.recentlySuccessful" class="text-sm font-medium text-green-600">
                Enregistré.
            </span>
        </div>
    </div>
</template>