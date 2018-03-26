<template>
  <form 
    class="form-horizontal" 
    @submit.prevent
  >
    <div 
      v-if="error" 
      class="alert alert-danger"
    >
      {{ error }}
    </div>
    <div class="form-group row">
      <label 
        for="inputName" 
        class="col-sm-2 col-form-label"
      >
        Naam
      </label>
      <div class="col-sm-5">
        <input 
          v-validate="'required|max:40'" 
          :class="{'input': true, 'form-control': true, 'invalid': errors.has('name') }" 
          v-model="module.name" 
          name="name" 
          type="text" 
          placeholder="Naam"
        >
      </div>
    </div>
    <div class="form-group row">
      <label 
        for="inputSubDescription" 
        class="col-sm-2 col-form-label"
      >
        Sub beschrijving
      </label>
      <div class="col-sm-5">
        <input 
          v-validate="'max:140'" 
          :class="{'input': true, 'form-control': true, 'invalid': errors.has('subDescription') }"
          v-model="module.sub_description"  
          name="subDescription" 
          type="text" 
          placeholder="Sub beschrijving"
        >
      </div>
    </div>
    <div class="form-group row">
      <label 
        for="inputPeriod" 
        class="col-sm-2 col-form-label"
      >
        Duur in weken
      </label>
      <div class="col-sm-5">
        <input 
          v-validate="'required|numeric|max_value:100'" 
          :class="{ 'input': true, 'form-control': true, 'invalid': errors.has('weekDuration') }" 
          v-model="module.week_duration" 
          name="weekDuration" 
          type="number" 
          placeholder="Duur in weken"
        >
      </div>
    </div>
    <div class="form-group row">
      <label 
        for="cohort" 
        class="col-sm-2 col-form-label"
      >
        Cohort
      </label>
      <div class="col-sm-5">
        <multiselect 
          v-validate="'required'"
          v-model="selectedObjects"
          :class="{ 'is-danger': errors.has('cohort') }"
          :options="cohorts"
          :multiple="true"
          placeholder="Kies cohort(en)"
          name="cohort"
          label="name" 
          track-by="id"
        />
      </div>
    </div>
    <br>
    <div class="form-group row">
      <label 
        for="cohort" 
        class="col-sm-2 col-form-label"
      >
        Lange beschrijving
      </label>
    </div>
    <div class="col-sm-8">
      <medium-editor 
        :text="module.long_description" 
        :options="options"  
        custom-tag="div"
        @edit="processEditOperation"
      />
    </div>
    <div class="form-group row">
      <div class="col-sm-10">
        <button 
          :disabled="submitted" 
          class="btn btn-primary" 
          @click="validateForm"
        >
          Opslaan
        </button>
      </div>
    </div>
  </form>
</template>
<script>
import editor from "vue2-medium-editor";
import Multiselect from "vue-multiselect";
import VeeValidate from "vee-validate";

Vue.use(VeeValidate);

export default {
  name: "Moduleform",
  components: {
    "medium-editor": editor,
    Multiselect
  },
  data: function() {
    return {
      cohorts: [{ id: "", name: "" }],
      module: {
        name: "",
        sub_description: "",
        week_duration: null,
        long_description: ""
      },
      selectedObjects: [],
      selectedIds: [],
      options: { placeholder: { text: "Voeg hier een beschrijving toe" } },
      submitted: false,
      error: "",
      text: ""
    };
  },
  watch: {
    selectedObjects(newValues) {
      this.selectedIds = newValues.map(obj => obj.id);
    }
  },
  created: function() {
    this.getCohorts();
    this.getModule();
  },
  methods: {
    processEditOperation: function(operation) {
      this.module.long_description = operation.api.origElements.innerHTML;
    },
    getCohorts: function() {
      axios.get("/api/cohort").then(res => {
        this.cohorts = res.data;
      });
    },
    getModule: function() {
      if (
        Number.isInteger(parseInt(_.last(window.location.pathname.split("/"))))
      ) {
        axios
          .get("/api/module/" + _.last(window.location.pathname.split("/")))
          .then(res => {
            this.module = res.data;
            this.text = module.long_description;
            this.selectedObjects = [];
            for (var i = 0, len = this.module.cohorts.length; i < len; i++) {
              this.selectedObjects.push({
                id: this.module.cohorts[i].pivot.cohort_id,
                name: this.module.cohorts[i].name
              });
            }
          });
      }
    },
    //Save or update, depending on if module ID is in url
    saveModule: function() {
      this.submitted = true;
      if (_.isInteger(parseInt(_.last(window.location.pathname.split("/"))))) {
        axios
          .put("/api/module/" + _.last(window.location.pathname.split("/")), {
            name: this.module.name,
            subDescription: this.module.sub_description,
            weekDuration: this.module.week_duration,
            cohorts: this.selectedIds,
            longDescription: this.module.long_description
          })
          .then(res => {
            document.location.href = "../module";
          })
          .catch(err => {
            this.submitted = false;
            if (err.response.data.errors === undefined) {
              this.error = err.response.data;
            } else {
              this.error = err.response.data.errors.name[0];
            }
          });
      } else {
        axios
          .post("/api/module", {
            name: this.module.name,
            subDescription: this.module.sub_description,
            weekDuration: this.module.week_duration,
            cohorts: this.selectedIds,
            longDescription: this.module.long_description
          })
          .then(res => {
            document.location.href = "../module";
          })
          .catch(err => {
            this.submitted = false;
            this.error = err.response.data.errors.name[0];
          });
      }
    },
    validateForm: function() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.saveModule();
          return;
        }
        this.error = "Je hebt iets incorrect ingevuld.";
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
  border-color: red !important;
}
</style>
