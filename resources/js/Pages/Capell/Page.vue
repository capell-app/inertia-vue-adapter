<script setup>
import ContentWidget from '../../Components/Capell/Widgets/Content.vue'
import ImageWidget from '../../Components/Capell/Widgets/Image.vue'
import TitleWidget from '../../Components/Capell/Widgets/Title.vue'

const props = defineProps({
    page: {
        type: Object,
        required: true,
    },
})

const widgets = {
    'Capell/Widgets/Content': ContentWidget,
    'Capell/Widgets/Image': ImageWidget,
    'Capell/Widgets/Title': TitleWidget,
}
</script>

<template>
    <main class="capell-inertia-page">
        <section class="capell-inertia-page__content">
            <h1 v-if="page.title">{{ page.title }}</h1>
            <div
                v-if="typeof page.content === 'string'"
                v-html="page.content"
            />
        </section>

        <section
            v-for="container in page.layout?.containers ?? []"
            :key="container.key"
            class="capell-inertia-page__container"
            :data-container="container.key"
        >
            <component
                :is="widgets[widget.component] ?? ContentWidget"
                v-for="widget in container.widgets"
                :key="`${widget.key}:${widget.occurrence}`"
                :widget="widget"
            />
        </section>
    </main>
</template>
