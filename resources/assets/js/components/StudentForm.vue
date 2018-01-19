<template>
  <form class="form-horizontal was-validated">
    <div class="form-group row">
      <label for="inputNumber" class="col-sm-2 col-form-label">OV-Nummer</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="studentNumber" type="text" placeholder="OV-Nummer" required>
      </div>
    </div>
    <div class="form-group row">
      <label for="inputFirstName" class="col-sm-2 col-form-label">Voornaam</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="firstName" type="text" placeholder="Voornaam" required>
      </div>
    </div>
    <div class="form-group row">
      <label for="inputPrefix" class="col-sm-2 col-form-label">Tussenvoegsel</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="prefix" type="text" placeholder="Tussenvoegsel">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputLastName" class="col-sm-2 col-form-label">Achternaam</label>
      <div class="col-sm-5">
        <input class="form-control" v-model="lastName" type="text" placeholder="Achternaam" required>
      </div>
    </div>
    <div class="form-group row">
      <label for="cohort" class="col-sm-2 col-form-label">Cohort</label>
      <div class="col-sm-5">
        <multiselect v-model="selectedObjects"
          :options="cohorts"
          label="name" 
          track-by="id">
        </multiselect>
      </div>
    </div>
    <div class="form-group row">
      <label for="cohort" class="col-sm-2 col-form-label">Startdatum studie</label>
      <div class="col-sm-5">
        <datepicker v-model="date" :format="format"></datepicker>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-10">
        <button v-on:click="saveStudent" :disabled="submitted" class="btn btn-primary">Opslaan</button>
      </div>
    </div>
  </form>
</template>
<script>
import editor from "vue2-medium-editor";
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";
import Multiselect from "vue-multiselect";
import Datepicker from "vuejs-datepicker";

export default {
  name: "studentform",
  data: function() {
    return {
      cohorts: [{ id: "", name: "" }],
      studentNumber: "",
      firstName: "",
      prefix: "",
      lastName: "",
      date: null,
      selectedObjects: [],
      selectedIds: [],
      format: "yyyy-MM-dd",
      submitted: false
    };
  },
  watch: {
    selectedObjects(newValues) {
      this.selectedIds = newValues.id
    }
  },
  components: {
    "medium-editor": editor,
    FontAwesomeIcon,
    Multiselect,
    Datepicker
  },
  created: function() {
    this.getCohorts();
  },
  methods: {
    getCohorts: function() {
      axios.get("/api/cohort").then(res => {
        this.cohorts = res.data;
      });
    },
    saveStudent: function() {
      this.submitted = true;
      axios
        .post("/api/student", {
          studentNumber: this.studentNumber,
          cohorts: this.selectedIds,
          firstName: this.firstName,
          prefix: this.prefix,
          lastName: this.lastName,
          date: this.date
        })
        .then(res => {
          document.location.href = 'student';
        })
        .catch(err => console.error(err));
    }
  }
};
</script>
