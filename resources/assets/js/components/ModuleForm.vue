<template>
  <form class="form-horizontal">
    <div class="form-group row">
      <label for="inputName" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="name" type="text" placeholder="Naam">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputSubDescription" class="col-sm-2 col-form-label">Sub beschrijving</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="subdescription" type="text" placeholder="Sub beschrijving">
      </div>
    </div>
      <div class="form-group row">
      <label for="inputPeriod" class="col-sm-2 col-form-label">Duur in weken</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="period" type="text" placeholder="Duur in weken">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputPeriod" class="col-sm-2 col-form-label">Cohort</label>
      <div class="col-sm-5">
        <multiselect
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
        <button type="submit" class="btn btn-primary">Opslaan</button>
      </div>
    </div>
  </form>
</template>
<script>
import editor from "vue2-medium-editor";
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";
import Multiselect from "vue-multiselect";

export default {
  name: "cohort",
  data() {
    return {
      cohorts: [{ id: "", name: "" }],
      cohort: "",
      text: "",
      selectedObjects: [],
      selectedIds: []
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
  mounted() {
    this.getCohorts();
  },
  methods: {
    processEditOperation: function(operation) {
      this.text = operation.api.origElements.innerHTML;
    },
    getCohorts() {
      axios.get("api/cohort").then(res => {
        this.cohorts = res.data;
      });
    }
  }
};
</script>
