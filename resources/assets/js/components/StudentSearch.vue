<template>
  <div class="float-right">
    <div class="input-group pt-3 pb-3 pr-3">
      <input 
        v-model="search" 
        type="text" 
        class="form-control" 
        placeholder="Zoek voor student..."
        @keyup.enter="searchStudent"
      >
      <div class="input-group-append">
        <button 
          class="btn btn-primary"
          @click="searchStudent"
        >
          <i class="fas fa-search" />
        </button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "Studentsearch",
  data: function() {
    return {
      search: ""
    };
  },
  methods: {
    searchStudent: function() {
      document.location.href = "../../student/search/" + this.search;
    }
  }
};
</script>
