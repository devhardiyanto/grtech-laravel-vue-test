<script setup lang="ts">
import { AlertDialogCancel, type AlertDialogCancelProps } from 'radix-vue';
import { cn } from '@/lib/utils';
import { buttonVariants } from '@/components/ui/button';

const props = withDefaults(defineProps<AlertDialogCancelProps & { class?: string }>(), {
    class: '',
});
</script>

<template>
    <AlertDialogCancel :class="cn(buttonVariants({ variant: 'outline' }), 'mt-2 sm:mt-0', props.class)" v-bind="props">
        <slot />
    </AlertDialogCancel>
</template>