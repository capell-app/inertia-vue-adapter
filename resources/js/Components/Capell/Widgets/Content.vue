<script setup>
defineProps({
    widget: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <article class="capell-widget capell-widget-content">
        <h2 v-if="widget.data?.title">{{ widget.data.title }}</h2>
        <div
            v-if="widget.data?.content"
            v-html="widget.data.content"
        />
    </article>
</template>
