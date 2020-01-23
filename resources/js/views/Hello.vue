<template>
    <div>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est, neque.</p>

        <big-message name="testModal">
            <template v-slot:header><p>custom header</p></template>
            <template v-slot:body><p>custom body</p></template>
        </big-message>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                //
            }
        },

        mounted() {
            //
        },

        methods: {
            //
        }
    }
</script>
