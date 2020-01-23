<template>
    <div class="toast-container" aria-live="polite" aria-atomic="true">
        <LittleMessageItem v-for="message in messages" :key="message.id" :message="message"></LittleMessageItem>
    </div>
</template>

<script>

    import { mapGetters } from 'vuex'
    import LittleMessageItem from "./LittleMessageItem";

    export default {

        components: { LittleMessageItem },

        computed: {
            ...mapGetters({ messages: 'messages/getLittleMessages' })
        }
    }
</script>
