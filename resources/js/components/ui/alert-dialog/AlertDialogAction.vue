<script setup lang="ts">
import { AlertDialogAction, type AlertDialogActionProps } from 'radix-vue';
import { cn } from '@/lib/utils';
import { buttonVariants } from '@/components/ui/button';

const props = withDefaults(defineProps<AlertDialogActionProps & { class?: string }>(), {
    class: '',
});
</script>

<template>
    <AlertDialogAction :class="cn(buttonVariants(), props.class)" v-bind="props">
        <slot />
    </AlertDialogAction>
</template>