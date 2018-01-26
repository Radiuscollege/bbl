<template>
<div class="col-lg-3 float-right">
	<div class="input-group">
		<input v-model="search" type="text" class="form-control" placeholder="Zoek voor student...">
		<span class="input-group-btn">
      <button v-on:click="searchStudent" class="btn btn-default"><i class="fas fa-search"></i></button>
		</span>
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
      document.location.href = '/student/search' + this.search;
    },
  }
};
</script>
