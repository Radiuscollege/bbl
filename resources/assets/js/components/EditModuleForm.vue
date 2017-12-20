<template>
  <form class="form-horizontal was-validated">
    <div class="form-group row">
      <label for="inputName" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="module.name" type="text" placeholder="Naam" required>
      </div>
    </div>
    <div class="form-group row">
      <label for="inputSubDescription" class="col-sm-2 col-form-label">Sub beschrijving</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="module.sub_description" type="text" placeholder="Sub beschrijving">
      </div>
    </div>
      <div class="form-group row">
      <label for="inputPeriod" class="col-sm-2 col-form-label">Duur in weken</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="module.week_duration" type="number" min="0" max="100" placeholder="Duur in weken" required>
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
    <medium-editor :text='module.long_description' :options='options' v-on:edit='processEditOperation' custom-tag='div'>
    </medium-editor>
    <div class="form-group row">
      <div class="col-sm-10">
        <button class="btn btn-primary" v-on:click="saveModule">Opslaan</button>
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
      selectedObjects: [],
      selectedIds: [],
      module: [],
      text: ""
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
    this.getModule();
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
    getModule: function() {
      axios
        .get("/api/module/" + _.last(window.location.pathname.split("/")))
        .then(res => {
          this.module = res.data;
          this.selectedObjects = [];
          //this.module.cohorts.forEach(function(element) {
          //  this.selectedObjects.push({id: element.pivot.cohort_id, name: element.name})
          //});
          for (var i = 0, len = this.module.cohorts.length; i < len; i++) {
            this.selectedObjects.push({id: this.module.cohorts[i].pivot.cohort_id, name: this.module.cohorts[i].name})
          }
          //this.selectedObjects = this.module.cohorts.name;
          //this.cohorts.id = this.module.cohorts[0].pivot.cohort_id
          //this.cohorts.name = this.module.cohorts[0].name
          /*this.selectedObjects = [
            {
              id: this.module.cohorts[0].pivot.cohort_id,
              name: this.module.cohorts[0].name
            },
            {
              id: this.module.cohorts[1].pivot.cohort_id,
              name: this.module.cohorts[1].name
            }
          ];*/
          //this.selectedIds = this.module.cohorts[0].pivot.cohort_id
        });
    },
    saveModule: function(e) {
      e.preventDefault();
      axios
        .put("/api/module/" + _.last(window.location.pathname.split("/")), {
          name: this.module.name,
          subDescription: this.module.sub_description,
          weekDuration: this.module.week_duration,
          cohorts: this.selectedIds,
          longDescription: this.text
        })
        .then(res => {
          document.location.href = "../module";
        })
        .catch(err => console.error(err));
    }
  }
};
</script>
