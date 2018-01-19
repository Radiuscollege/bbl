<template>
  <form class="form-horizontal was-validated">
    <div class="form-group row">
      <label for="inputName" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="name" type="text" placeholder="Naam" required>
      </div>
    </div>
    <div class="form-group row">
      <label for="inputSubDescription" class="col-sm-2 col-form-label">Sub beschrijving</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="subDescription" type="text" placeholder="Sub beschrijving">
      </div>
    </div>
      <div class="form-group row">
      <label for="inputPeriod" class="col-sm-2 col-form-label">Duur in weken</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="weekDuration" type="number" min="0" max="100" placeholder="Duur in weken" required>
      </div>
    </div>
    <div class="form-group row">
      <label for="cohort" class="col-sm-2 col-form-label">Cohort</label>
      <div class="col-sm-5">
        <multiselect v-model="selectedObjects"
          :options="cohorts"
          :multiple="true" 
          label="name" 
          track-by="id">
        </multiselect>
      </div>
    </div>
    </br>
    <medium-editor :text='text' :options='options' v-on:edit='processEditOperation' custom-tag='div'>
    </medium-editor>
    <div class="form-group row">
      <div class="col-sm-10">
        <button v-on:click="saveModule" :disabled="submitted" class="btn btn-primary" >Opslaan</button>
      </div>
    </div>
  </form>
</template>
<script>
import editor from "vue2-medium-editor";
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";
import Multiselect from "vue-multiselect";

export default {
  name: "moduleform",
  data: function() {
    return {
      cohorts: [{ id: "", name: "" }],
      name: "",
      subDescription: "",
      weekDuration: null,
      text: "",
      selectedObjects: [],
      selectedIds: [],
      options: { placeholder: { text: "Voeg hier een beschrijving toe" }},
      submitted: false
    };
  },
  watch: {
    selectedObjects(newValues) {
      this.selectedIds = newValues.map(obj => obj.id);
    }
  },
  components: {
    "medium-editor": editor,
    FontAwesomeIcon,
    Multiselect
  },
  created: function() {
    this.getCohorts();
  },
  methods: {
    processEditOperation: function(operation) {
      this.text = operation.api.origElements.innerHTML;
    },
    getCohorts: function() {
      axios.get("/api/cohort").then(res => {
        this.cohorts = res.data;
      });
    },
    saveModule: function() {
      this.submitted = true;
      axios
        .post("/api/module", {
          name: this.name,
          subDescription: this.subDescription,
          weekDuration: this.weekDuration,
          cohorts: this.selectedIds,
          longDescription: this.text
        })
        .then(res => {
          document.location.href = 'module';
        })
        .catch(err => console.error(err));
    }
  }
};
</script>
