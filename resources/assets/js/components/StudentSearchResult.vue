<template>
  <div class="container">
    <div   
      v-for="student in students" 
      v-if="student"
      :key="student.id"
      class="list-group mt-3 text-center col-sm"
    >
      <a 
        :href="'/student/' + student.id" 
        class="list-group-item list-group-item-action"
      >
        {{ student.student_id }} - {{ student.first_name }} {{ student.prefix }} {{ student.last_name }} - 
        {{ student.cohort.name }} - {{ student.progress.toFixed(0) }}%
      </a>
    </div>
    <div v-if="!Array.isArray(students) || !students.length">
      <p>Er geen student gevonden</p>
    </div>
  </div>
</template>
<script>
export default {
  name: "Studentsearchresult",
  data: function() {
    return {
      students: []
    };
  },
  created: function() {
    this.getStudents();
  },
  methods: {
    getStudents: function() {
      axios
        .get(
          "/api/student/" +
            _.last(window.location.pathname.split("/")) +
            "/search"
        )
        .then(res => {
          this.students = res.data;
        });
    }
  }
};
</script>
