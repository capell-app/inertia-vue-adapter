<script setup>
defineProps({
    widget: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <figure class="capell-widget capell-widget-image">
        <img
            v-if="widget.data?.image?.url"
            :alt="widget.data.image.alt ?? ''"
            :src="widget.data.image.url"
        />
        <figcaption v-if="widget.data?.caption">
            {{ widget.data.caption }}
        </figcaption>
    </figure>
</template>
