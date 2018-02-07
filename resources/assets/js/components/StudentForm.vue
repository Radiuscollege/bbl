<template>
  <form v-on:submit.prevent class="form-horizontal">
    <button v-on:click="print" class="btn btn-primary float-right d-print-none">Uitprinten</button>
    <fieldset :disabled="isStudent">
      <div v-if="error" class="alert alert-danger">
          {{error}}
      </div>
      <div class="form-group row">
        <label for="inputNumber" class="col-sm-2 col-form-label">OV-Nummer</label>
        <div class="col-sm-5">
          <input v-validate="'required'" :class="{'input': true, 'form-control': true, 'invalid': errors.has('studentNumber') }" v-model="student.student_id" name="studentNumber" type="text" placeholder="OV-Nummer">
        </div>
      </div>
      <div class="form-group row">
        <label for="inputFirstName" class="col-sm-2 col-form-label">Voornaam</label>
        <div class="col-sm-5">
          <input v-validate="'required'" :class="{'input': true, 'form-control': true, 'invalid': errors.has('firstName') }" v-model="student.first_name" class="form-control" name="firstName"  type="text" placeholder="Voornaam">
        </div>
      </div>
      <div class="form-group row">
        <label for="inputPrefix" class="col-sm-2 col-form-label">Tussenvoegsel</label>
        <div class="col-sm-5">
          <input v-model="student.prefix" class="form-control" type="text">
        </div>
      </div>
      <div class="form-group row">
        <label for="inputLastName" class="col-sm-2 col-form-label">Achternaam</label>
        <div class="col-sm-5">
          <input v-validate="'required'" :class="{'input': true, 'form-control': true, 'invalid': errors.has('lastName') }" v-model="student.last_name" class="form-control" name="lastName" type="text" placeholder="Achternaam">
        </div>
      </div>
      <div class="form-group row">
        <label for="cohort" class="col-sm-2 col-form-label">Cohort</label>
        <div class="col-sm-5">
          <multiselect v-if="!isStudent" v-model="selectedObjects"
            :class="{ 'is-danger': errors.has('cohort') }"
            :options="cohorts"
            :disabled="studentInfo !== undefined"
            v-validate="'required'"
            placeholder="Kies cohort"
            name="cohort"
            label="name" 
            track-by="id">
          </multiselect>
          <input v-else v-model="student.cohort_name" class="form-control" type="text" placeholder="Cohort">
        </div>
      </div>
      <div class="form-group row">
        <label for="cohort" class="col-sm-2 col-form-label">Startdatum studie</label>
        <div class="col-sm-5">
          <datepicker v-validate="'required'" :input-class="{'invalid': errors.has('date') }" v-model="student.started_on" :format="format" name="date"></datepicker>
        </div>
      </div>
      <div v-if="student.progress" class="form-group row">
        <label for="inputPrefix" class="col-sm-2 col-form-label">Geslaagd</label>
        <div class="col-sm-5">
          <i v-if="student.progress == 100" class="fas fa-check-square my-auto mr-4"></i>
          <i v-else class="fas fa-window-close my-auto mr-4"></i>
        </div>
      </div>
    </fieldset>
    <div class="form-group row">
      <div class="col-sm-10">
        <button v-if="!isStudent" v-on:click="validateForm" :disabled="submitted" class="btn btn-primary d-print-none">Opslaan</button>
        <button v-if="!isStudent" v-on:click="deleteStudent" :disabled="submitted" class="btn btn-danger d-print-none float-right">Verwijderen</button>
      </div>
    </div>
  </form>
</template>
<script>
import editor from "vue2-medium-editor";
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";
import Multiselect from "vue-multiselect";
import Datepicker from "vuejs-datepicker";
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

export default {
  name: "studentform",
  data: function() {
    return {
      student: {
        cohorts: [{ id: "", name: "" }],
        student_id: "",
        firstName: "",
        prefix: "",
        lastName: "",
        date: null,
      },
      selectedObjects: [],
      selectedIds: [],
      cohorts: [{ id: "", name: "" }],
      format: "yyyy-MM-dd",
      submitted: false,
      error: ""
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
  props: ["studentInfo", "isStudent"],
  created: function() {
    if(!this.isStudent){
      this.getCohorts();
      if (this.studentInfo) {
        this.student = this.studentInfo;
        this.selectedObjects = {id: this.student.cohort_id, name: this.student.cohort_name};
      }
    } 
    else {
      this.getStudent();
    }

  },
  methods: {
    getStudent: function() {
      axios.get("/api/student").then(res => {
        this.student = res.data;
      });
    },
    getCohorts: function() {
      axios.get("/api/cohort").then(res => {
        this.cohorts = res.data;
      });
    },
    saveStudent: function() {
      this.submitted = true;
      if (this.studentInfo !== undefined){
      axios
        .put("/api/student/" + _.last(window.location.pathname.split("/")), {
          studentNumber: this.student.student_id,
          cohorts: this.selectedIds,
          firstName: this.student.first_name,
          prefix: this.student.prefix,
          lastName: this.student.last_name,
          date: this.student.started_on
        })
        .then(res => {
          this.submitted = false;
        })
        .catch(err => {
          this.submitted = false;
          this.error = err.response.data.errors;
        })
      }
      else {
      axios
        .post("/api/student", {
          studentNumber: this.student.student_id,
          cohorts: this.selectedIds,
          firstName: this.student.first_name,
          prefix: this.student.prefix,
          lastName: this.student.last_name,
          date: this.student.started_on
        })
        .then(res => {
          document.location.href = '../student';
        })
        .catch(err => {
          this.submitted = false;
          this.error = err.response.data.errors.studentNumber[0];
        })
      }
    },
    //check if user is sure to delete the student (confirm can be without the window prefix)
    deleteStudent: function() {
      if (confirm("Weet je zeker dat je de student wilt verwijderen? (student wordt gearchiveerd)")) {
        this.submitted = true;
        axios
        .delete("/api/student/" + this.student.id, {
          studentNumber: this.student.student_id,
          cohorts: this.selectedIds,
          firstName: this.student.first_name,
          prefix: this.student.prefix,
          lastName: this.student.last_name,
          date: this.student.started_on
        })
        .then(res => {
          document.location.href = '../student';
        })
        .catch(err => {
          this.submitted = false;
          this.error = err.response.data.errors;
        })
      }
    },
    validateForm: function() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.saveStudent();
          return;
        }
        this.error = "Je bent vergeten om iets in te vullen."
      });
    },
    print: function() {
      window.print();
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