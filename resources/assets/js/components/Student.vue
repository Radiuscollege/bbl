<template>
<div class="container">
  <div v-if="error" class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Waarschuwing</h5>
      </div>
      <div class="modal-body">
        Deze student bestaat waarschijnlijk niet (meer).
      </div>
    </div>
  </div>
  <studentform :studentInfo="studentInfo"></studentform>
  <hr>
  <div class="text-center mb-5 d-print-none">
    <a v-on:click="showModules = true" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Modules</a>
    <a v-on:click="showModules = false" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Statistieken</a>
  </div>
  <div v-if="showModules">
    <markmodal v-if="modalVisible" v-on:close="closeModal" :studentModule="modalData"></markmodal>
    <div v-if="studentInfo.cohort" v-for="(module, index) in studentInfo.cohort.modules" :key="module.id" class="row pt-3 pb-3" style="border-bottom: 1px solid #ccc;">
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
          <div v-if="!module.student_modules[0] || module.student_modules[0].pass == false && module.student_modules[0].mark === null" class="card-body">
            <p class="card-text">&nbsp;</p>
            <button type="button" class="btn btn-primary d-print-none" v-on:click="openModal(module)">
              Accorderen
            </button>
            <p class="card-text">&nbsp;</p>
          </div>
          <div v-else-if="module.student_modules[0]" class="card-body pt-2">
            <p v-if="module.student_modules[0].teacher">
              ✓ {{module.student_modules[0].teacher}}
            </p>
            <p v-if="module.student_modules[0].mark">
              {{module.student_modules[0].mark}}
            </p>
            <p v-else-if="module.student_modules[0].approved_by">
              {{module.student_modules[0].approved_by}}
            </p>
            <button type="button" class="btn btn-primary d-print-none" v-on:click="openModal(module)">
              Accorderen
            </button>
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
  </div>
  <div class="row" v-else>
    <div class="col-md-4">
      <h4>Opleiding voortgang</h4>
      <studentprogresschart class="h-25" :student="studentInfo"></studentprogresschart>
    </div>
    <div class="col">
      <h4>Cijfers</h4>
      <studentchart :student="studentInfo"></studentchart>
    </div>
  </div>
</div>
</template>
<script>
import editor from "vue2-medium-editor";
import markmodal from "./MarkModal";
import studentchart from "./StudentChart";
import studentprogresschart from "./StudentProgressChart";

export default {
  name: "student",
  data: function() {
    return {
      studentInfo: [],
      options: { disableEditing: true, toolbar: false, placeholder: false },
      modalVisible: false,
      modalData: null,
      showModules: true,
      error: false
    };
  },
  components: {
    "medium-editor": editor,
    markmodal
  },
  created: function() {
    this.getModules();
  },
  methods: {
    getModules: function() {
      axios
        .get(
          "/api/student/" + _.last(window.location.pathname.split("/")) + "/studentmodule"
        )
        .then(res => {
          this.studentInfo = res.data;
          this.studentInfo.cohort.modules = _.orderBy(this.studentInfo.cohort.modules, 'student_modules[0]', 'asc');
        })
        .catch(err => {
          this.error = true;
        });
    },
    showPopup: function() {
      this.show = true;
    },
    //Open the modal, tries to give existing data, otherwise gives default data with the current date.
    openModal: function(module) {
      if (module.student_modules[0]) {
        this.modalData = {
          id: module.id,
          mark: module.student_modules[0].mark,
          pass: null,
          beginDate: module.student_modules[0].begin_date,
          finishDate: module.student_modules[0].finish_date,
          note: module.student_modules[0].note
        };
        if (module.student_modules[0].mark == null && module.student_modules[0].pass == true){
          this.modalData.pass = true;
        }
        else {
          this.modalData.pass = false;
        }
        if (!module.student_modules[0].begin_date){
          this.modalData.beginDate = new Date();
        }
        if (!module.student_modules[0].finish_date){
          this.modalData.finishDate = new Date();
        }
      } else {
        this.modalData = {
          id: module.id,
          mark: null,
          pass: false,
          beginDate: new Date(),
          finishDate: new Date()
        };
      }
      this.modalVisible = true;
    },
    beganModule: function(moduleID, toggle) {
      axios
        .put("/api/studentmodule/" + moduleID + "/toggle", {
          student: _.last(window.location.pathname.split("/")),
          began: toggle
        })
        .then(res => this.getModules());
    },
    closeModal: function() {
      this.getModules();
      this.modalVisible = false;
    }
  }
};
</script>
