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
  data() {
    return {
      cohorts: [{ id: "", name: "" }],
      cohort: ""
    };
  },
  mounted() {
    this.getCohorts();
  },
  methods: {
    addCohort: function(event) {
      //post API
      if (event) {
        alert(this.cohort);
      }
    },
    getCohorts() {
      axios.get("api/cohort").then(res => {
        this.cohorts = res.data;
      });
    },
    createCohort() {
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
