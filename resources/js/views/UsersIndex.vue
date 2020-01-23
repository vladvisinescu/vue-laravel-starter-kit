<template>
    <div class="users">
        <div class="loading" v-if="loading">
            Loading...
        </div>

        <div v-if="error" class="alert alert-warning">
            {{ error }}
        </div>

        <ul v-if="users">
            <li v-for="{ name, email } in users">
                <strong>Name:</strong> {{ name }},
                <strong>Email:</strong> {{ email }}
            </li>
        </ul>
    </div>
</template>
<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                loading: false,
                users: null,
                error: null,
            };
        },

        beforeRouteEnter(to, from, next) {
            next(vm => vm.fetchData())
        },

        methods: {
            fetchData() {
                this.error = this.users = null;
                this.loading = true;
                axios.get('/api/users').then(response => {
                    this.users = response.data
                }).finally(() => {
                    this.loading = false
                });
            }
        }
    }
</script>
