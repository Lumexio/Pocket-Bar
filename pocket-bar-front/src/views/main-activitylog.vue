<template>
  <div>
    <v-row>
      <v-col cols="12">
        <activitylogtable />
      </v-col>
    </v-row>
  </div>
</template>

<script>
import activitylogtable from "../components/table-lists/tabla-activitylog.vue";

export default {
  name: "activitylog",
  components: { activitylogtable },
};
</script>
