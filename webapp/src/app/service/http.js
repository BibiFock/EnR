import Vue from '../index.js';
import Auth from './auth.js';

var Http = {
    interceptor: (request, next)  => {
        if (Auth.user.authenticated) {
            request.headers['Authorization'] = 'Bearer ' + localStorage.getItem('id_token');
        }

        next((response) => {
            if (response.status == 401) {
                Vue.$notify({
                    type: 'error',
                    title: response.status + '. ' + response.statusText,
                    text: response.body,
                });
                Auth.signout();
            }
        });
    }
};

export default Http;
