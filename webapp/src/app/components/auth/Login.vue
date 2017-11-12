<template>
    <div>
        <div class="alert alert-danger" v-if="error">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>
        <form autocomplete="off" v-on:submit="signin">
            <div class="form-group">
                <label for="name">E-mail</label>
                <input type="text" id="name" class="form-control" placeholder="gavin.belson@hooli.com" v-model="name" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="password" required>
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
                name: 'Dewayne Spencer',
                password: '123',
                error: false
            }
        },
        methods: {
            signin(event) {
                event.preventDefault()
                auth.signin(this, this.name, this.password)
            }
        }
}
</script>
