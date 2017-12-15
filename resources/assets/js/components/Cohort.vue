<template>
    <div class="row justify-content-center">
        <select v-model="selected">
            <option v-for="(cohort, index) in cohorts" v-bind:key="cohort.id">
                {{ cohort.name }}
            </option>
            
        </select>
    <input v-model="cohort" placeholder="Cohort toevoegen">
    <button class="btn-primary" v-on:click="createCohort">Voeg een cohort toe</button>
    </div>
</template>
<script>
export default {
  name: "cohort",
  data: function() {
    return {
      cohorts: [{ id: "", name: "" }],
      cohort: ""
    };
  },
  mounted: function() {
    this.getCohorts();
  },
  methods: {
    getCohorts: function() {
      axios.get("api/cohort").then(res => {
        this.cohorts = res.data;
      });
    },
    createCohort: function() {
      axios.post(
          "api/cohort",
          {
            name: this.cohort
          }
        )
        .then(res => {
          this.cohort = "";
          this.getCohorts();
        })
        .catch(err => console.error(err));
    }
  }
};
</script>
