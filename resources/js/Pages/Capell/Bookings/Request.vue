<script setup>
import { Deferred, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    options: {
        type: Object,
        default: () => ({ services: [], staff: [], locations: [] }),
    },
    slots: {
        type: Array,
        default: () => [],
    },
    postUrl: {
        type: String,
        default: '/bookings',
    },
    timezone: {
        type: String,
        default: 'UTC',
    },
})

const form = useForm({
    service_id: '',
    staff_member_id: '',
    location_id: '',
    requested_starts_at: '',
    timezone: props.timezone,
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    notes: '',
})

function reloadSlots() {
    router.reload({
        only: ['slots'],
        data: {
            service_id: form.service_id,
            staff_member_id: form.staff_member_id,
            location_id: form.location_id,
            timezone: form.timezone,
        },
    })
}
</script>

<template>
    <main class="capell-booking-request">
        <form @submit.prevent="form.post(postUrl)">
            <label>
                Service
                <select
                    v-model="form.service_id"
                    @change="reloadSlots"
                >
                    <option value="">Choose a service</option>
                    <option
                        v-for="service in options.services"
                        :key="service.id"
                        :value="service.id"
                    >
                        {{ service.name }}
                    </option>
                </select>
            </label>

            <label>
                Staff
                <select
                    v-model="form.staff_member_id"
                    @change="reloadSlots"
                >
                    <option value="">Any staff member</option>
                    <option
                        v-for="staffMember in options.staff"
                        :key="staffMember.id"
                        :value="staffMember.id"
                    >
                        {{ staffMember.display_name }}
                    </option>
                </select>
            </label>

            <label>
                Location
                <select
                    v-model="form.location_id"
                    @change="reloadSlots"
                >
                    <option value="">Choose a location</option>
                    <option
                        v-for="location in options.locations"
                        :key="location.id"
                        :value="location.id"
                    >
                        {{ location.name }}
                    </option>
                </select>
            </label>

            <label>
                Time
                <Deferred data="slots">
                    <template #fallback>
                        <select disabled>
                            <option>Choose service details first</option>
                        </select>
                    </template>

                    <select v-model="form.requested_starts_at">
                        <option value="">Choose a time</option>
                        <option
                            v-for="slot in slots"
                            :key="slot.starts_at"
                            :value="slot.starts_at"
                        >
                            {{ slot.label }}
                        </option>
                    </select>
                </Deferred>
            </label>

            <label>
                Name
                <input
                    v-model="form.customer_name"
                    autocomplete="name"
                    type="text"
                />
            </label>

            <label>
                Email
                <input
                    v-model="form.customer_email"
                    autocomplete="email"
                    type="email"
                />
            </label>

            <label>
                Phone
                <input
                    v-model="form.customer_phone"
                    autocomplete="tel"
                    type="tel"
                />
            </label>

            <label>
                Notes
                <textarea v-model="form.notes" />
            </label>

            <button
                :disabled="form.processing"
                type="submit"
            >
                Request appointment
            </button>
        </form>
    </main>
</template>
