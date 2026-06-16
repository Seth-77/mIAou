import type { ComputedRef, Ref } from 'vue';
import { computed, ref } from 'vue';
import type { Appearance, ResolvedAppearance } from '@/types';

export type { Appearance, ResolvedAppearance };

export type UseAppearanceReturn = {
    appearance: Ref<Appearance>;
    resolvedAppearance: ComputedRef<ResolvedAppearance>;
    updateAppearance: (value: Appearance) => void;
};

/**
 * Thème "taverne" : le mode sombre est forcé sur toute l'application.
 * On garde la même API que le composable d'origine pour ne rien casser,
 * mais toutes les fonctions appliquent et conservent la classe `dark`.
 */
function forceDark(): void {
    if (typeof document === 'undefined') {
        return;
    }
    document.documentElement.classList.add('dark');
}

export function updateTheme(): void {
    forceDark();
}

export function initializeTheme(): void {
    forceDark();
}

const appearance = ref<Appearance>('dark');

export function useAppearance(): UseAppearanceReturn {
    const resolvedAppearance = computed<ResolvedAppearance>(() => 'dark');

    function updateAppearance() {
        // Mode sombre forcé : on ignore la valeur et on garde `dark`.
        appearance.value = 'dark';
        forceDark();
    }

    return {
        appearance,
        resolvedAppearance,
        updateAppearance,
    };
}
