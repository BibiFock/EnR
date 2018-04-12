import Vue from '../index.js';
import store from '../store';

var Http = {
    interceptor: (request, next)  => {
        if (store.getters['auth/authenticated']) {
            request.headers.append(
                'Authorization',
                'Bearer ' + store.getters['auth/token']
            );
        }

        next((response) => {
            switch (response.status) {
                case 400:
                    let message = [];
                    for (let key in response.body) {
                        message.push(
                            '<strong>' + key + ': </strong>' +
                            response.body[key].join(' | ')
                        );
                    }
                    Vue.$notify({
                        type:'error',
                        title: response.statusText,
                        text: message.join('<br />')
                    });
                    break;
                case 401:
                    // Vue.$notify({
                        // type: 'error',
                        // title: response.status + '. ' + response.statustext,
                        // text: response.body,
                    // });
                    store.dispatch('auth/signOut');
                    break;

                case 500:
                case 404:
                    Vue.$notify({
                        type: 'error',
                        title: response.status + '. ' + response.statustext,
                        text: response.body.message
                    });
            }

            return response;
        });
    }
};

export default Http;
