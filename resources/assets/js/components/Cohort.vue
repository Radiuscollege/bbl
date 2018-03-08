<template>
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
      submitted: false
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
          this.selected = { name: submittedCohort };
          /* open dropdownlist and close
          this.$refs.multiselect.$el.focus();
          setTimeout(() => {
            this.$refs.multiselect.$refs.search.blur();
          }, 1000);
          */
        })
        .catch(err => {
          window.alert("Er iets mis gegaan, misschien bestaat deze cohort al?");
          this.submitted = false;
        });
    }
  }
};
</script>
