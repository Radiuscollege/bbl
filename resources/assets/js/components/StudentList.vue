<template>
<div class="container">
  <div class="row align-items-start">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Sorteer</h5>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="firstname" value="Firstname" v-model="criteria">
        <label class="form-check-label">
          Voornaam
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="lastname" value="Lastname" v-model="criteria">
        <label class="form-check-label">
          Achternaam
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="ovnumber" value="OVNumber" v-model="criteria">
        <label class="form-check-label">
          OV-nummer
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="cohort" value="Cohort" v-model="criteria">
        <label class="form-check-label">
          Cohort
        </label>
      </div>
          <div class="form-check">
        <input class="form-check-input" type="radio" id="progress" value="Progress" v-model="criteria">
        <label class="form-check-label">
          Voortgang
        </label>
      </div>
      <br>
        <label class="btn btn-secondary">
          <input v-model="order" type="radio" id="ascend" value="asc"> Oplopend
        </label>
        <label class="btn btn-secondary">
          <input v-model="order" type="radio" nam id="descend" value="desc"> Aflopend
        </label>
    </div>
  </div>
  <div class="col-sm-6">
  <div class="list-group mb-3 text-center" v-for="student in onSearch" :key="student.id" v-if="student">
        <a v-bind:href="'/student/' + student.id" class="list-group-item list-group-item-action">
          {{student.student_id}} - {{student.first_name}} {{student.prefix}} {{student.last_name}} - 
          {{student.cohort.name}} - {{student.progress.toFixed(0)}}%
        </a>
  </div>
  </div>
</div>
</div>
</template>
<script>
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";

export default {
  name: "studentlist",
  data: function() {
    return {
      students: [],
      sortedStudents: [],
      criteria: "Firstname",
      order: "asc",
      search: ""
    };
  },
  components: {
    FontAwesomeIcon
  },
  created: function() {
    this.getStudents();
  },
  methods: {
    getStudents: function() {
      axios.get("/api/student").then(res => {
        this.students = res.data;
        this.sortedStudents = this.students;
      });
    }
  },
  computed: {
    //checks in which order it must be shown with criteria
    //then checks if the search term string is included in the list
    onSearch: function() {
      var vm = this;
      var obj = [];
      if (vm.criteria === "Firstname") {
        return _.orderBy(this.students, "first_name", vm.order);
      } else if (vm.criteria === "Lastname") {
        return _.orderBy(this.students, "last_name", vm.order);
      } else if (vm.criteria === "OVNumber") {
        return _.orderBy(this.students, "student_id", vm.order);
      } else if (vm.criteria === "Cohort") {
        return _.orderBy(this.students, "cohort.name", vm.order);
      } else if (vm.criteria === "Progress") {
        return _.orderBy(this.students, "progress", vm.order);
      }
    }
  }
};
</script>
