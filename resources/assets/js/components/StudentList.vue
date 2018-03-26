<template>
  <div class="container">
    <div class="row align-items-start">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Sorteer</h5>
          <div class="form-check">
            <input 
              id="firstname" 
              v-model="criteria"
              class="form-check-input" 
              type="radio" 
              value="Firstname" 
            >
            <label class="form-check-label">
              Voornaam
            </label>
          </div>
          <div class="form-check">
            <input 
              id="lastname" 
              v-model="criteria"
              class="form-check-input" 
              type="radio" 
              value="Lastname" 
            >
            <label class="form-check-label">
              Achternaam
            </label>
          </div>
          <div class="form-check">
            <input 
              id="ovnumber" 
              v-model="criteria"
              class="form-check-input" 
              type="radio" 
              value="OVNumber" 
            >
            <label class="form-check-label">
              OV-nummer
            </label>
          </div>
          <div class="form-check">
            <input 
              id="cohort" 
              v-model="criteria"
              class="form-check-input" 
              type="radio" 
              value="Cohort"
            >
            <label class="form-check-label">
              Cohort
            </label>
          </div>
          <div class="form-check">
            <input 
              id="progress" 
              v-model="criteria"
              class="form-check-input" 
              type="radio" 
              value="Progress" 
            >
            <label class="form-check-label">
              Voortgang
            </label>
          </div>
          <br>
          <label class="btn btn-secondary">
            <input 
              id="ascend" 
              v-model="order" 
              type="radio" 
              value="asc"
            > 
            Oplopend
          </label>
          <label class="btn btn-secondary">
            <input 
              id="descend" 
              v-model="order" 
              type="radio" 
              nam 
              value="desc"
            > 
            Aflopend
          </label>
        </div>
      </div>
      <div class="col-sm-6">
        <div 
          v-for="student in onSearch"  
          v-if="student"
          :key="student.id" 
          class="list-group mb-3 text-center"
        >
          <a 
            :href="'/student/' + student.id" 
            class="list-group-item list-group-item-action"
          >
            {{ student.student_id }} - {{ student.first_name }} {{ student.prefix }} {{ student.last_name }} - 
            {{ student.cohort.name }} - {{ student.progress.toFixed(0) }}%
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "Studentlist",
  data: function() {
    return {
      students: [],
      sortedStudents: [],
      criteria: "Firstname",
      order: "asc",
      search: ""
    };
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
  }
};
</script>
