import Vue from '../index.js';
import Auth from './auth.js';

var Http = {
    interceptor: (request, next)  => {
        if (Auth.user.authenticated) {
            request.headers.append('Authorization', 'Bearer ' + localStorage.getItem('id_token'));
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
                    Vue.$notify({
                        type: 'error',
                        title: response.status + '. ' + response.statustext,
                        text: response.body,
                    });
                    Auth.signout(Vue);
                    break;

                case 500:
                case 404:
                    Vue.$notify({
                        type: 'error',
                        title: response.status + '. ' + response.statustext,
                        text: response.body.message
                    });
            }
        });
    }
};

export default Http;
