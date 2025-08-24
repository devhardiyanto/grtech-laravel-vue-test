<script setup lang="ts">
import { AlertDialogDescription, type AlertDialogDescriptionProps } from 'radix-vue';
import { cn } from '@/lib/utils';

const props = withDefaults(defineProps<AlertDialogDescriptionProps & { class?: string }>(), {
    class: '',
});
</script>

<template>
    <AlertDialogDescription :class="cn('text-sm text-muted-foreground', props.class)" v-bind="props">
        <slot />
    </AlertDialogDescription>
</template>