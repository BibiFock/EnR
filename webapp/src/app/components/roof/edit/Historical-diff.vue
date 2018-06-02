<template>
    <ul>
        <li v-for="(change, num) in state" :key="num"
            v-if="change.isChanged">
            <span>{{ change.key }}</span> :
            <HistoricalDiff
                v-if="change.diff"
                :state="change.diff"></HistoricalDiff>
            <span v-else>
                <strong>{{ change.old }}</strong> ->
                <strong>{{ change.new }}</strong>
            </span>
        </li>
    </ul>
</template>
<script>
export default {
    name: 'HistoricalDiff',
    props: {
        state: [],
    },
}
</script>
