<template>
  <div class="row justify-content-center">
    <select v-model="selected">
      <option value="All">Alle cohorts tonen</option>
      <option v-for="cohort in cohorts" v-bind:key="cohort.id">
        {{ cohort.name }}
      </option>
    </select>
    <input v-model="cohort" placeholder="Cohort toevoegen" type="text">
    <button class="btn btn-primary" v-on:click="createCohort">Voeg cohort toe</button>
    <modulelist :cohort="selected"></modulelist>
  </div>
</template>
<script>
export default {
  name: "cohort",
  data: function() {
    return {
      cohorts: [{ id: "", name: "" }],
      cohort: "",
      selected: "All"
    };
  },
  created: function() {
    this.getCohorts();
  },
  methods: {
    getCohorts: function() {
      axios.get("api/cohort").then(res => {
        this.cohorts = res.data;
      });
    },
    createCohort: function() {
      axios
        .post("api/cohort", {
          name: this.cohort
        })
        .then(res => {
          this.cohort = "";
          this.getCohorts();
        })
        .catch(err => console.error(err));
    }
  }
};
</script>
