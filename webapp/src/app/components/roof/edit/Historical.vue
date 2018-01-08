<template>
    <div>
        <table class="table">
            <thead>
                <tr class="row">
                    <th class="col-2 text-center">utilisateur</th>
                    <th class="col-2 text-center">date</th>
                    <th class="col-6 text-center">modifications</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(hist, index) in historicals" :key="hist.id" class="row">
                    <td class="col-2">
                        <span >{{ hist.user.name }}</span>
                    </td>
                    <td class="col-2">{{ hist.created_at }}</td>
                    <td class="col-6">
                        <ul>
                            <li v-for="(changes, num) in hist.state" key="num"
                                v-if="changes.isChanged">
                                <span>{{ changes.key }}</span> :
                                <ul v-if="changes.diff">
                                    <li v-for="(row, i) in changes.diff" key="i"
                                        v-if="row.isChanged">
                                        <span>{{ row.key }}</span>: 
                                        <strong>{{ row.old }}</strong> ->
                                        <strong>{{ row.new }}</strong>
                                    </li>
                                </ul>
                                <span v-else>
                                    <!-- <span>{{ changes.key }}</span>:  -->
                                    <strong>{{ changes.old }}</strong> ->
                                    <strong>{{ changes.new }}</strong>
                                </span>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-6">
                <button type="button" class="btn btn-link"
                    v-on:click="backToMap()">retour</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        roofId: 0,
    },
    methods: {
        backToMap:  function() {
            this.$router.push({ name: 'map' });
        },
        searchDiff: function (oldState, newState) {
            let key = null,
                hiddenKey = ['id', 'created_at', 'updated_at'],
                diff = [],
                keys = [];

            if (oldState == null || newState == null) {
                if (oldState === newState) {
                    return [];
                }
                if (oldState == null) {
                    oldState = {};
                }
                if (newState == null) {
                    newState = {};
                }
            }

            keys = Object.keys(newState);
            for (key in oldState) {
                if (keys.indexOf(key) < 0) {
                    keys.push(key);
                }
            }


            for (let i = 0; i < keys.length; i++) {
                key = keys[i];
                if (hiddenKey.indexOf(key) > -1
                    || /_id$/.test(key)
                ) {
                    continue;
                }

                if (typeof oldState[key] !== 'object'
                    && typeof newState[key] !== 'object'
                ) {
                    diff.push({
                        key: key,
                        old: oldState[key],
                        new: newState[key],
                        isChanged: (oldState[key] != newState[key])
                    });
                    continue;
                }

                let subDiff = this.searchDiff(oldState[key], newState[key]);
                if (subDiff.length === 0) {
                    continue;
                } else if (subDiff.length === 1) {
                    subDiff[0].key = key;
                    diff.push(subDiff[0]);
                    continue;
                }
                diff.push({
                    key: key,
                    diff: subDiff,
                    isChanged: subDiff.filter( el => el.isChanged ).length > 0
                });
            }

            return diff;
        },
        loadHistorical: function(roofId) {
            this.$http.get(
                process.env.API_URL + 'roofs/' + roofId + '/historical'
            ).then(
                response => {
                    let defaultUser = { name: 'console' };
                    let rows = response.body;
                    for (let i = rows.length - 1; i > -1; i--) {
                        if (rows[i].user === null) {
                            rows[i].user = defaultUser;
                        }
                        rows[i].state = this.searchDiff(
                            ( i > 0 ? rows[i-1].state : {} ),
                            rows[i].state
                        );
                    }

                    rows.reverse();
                    this.historicals = rows;
                }
            );
        }
    },
    mounted() {
        if (this.$route.params.roofId == false) {
            this.$router.push({
                name: 'roof-edit',
                params: { roofId: this.$route.params.roofId }
            });
            return true;
        }

        this.loadHistorical(this.$route.params.roofId);
    },
    data() {
        return {
            historicals:[]
        };
    }
}
</script>
