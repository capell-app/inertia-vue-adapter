<script setup>
defineProps({
    widget: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <header class="capell-widget capell-widget-title">
        <p v-if="widget.data?.eyebrow">{{ widget.data.eyebrow }}</p>
        <h2>{{ widget.data?.title ?? widget.data?.heading }}</h2>
    </header>
</template>
