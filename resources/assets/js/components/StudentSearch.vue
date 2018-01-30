<template>
<div class="float-right">
  <div class="input-group pt-3 pr-3">
		<input v-model="search" type="text" class="form-control" placeholder="Zoek voor student...">
    <div class="input-group-append">
      <button v-on:click="searchStudent" class="btn btn-primary"><i class="fas fa-search"></i></button>
    </div>
  </div>
</div>
</template>
<script>
export default {
  name: "studentsearch",
  data: function() {
    return {
      search: ""
    };
  },
  methods: {
    searchStudent: function() {
      window.location.href = "../student/search/" + this.search;
    }
  }
};
</script>
