<template>
    <div class="container">
        <component :is="currentView"></component>
    </div>
</template>

<script>

import Simple from './Simple';

export default {
  name: 'app',
  components: {
    Simple,
  },
  data () {
    return {
      currentView: 'simple'
    }
  }
}
</script>

<style>
/*.leaflet-fake-icon-image-2x {
  background-image: url(../../node_modules/leaflet/dist/images/marker-icon-2x.png);
}
.leaflet-fake-icon-shadow {
  background-image: url(../../node_modules/leaflet/dist/images/marker-shadow.png);
}
@import "../../node_modules/leaflet/dist/leaflet.css";
*/

.leaflet-fake-icon-image-2x {
  background-image: url(../node_modules/leaflet/dist/images/marker-icon-2x.png);
}
.leaflet-fake-icon-shadow {
  background-image: url(../node_modules/leaflet/dist/images/marker-shadow.png);
}

@import "../node_modules/leaflet/dist/leaflet.css";

</style>
