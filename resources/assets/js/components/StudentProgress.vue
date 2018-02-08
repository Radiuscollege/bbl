<template>
<div class="container">
  <div v-if="error" class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Waarschuwing</h5>
      </div>
      <div class="modal-body">
        Je hebt waarschijnlijk nog geen account hier.
        Neem contact op met je studieloopbaanbegeleider
      </div>
    </div>
  </div>
<studentform :studentInfo="moduleList" :isStudent="true"></studentform>
<hr>
  <div class="text-center mb-5 d-print-none">
    <a v-on:click="showModules = true" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Modules</a>
    <a v-on:click="showModules = false" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Statistieken</a>
  </div>
  <div v-if="showModules">
    <div v-if="moduleList[0]" v-for="(module, index) in moduleList[0].modules" :key="module.id" class="row pt-3 pb-3" style="border-bottom: 1px solid #ccc;">
      <div class="col-2 my-auto">
        <h4 v-if="index == 0" class="text-center">Naam</h4>
        <div class="card bg-light">
          <div class="card-header text-center p-2">{{module.name}}</div>
            <div class="card-body p-2">
              <p class="card-text text-center">{{module.sub_description}}</p>
            </div>
          <div class="card-footer bg-transparent border-success">
            <a data-toggle="collapse" :href='"#collapse" + module.id'  aria-expanded="true" :aria-controls='"collapse" + module.id'>
              Uitleg
            </a>
            <div :id="'collapse' + module.id" class="collapse" role="tabpanel" aria-labelledby="heading" data-parent="#accordion">
              <div class="card-body p-0">
                <medium-editor :text='module.long_description' :options='options'>
                </medium-editor>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-2 my-auto">
        <h4 v-if="index == 0" class="text-center">Duur</h4>
        <div class="card text-center">
          <div class="card-body">
            <p class="card-text">{{module.week_duration / 8}} periode</p>
            <p class="card-text">=</p>
            <p class="card-text">{{module.week_duration}} weken</p>
          </div>
        </div>
      </div>
      <div class="col-2 my-auto">
        <h4 v-if="index == 0" class="text-center">Begindatum</h4>
        <div class="card text-center">
          <div v-if="module.student_modules[0] && module.student_modules[0].begin_date" class="card-body">
            <p class="card-text">&nbsp;</p>
            <p class="card-text">{{module.student_modules[0].begin_date}}</p>
            <p class="card-text">&nbsp;</p>
          </div>
          <div v-else class="card-body">
            <p class="card-text">&nbsp;</p>
            <p class="card-text">&nbsp;</p>
            <p class="card-text">&nbsp;</p>
          </div>
        </div>
      </div>
      <div class="col-2 my-auto">
        <h4 v-if="index == 0" class="text-center">Einddatum</h4>
        <div class="card text-center">
          <div v-if="module.student_modules[0]" class="card-body">
            <p class="card-text">{{module.student_modules[0].finish_date}}</p>
            <p v-if="module.student_modules[0].begin_date" class="card-text">Geschatte einddatum: 
              {{module.student_modules[0].expected_date}}
            </p>
            <div v-else class="card-text">
              <p>&nbsp;</p>
              <p>&nbsp;</p>
            </div>
          </div>
          <div v-else class="card-body">
            <p class="card-text">&nbsp;</p>
            <p class="card-text">&nbsp;</p>
            <p class="card-text">&nbsp;</p>
          </div>
        </div>
      </div>
      <div class="col-2 my-auto">
        <h4 v-if="index == 0" class="text-center">Beoordeling</h4>
        <div class="card text-center">
          <div v-if="module.student_modules[0]" class="card-body pt-2">
            <p v-if="module.student_modules[0].teacher">
              ✓ {{module.student_modules[0].teacher}}
            </p>
            <p v-if="module.student_modules[0].mark">
              {{module.student_modules[0].mark}}
            </p>
            <div v-else class="card-body">
              <p class="card-text">&nbsp;</p>
              <p class="card-text">&nbsp;</p>
            </div>
          </div>
          <div v-else class="card-body">
            <p class="card-text">&nbsp;</p>
            <p class="card-text">&nbsp;</p>
          </div>
        </div>
      </div>
      <div class="col-2 my-auto">
        <div v-if="module.student_modules[0]" class="card text-center">
          <div v-if="!module.student_modules[0].pass" class="card-body">
            <p class="card-text">&nbsp;</p>
            <input v-model="module.student_modules[0].begin_date" v-on:click="beganModule(module.id, !module.student_modules[0].begin_date)" type="checkbox" id="checkbox" :value="module.id">
            <label for="checkbox">Gestart?</label>
            <p class="card-text">&nbsp;</p>
          </div>
          <div v-else-if="module.student_modules[0].note" class="card-body p-3">
            <textarea v-model="module.student_modules[0].note" class="form-control" rows="4" id="comment" disabled></textarea>
          </div>
          <div v-else>
            <p class="card-text">&nbsp;</p>
            <p class="card-text">&nbsp;</p>
            <p class="card-text">&nbsp;</p>
            <p class="card-text">&nbsp;</p>
          </div>
        </div>
        <div v-else class="card text-center">
          <div class="card-body">
            <p class="card-text">&nbsp;</p>
            <input v-on:click="beganModule(module.id, true)" type="checkbox" id="checkbox" :value="module.id">
            <label for="checkbox">Gestart?</label>
            <p class="card-text">&nbsp;</p>
          </div>
        </div>   
      </div>
    </div>
  <div v-else>
    <studentchart :student="moduleList" :isStudent="true"></studentchart>
  </div>
</div>
</div>
</template>
<script>
import editor from "vue2-medium-editor";
import studentchart from "./StudentChart";

export default {
  name: "studentlist",
  data: function() {
    return {
      moduleList: [],
      error: false,
      checked: false,
      options: { disableEditing: true, toolbar: false, placeholder: false },
      showModules: true
    };
  },
  components: {
    "medium-editor": editor,
    studentchart
  },
  created: function() {
    this.getModules();
  },
  methods: {
    getModules: function() {
      axios
        .get("/api/student/studentmodule")
        .then(res => {
          this.moduleList = res.data;
          this.moduleList[0].modules = _.orderBy(this.moduleList[0].modules, 'student_modules[0]', 'asc');
        })
        .catch(err => {
          this.error = true;
        });
    },
    beganModule: function(moduleID, toggle) {
      axios
        .put("/api/studentmodule/" + moduleID + "/toggle", { began: toggle })
        .then(res => this.getModules());
    }
  }
};
</script>
