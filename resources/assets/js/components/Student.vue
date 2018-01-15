<template>
  <div class="container">
    <markmodal v-if="modalVisible" v-on:close="modalVisible = false" :studentModule="modalData"></markmodal>
    <div v-if="moduleList.cohort" v-for="module in moduleList.cohort.modules" :key="module.id" class="row">
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
        <div class="card text-center">
          <div class="card-body">
            <p class="card-text">{{module.week_duration / 8}} periode</p>
            <p class="card-text">=</p>
            <p class="card-text">{{module.week_duration}} weken</p>
          </div>
        </div>
      </div>
      <div v-if="!module.student_modules.mark || !module.student_modules.user" class="col-2">
        <div class="card text-center">
          <div class="card-body">
          <button type="button" class="btn btn-primary" v-on:click="openModal(module.student_modules); console.log(module.student_modules[0]);">
            Accorderen
          </button>
          </div>
        </div>
      </div>
      <div v-else class="col-2">
        <div class="card text-center">
          <div class="card-body">
            <div class="card-body">
              <p v-if="module.student_modules.mark" class="card-text">
                {{module.student_modules.mark}}
                ✓
              </p>
              <p v-else class="card-text">
                {{module.student_modules.user}}
              </p>
              <p>
                ✓
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-2">
        <div class="card text-center">
          <div class="card-body">
            <input v-if="module.student_modules.began" v-model="module.student_modules.began" :value="module.id" type="checkbox" id="checkbox">
            <input v-else type="checkbox" id="checkbox" :value="module.id">
            <label for="checkbox">Begonnen?</label>
          </div>
          <p class="card-text"> </p>
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
      studentInfo: [],
      pass: false,
      options: { disableEditing: true, toolbar: false },
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
    this.getStudent();
    this.getModules();
  },
  methods: {
    getStudent: function() {
      axios
        .get("/api/student/" + _.last(window.location.pathname.split("/")))
        .then(res => {
          this.studentInfo = res.data;
        })
        .catch(error => {
          console.log("Er is iets foutgegaan tijdens het ophalen van de data.");
        });
    },
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
    setMark: function(moduleID, mark, passed) {
      axios
        .post("/api/studentmodule/mark/" + moduleID, {
          mark: mark,
          pass: passed
        })
        .then(res => {})
        .catch(err => console.error(err));
      this.pass = false;
    },
    showPopup: function() {
      this.show = true;
    },
    checkMark: function(array, id) {
      try {
        return array.find(o => o.module_id === id).mark !== null;
      } catch (e) {
        return false;
      }
    },
    checkApprovedBy: function(array, id) {
      try {
        return array.find(o => o.module_id === id).approved_by !== null;
      } catch (e) {
        return false;
      }
    },
    getValue: function(array, id) {
      try {
        return array.find(o => o.module_id === id);
      } catch (e) {
        return false;
      }
    },
    openModal: function(data) {
      this.modalData = data;
      this.modalVisible = true;
    }
  }
};
</script>
