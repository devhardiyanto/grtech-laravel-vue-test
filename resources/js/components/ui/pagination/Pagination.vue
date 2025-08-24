<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ChevronLeft, ChevronRight, MoreHorizontal } from 'lucide-vue-next';

interface Props {
    currentPage: number;
    totalPages: number;
    onPageChange: (page: number) => void;
}

const props = defineProps<Props>();

const pages = computed(() => {
    const items: (number | string)[] = [];
    const total = props.totalPages;
    const current = props.currentPage;

    if (total <= 7) {
        for (let i = 1; i <= total; i++) {
            items.push(i);
        }
    } else {
        items.push(1);
        
        if (current > 3) {
            items.push('...');
        }
        
        const start = Math.max(2, current - 1);
        const end = Math.min(total - 1, current + 1);
        
        for (let i = start; i <= end; i++) {
            items.push(i);
        }
        
        if (current < total - 2) {
            items.push('...');
        }
        
        items.push(total);
    }
    
    return items;
});
</script>

<template>
    <nav class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <Button
                variant="outline"
                size="sm"
                :disabled="currentPage === 1"
                @click="onPageChange(currentPage - 1)"
            >
                <ChevronLeft class="h-4 w-4" />
                Previous
            </Button>
            
            <div class="flex gap-1">
                <template v-for="page in pages" :key="page">
                    <Button
                        v-if="page === '...'"
                        variant="ghost"
                        size="sm"
                        disabled
                    >
                        <MoreHorizontal class="h-4 w-4" />
                    </Button>
                    <Button
                        v-else
                        :variant="page === currentPage ? 'default' : 'outline'"
                        size="sm"
                        @click="onPageChange(page as number)"
                    >
                        {{ page }}
                    </Button>
                </template>
            </div>
            
            <Button
                variant="outline"
                size="sm"
                :disabled="currentPage === totalPages"
                @click="onPageChange(currentPage + 1)"
            >
                Next
                <ChevronRight class="h-4 w-4" />
            </Button>
        </div>
    </nav>
</template>