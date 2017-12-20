<template>
<div class="container">
<div class="list-group mb-3 text-center col-sm" v-for="student in students" :key="student.id" v-if="student">
      <a v-bind:href="'/student/' + student.id" class="list-group-item list-group-item-action">{{student.student_id}} - {{student.first_name}} {{student.prefix}} {{student.last_name}} - {{student.cohort.name}}</a>
      
</div>
</div>
</template>
<script>
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";

export default {
  name: "studentlist",
  data: function() {
    return {
      students: []
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
      });
    },
  }
};
</script>
