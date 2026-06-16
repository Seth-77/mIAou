<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Reprends ta quête',
        description: 'Présente ton sceau pour entrer dans la taverne',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <Head title="Connexion" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-[#7bbf6a]"
    >
        {{ status }}
    </div>

    <PasskeyVerify />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="email" class="text-[#e8d9b5]">Email</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="border-[#d4a843]/30 bg-[#1a120b]/60 text-[#e8d9b5] placeholder:text-[#e8d9b5]/40 focus-visible:border-[#d4a843] focus-visible:ring-[#d4a843]/40"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password" class="text-[#e8d9b5]">Mot de passe</Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-sm text-[#d4a843] hover:text-[#f3e6c4]"
                        :tabindex="5"
                    >
                        Sceau oublié ?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Mot de passe"
                    class="border-[#d4a843]/30 bg-[#1a120b]/60 text-[#e8d9b5] placeholder:text-[#e8d9b5]/40 focus-visible:border-[#d4a843] focus-visible:ring-[#d4a843]/40"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3 text-[#e8d9b5]">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span>Se souvenir de moi</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-4 w-full border border-[#d4a843] bg-gradient-to-b from-[#b8841f] to-[#8a5e12] tracking-widest text-[#1a120b] uppercase hover:from-[#d4a843] hover:to-[#a06d18]"
                style="font-family: 'Cinzel', serif; font-weight: 700"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                Entrer dans la taverne
            </Button>
        </div>

        <div class="text-center text-sm text-[#e8d9b5]/70">
            Pas encore de héros ?
            <TextLink
                :href="register()"
                class="text-[#d4a843] hover:text-[#f3e6c4]"
                :tabindex="5"
                >Rejoins l'aventure</TextLink
            >
        </div>
    </Form>
</template>
