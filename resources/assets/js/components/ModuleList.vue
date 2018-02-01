<template>
<div class="container">
  <div class="row" v-for="module in filteredModules" :key="module.id" v-if="module">
    <div class="col-8">
      <div class="card bg-light mt-3">
        <div class="card-header">{{module.name}}</div>
        <div class="card-body">
          <p class="card-text">{{module.sub_description}}</p>
        </div>
        <div class="card-footer bg-transparent border-success">
          <a data-toggle="collapse" :href='"#collapse" + module.id'  aria-expanded="true" :aria-controls='"collapse" + module.id'>
            Uitleg
          </a>
          <div class="float-right"><a v-bind:href="'/module/' + module.id" role="button" class="btn btn-primary">Wijzigen</a>
          <button v-if="!module.used" v-on:click="deleteModule(module.id)" role="button" class="btn btn-danger">Verwijderen</button>
          </div>
          <div :id="'collapse' + module.id" class="collapse" role="tabpanel" aria-labelledby="heading" data-parent="#accordion">
            <div class="card-body">
              <medium-editor :text='module.long_description' :options='options'>
              </medium-editor>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-2 my-auto">
      <div class="card text-center">
        <div class="card-body">
          <p class="card-text">{{module.week_duration / 8}} periode</p>
          <p class="card-text">=</p>
          <p class="card-text">{{module.week_duration}} weken</p>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import editor from "vue2-medium-editor";
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";

window.$ = window.jQuery = require("jquery");

export default {
  name: "modulelist",
  data: function() {
    return {
      modules: [],
      options: { disableEditing: true, toolbar: false, placeholder: false }
    };
  },
  props: ["cohort"],
  components: {
    "medium-editor": editor,
    FontAwesomeIcon
  },
  created: function() {
    this.getModules();
  },
  methods: {
    getModules: function() {
      axios.get("/api/module").then(res => {
        this.modules = res.data;
      });
    },
    deleteModule: function(id) {
      axios.delete("/api/module/" + id).then(res => {
        this.getModules();
      });
    }
  },
  computed: {
    filteredModules: function() {
      if(this.cohort == "All"){
        return this.modules;
      }
      return _.filter(this.modules, { cohorts: [ { name: this.cohort } ]});
    }
  }
};
</script>
