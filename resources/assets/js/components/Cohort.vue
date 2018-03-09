<template>
  <span>
    <div v-if="success" class="alert alert-primary">
      {{success}}
    </div>
    <div v-if="error" class="alert alert-danger">
      {{error}}
    </div>
    <div class="row justify-content-center">
      <multiselect v-model="selected"
        :options="cohorts"
        :allow-empty="false"
        :show-labels="false"
        class="col-md-4"
        placeholder="Kies cohort(en)"
        name="cohort"
        label="name"
        track-by="id"
        ref="multiselect">
      </multiselect>
      <input v-model="cohort" placeholder="Cohort toevoegen" type="text" maxlength="80">
      <button class="btn btn-primary" v-on:click="createCohort" :disabled="submitted">Voeg cohort toe</button>
      <modulelist :cohort="selected.name"></modulelist>
    </div>
  </span>
</template>
<script>
import Multiselect from "vue-multiselect";

export default {
  name: "cohort",
  data: function() {
    return {
      cohorts: [{ id: "", name: "" }],
      cohort: "",
      selected: { id: 0, name: "Alle cohorten tonen" },
      submitted: false,
      error: "",
      success: ""
    };
  },
  components: {
    Multiselect
  },
  created: function() {
    this.getCohorts();
  },
  methods: {
    getCohorts: function() {
      axios.get("api/cohort").then(res => {
        this.cohorts = res.data;
        this.cohorts.unshift({ id: 0, name: "Alle cohorten tonen" });
      });
    },
    createCohort: function() {
      this.submitted = true;
      var submittedCohort = this.cohort;
      this.cohort = "";
      axios
        .post("api/cohort", {
          name: submittedCohort
        })
        .then(res => {
          this.submitted = false;
          this.getCohorts();
          this.error = "";
          this.success = submittedCohort + " is succesvol als cohort toegevoegd!"
        })
        .catch(err => {
          this.submitted = false;
          this.success = "";
          this.error = "Er iets mis gegaan, deze cohort bestaat al.";
        });
    }
  }
};
</script>
