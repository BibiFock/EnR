<template>
    <div>
        <div class="alert alert-danger" v-if="error">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>
        <form autocomplete="off" v-on:submit="signin">
            <div class="form-group">
                <label for="name">Structure</label>
                <select class="form-control" v-model="id">
                    <option v-for="user in users"
                        :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control"
                    v-model="password" required>
            </div>
            <button type="submit" class="btn btn-default">Sign in</button>
        </form>
    </div>
</template>
<script>
import auth from '../../service/auth.js';

export default {
    data() {
        return {
            id: null,
            password: null,
            error: false,
            users: []
        }
    },
    methods: {
        signin(event) {
            event.preventDefault()
            auth.signin(this.id, this.password)
        }
    },
    mounted() {
        this.$http.get(
            process.env.API_URL + 'users'
        ).then(
            response => {
                this.users = response.body;
            }
        );
    }
}
</script>
