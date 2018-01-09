<template>
<div class="container">
<span v-html="errorModal"></span>
<div class="row" v-for="module in moduleList[0].modules" :key="module.id" v-if="moduleList[0]">
  <div class="col-8">
    <div class="card bg-light mb-3">
      <div class="card-header">{{module.name}}</div>
        <div class="card-body">
          <p class="card-text">{{module.sub_description}}</p>
        </div>
      <div class="card-footer bg-transparent border-success">
        <a data-toggle="collapse" :href='"#collapse" + module.id'  aria-expanded="true" :aria-controls='"collapse" + module.id'>
          Uitleg
        </a>
        <div :id="'collapse' + module.id" class="collapse" role="tabpanel" aria-labelledby="heading" data-parent="#accordion">
          <div class="card-body">
            <medium-editor :text='module.long_description' :options='options'>
            </medium-editor>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-2">
    <div class="card text-center">
      <div class="card-body">
        <p class="card-text">{{module.week_duration / 8}} periode</p>
        <p class="card-text">=</p>
        <p class="card-text">{{module.week_duration}} weken</p>
      </div>
    </div>
  </div>
  <div class="col-2">
    <div class="card text-center">
      <div class="card-body">
        <input type="checkbox" id="checkbox" :value="module.id" v-model="module.student_modules[0].began" v-on:click="beganModule(module.id, !module.student_modules[0].began)">
        <label for="checkbox">Begonnen?</label>
      </div>
    </div>
  </div>
</div>
</div>
</template>
<script>
import editor from "vue2-medium-editor";
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";

export default {
  name: "studentlist",
  data: function() {
    return {
      moduleList: [],
      errorModal: null
    };
  },
  components: {
    "medium-editor": editor,
    FontAwesomeIcon
  },
  created: function() {
    this.getModules();
  },
  methods: {
    getModules: function() {
      axios
        .get("/api/studentmodule")
        .then(res => {
          this.moduleList = res.data;
        })
        .catch(error => {
          this.errorModal =
            '<div class="modal-dialog" role="document">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h5 class="modal-title">Waarschuwing</h5>' +
            "</div>" +
            '<div class="modal-body">' +
            "Je hebt nog geen account hier." +
            " Neem contact op met je studieloopbaanbegeleider" +
            "</div>" +
            "</div>" +
            "</div>";
        });
    },
    beganModule: function(moduleID, toggle) {
      axios
        .post("/api/studentmodule/toggle/" + moduleID, { began: toggle })
        .then(res => {});
    }
  }
};
</script>
