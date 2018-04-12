import Vue from '../../index.js'

const state = {
    center: {
        lat: process.env.COORD.LATITUDE,
        lng: process.env.COORD.LONGITUDE
    },
    type: process.env.MAP.TYPE,
    zoom: process.env.MAP.ZOOM,
    zoomEdit: process.env.MAP.ZOOM_EDIT,
}

const getters = {};
const actions = {};

const mutations = {
    loadCacheInfos: (state) => {
        if (Vue.$cookie.get('map-center')) {
            let cookieCenter = Vue.$cookie.get('map-center').split(',');
            state.center = {
                lat: parseFloat(cookieCenter[0]),
                lng: parseFloat(cookieCenter[1])
            };
        }

        if (Vue.$cookie.get('map-type')) {
            state.type = Vue.$cookie.get('map-type');
        }
    },
    typeChanged: function(state, mapType) {
        state.type = mapType;
        Vue.$cookie.set('map-type', mapType, 30);
    },
    centerChanged: function(state, center) {
        Vue.$cookie.set(
            'map-center',
            [center.lat(), center.lng()],
            30
        );
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
