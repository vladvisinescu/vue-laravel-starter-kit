<template>
    <div>
        {{ message }}
    </div>
</template>

<script>
    import User from "../common/auth/user";

    export default {

        data() {
            return {
                message: ''
            }
        },

        created() {
            this.message = 'Hello Administrator'
        },
    }
</script>
