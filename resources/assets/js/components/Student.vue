<template>
<div class="container">
  <div class="text-center mb-5">
    <a v-on:click="showModules = true" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Modules</a>
    <a v-on:click="showModules = false" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Statistieken</a>
  </div>
  <div>
    <markmodal v-if="modalVisible" v-on:close="closeModal" :studentModule="modalData"></markmodal>
    <div v-if="moduleList.cohort" v-for="module in moduleList.cohort.modules" :key="module.id" class="row card-columns border border-primary rounded">
      <div class="col-4">
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
        <div class="card text-center h-25">
          <div class="card-body">
            <p class="card-text">{{module.week_duration / 8}} periode</p>
            <p class="card-text">=</p>
            <p class="card-text">{{module.week_duration}} weken</p>
          </div>
        </div>
      </div>
      <div class="col-2">
        <div class="card text-center h-25">
          <div v-if="module.student_modules[0]" class="card-body">
            <p class="card-text">Begin datum</p>
            <p class="card-text">{{module.student_modules[0].begin_date}}</p>
          </div>
        </div>
      </div>
      <div class="col-2">
        <div class="card text-center h-25">
          <div v-if="module.student_modules[0]" class="card-body">
            <p class="card-text">{{module.student_modules[0].finish_date}}</p>
            <p class="card-text">Geschatte einddatum</p>
            <p class="card-text">{{module.student_modules[0].expected_date}}</p>
          </div>
        </div>
      </div>
      <div class="col-2">
        <div class="card text-center h-25">
          <div v-if="!module.student_modules[0] || module.student_modules[0].pass == false && module.student_modules[0].mark === null" class="card-body">
            <button type="button" class="btn btn-primary" v-on:click="openModal(module)">
              Accorderen
            </button>
          </div>
          <div v-else-if="module.student_modules[0]" class="card-body">
            <p>
              ✓
            </p>
            <p v-if="module.student_modules[0].mark">
              {{module.student_modules[0].mark}}
            </p>
            <p v-else-if="module.student_modules[0].pass">
              {{module.student_modules[0].approved_by}}
            </p>
            <button type="button" class="btn btn-primary" v-on:click="openModal(module)">
              Accorderen
            </button>
          </div>
        </div>
      </div>
      <div class="col-2">
        <div v-if="module.student_modules[0]" class="card text-center h-25">
          <div v-if="!module.student_modules[0].pass" class="card-body">
            <input v-model="module.student_modules[0].began" v-on:click="beganModule(module.id, !module.student_modules[0].began)" type="checkbox" id="checkbox" :value="module.id">
            <label for="checkbox">Gestart?</label>
          </div>
          <div v-else-if="module.student_modules[0].note" class="card-body">
            <p class="card-text">{{module.student_modules[0].note}}</p>
          </div>
        </div>
        <div v-else class="card text-center h-25">
          <div class="card-body">
            <input v-on:click="beganModule(module.id, true)" type="checkbox" id="checkbox" :value="module.id">
            <label for="checkbox">Gestart?</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import editor from "vue2-medium-editor";
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";
import markmodal from "./MarkModal";

export default {
  name: "student",
  data: function() {
    return {
      moduleList: [],
      options: { disableEditing: true, toolbar: false, placeholder: false },
      modalVisible: false,
      modalData: null,
      showModules: true
    };
  },
  components: {
    "medium-editor": editor,
    FontAwesomeIcon,
    markmodal
  },
  created: function() {
    this.getModules();
  },
  methods: {
    getModules: function() {
      axios
        .get(
          "/api/studentmodule/" + _.last(window.location.pathname.split("/"))
        )
        .then(res => {
          this.moduleList = res.data;
        })
        .catch(error => {
          console.log("Er is iets foutgegaan tijdens het ophalen van de data.");
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
        .post("/api/studentmodule/toggle/" + moduleID, {
          student: _.last(window.location.pathname.split("/")),
          began: toggle
        })
        .then(res => {});
    },
    closeModal: function() {
      this.getModules();
      this.modalVisible = false;
    }
  }
};
</script>
