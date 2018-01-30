<template>
  <form v-on:submit.prevent class="form-horizontal">
    <div v-if="error" class="alert alert-danger">
        {{error}}
    </div>
    <div class="form-group row">
      <label for="inputName" class="col-sm-2 col-form-label">Naam</label>
      <div class="col-sm-5">
        <input v-validate="'required'" :class="{'input': true, 'form-control': true, 'invalid': errors.has('name') }" v-model="module.name" name="name" type="text" placeholder="Naam">
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
        <input v-validate="'required|numeric|max_value:100'" name="weekDuration" :class="{ 'input': true, 'form-control': true, 'invalid': errors.has('weekDuration') }" v-model="module.week_duration" type="number" placeholder="Duur in weken">
      </div>
    </div>
    <div class="form-group row">
      <label for="cohort" class="col-sm-2 col-form-label">Cohort</label>
      <div class="col-sm-5">
        <multiselect v-model="selectedObjects"
          :class="{ 'is-danger': errors.has('cohort') }"
          :options="cohorts"
          :multiple="true" 
          v-validate="'required'"
          placeholder="Kies cohort(en)"
          name="cohort"
          label="name" 
          track-by="id">
        </multiselect>
      </div>
    </div>
    <br>
    <medium-editor :text='module.long_description' :options='options' v-on:edit='processEditOperation' custom-tag='div'>
    </medium-editor>
    <div class="form-group row">
      <div class="col-sm-10">
        <button v-on:click="validateForm" :disabled="submitted" class="btn btn-primary" >Opslaan</button>
      </div>
    </div>
  </form>
</template>
<script>
import editor from "vue2-medium-editor";
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";
import Multiselect from "vue-multiselect";
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

export default {
  name: "moduleform",
  data: function() {
    return {
      cohorts: [{ id: "", name: "" }],
      module: { name: "", sub_description: "", week_duration: null, long_description: ""},
      selectedObjects: [],
      selectedIds: [],
      options: { placeholder: { text: "Voeg hier een beschrijving toe" }},
      submitted: false,
      error: ""
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
      if (Number.isInteger(parseInt(_.last(window.location.pathname.split("/"))))){
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
        });
      }
    },
    saveModule: function() {
      this.submitted = true;
      if (_.isInteger(parseInt(_.last(window.location.pathname.split("/"))))){
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
        .catch(err => {
          this.submitted = false;
          this.error = err.response.data.errors.name[0];
        });
      } else {
      axios
        .post("/api/module", {
          name: this.module.name,
          subDescription: this.module.sub_description,
          weekDuration: this.module.week_duration,
          cohorts: this.selectedIds,
          longDescription: this.text
        })
        .then(res => {
          document.location.href = '../module';
        })
        .catch(err => {
          this.submitted = false;
          console.log(err);
          this.error = err.response.data.errors.name[0];
        });
      }
    },
    validateForm: function() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.saveModule();
          return;
        }
        this.error = "Je bent vergeten om iets in te vullen."
      });
    }
  }
};
</script>
<style>
.is-danger .multiselect__tags {
  border-color: red;
}
.invalid {
  border-color: red!important;
}
</style>
