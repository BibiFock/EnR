import cloneDeep from 'lodash/cloneDeep';
import Vue from '../../index.js';

const prepareRoof = (roof) => {
  if (roof.tilts.length == 0) {
    console.warn('roof id: ' + roof.id + ' missing tilts');
    return true;
  }

  roof.position = {
    lat: parseFloat(roof.tilts[0].latitude),
    lng: parseFloat(roof.tilts[0].longitude)
  };

  return roof;
};

const state = {
    roofs: [],
    roof: null,
    types: {
        types: null,
        probabilities: null,
        structures: null,
        purchase_categories: null,
        structure_types: null
    }
}

const getters = {};
const actions = {
    loadRoofs: ({commit, state}) => {
        if (state.roofs.length > 0) {
            return true;
        }
        Vue.$http.get(
            process.env.API_URL + 'roofs'
        ).then(
            response => {
                commit('roofsLoaded', response.body);
            }
        );
    },
    loadRoof: ({ commit, state }, roofId) => {
        return new Promise((resolve, reject) => {
            let found = state.roofs.find(roof => roof.id === roofId);
            if (found) {
                resolve(found);
            }

            Vue.$http.get(
                process.env.API_URL + 'roofs/' + roofId
            ).then( response => {
                resolve(response.body);
            });
        });
    },
    saveRoof: ({commit, state}, roof) => {
        return new Promise((resolve, reject) => {
            let method = 'post',
                url = process.env.API_URL + 'roofs/';

            if (roof.id) {
                method = 'put';
                url += this.roof.id;
            }

            this.$http[method](url, params).then(
                response => {
                    resolve(response.body);
                    commit('roofSaved', {
                        name: name,
                        data: response.body
                    });

                },
                response => {
                    if (response.status !== 400) {
                        reject(false);
                    }

                    let errors = { tilts: [] };
                    for (let key in response.body) {
                        if (!/^tilts./.test(key)) {
                            errors[key] = response.body[key];
                            continue;
                        }
                        let details = key.split('.');
                        if (!errors.tilts[details[1]]) {
                            errors.tilts[details[1]] = {};
                        }
                        errors.tilts[details[1]][details[2]] = response.body[key]
                            .map(el => el.replace( 'tilts.' + details[1] + '.', ''));
                    }
                    reject(errors);
                }
            );
        });
    },
    loadTypesInfos: ({commit, state}) => {
        for (let name in state.types) {
            let url = process.env.API_URL;
            if (['structures', 'structure_types'].indexOf(name) == -1) {
                url += 'roof/';
                if (name == 'types') {
                    url += 'tilt/';
                }
            }

            if (state.types[name]) {
                continue;
            }

            Vue.$http.get(
                url + name
            ).then(response => {
                state.types[name] = response.body
                commit('typesLoaded', {
                    name: name,
                    data: response.body
                });
            });
        }
    },
};

const mutations = {
    roofsLoaded: (state, roofs) => {
        state.roofs = roofs.map(roof => {
            return prepareRoof(roof);
        });
    },
    roofSaved: (state, roof) => {
        roof = prepareRoof(roof);
        let index = state.roofs.findIndex(el => el.id === roof.id);
        if (index) {
            state.roofs[index] = roof;
        } else {
            state.roofs.push(roof);
        }
    },
    typesLoaded: (state, type) => {
        state.types[type.name] = type.data;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
