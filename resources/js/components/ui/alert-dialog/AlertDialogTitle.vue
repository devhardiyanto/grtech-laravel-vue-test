<script setup lang="ts">
import { AlertDialogTitle, type AlertDialogTitleProps } from 'radix-vue';
import { cn } from '@/lib/utils';

const props = withDefaults(defineProps<AlertDialogTitleProps & { class?: string }>(), {
    class: '',
});
</script>

<template>
    <AlertDialogTitle :class="cn('text-lg font-semibold', props.class)" v-bind="props">
        <slot />
    </AlertDialogTitle>
</template>