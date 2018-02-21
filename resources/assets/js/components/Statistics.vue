<template>
  <div class="container">
    <h1>Cohort info</h1>
    <button v-on:click="print" class="btn btn-primary float-right d-print-none">Uitprinten</button>
    <div class="row">
      <div class="card my-auto col-6">
        <ul class="list-group list-group-flush text-center">
          <li class="list-group-item">
            Er zijn momenteel {{statistics.student_amount}} studenten met de AMO opleiding bezig
          </li>
          <li v-for="cohort in statistics.cohorts" v-bind:key="cohort.id" class="list-group-item">
            {{cohort.student_cohort_count}}
            <span v-if="cohort.student_cohort_count == 1">
              student zit in {{cohort.name}}
            </span> 
            <span v-else>
              studenten zitten in {{cohort.name}}
            </span> 
            <popper v-if="cohort.students" trigger="click" :options="{placement: 'right'}">
              <div class="popper">
                <a v-for="student in cohort.students" :key="student.id" :href="'/student/' + student.id" v-if="!student.approved_by">
                  {{student.first_name + (student.prefix == null ? " " : " " + student.prefix + " ")  + student.last_name}}
                  <br>
                </a>
              </div>
              <button v-if="cohort.student_cohort_count != 0" type="button" class="btn btn-outline-dark" slot="reference">Toon</button>
            </popper>
          </li>
        </ul>
      </div>
      <cohortchart class="col-6" v-if="statistics.cohorts" :cohortInfo="statistics.cohorts"></cohortchart>
    </div>
    <hr>
    <h1>Studenten aantal per module</h1>
    <studentamountchart v-if="statistics.module_info" :moduleInfo="statistics.module_info"></studentamountchart>
    <hr>
    <h1>Module info</h1>
    <div v-if="module" v-for="module in statistics.module_info" :key="module.id" class="row pt-3 pb-3" style="border-bottom: 1px solid #ccc;">
      <div class="col-2 my-auto">
        <div class="card bg-light">
          <div class="card-header text-center p-2">{{module.name}}</div>
            <div class="card-body p-2">
              <p class="card-text text-center">{{module.sub_description}}</p>
            </div>
        </div>
      </div>
      <div class="col-2 my-auto">
        <div class="card text-center">
          <div class="card-header text-center p-2">Duur in weken</div>
          <div class="card-body">
            <p class="card-text">Geschat: {{module.week_duration}}</p>
            <p v-if="module.avg_duration" class="card-text">Gemiddeld: {{module.avg_duration.toFixed(1)}}</p>
          </div>
        </div>
      </div>
      <div class="card my-auto">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            {{module.finished_modules_count}}
            <span v-if="module.finished_modules_count == 1">
              student heeft deze module afgerond
            </span> 
            <span v-else>
              studenten hebben deze module afgerond
            </span> 
            <popper v-if="module.finished_modules_count > 0" trigger="click" :options="{placement: 'right'}">
              <div class="popper">
                <a v-for="studentModule in module.student_modules" :key="studentModule.id" :href="'/student/' + studentModule.student_id" v-if="studentModule.approved_by">
                  {{studentModule.student.first_name + (studentModule.student.prefix == null ? " " : " " + studentModule.student.prefix + " ")  + studentModule.student.last_name}}
                  <br>
                </a>
              </div>
              <button type="button" class="btn btn-outline-dark" slot="reference">Toon</button>
            </popper>
          </li>
          <li class="list-group-item">
            {{module.student_modules_count}}
            <span v-if="module.student_modules_count == 1">
              student is met deze module bezig.
            </span> 
            <span v-else>
              studenten zijn met deze module bezig.
            </span> 
            <popper v-if="module.student_modules_count > 0" trigger="click" :options="{placement: 'right'}">
              <div class="popper">
                <a v-for="studentModule in module.student_modules" :key="studentModule.id" :href="'/student/' + studentModule.student_id" v-if="!studentModule.approved_by">
                  {{studentModule.student.first_name + (studentModule.student.prefix == null ? " " : " " + studentModule.student.prefix + " ")  + studentModule.student.last_name}}
                  <br>
                </a>
              </div>
              <button type="button" class="btn btn-outline-dark" slot="reference">Toon</button>
            </popper>
          </li>
          <li v-if="module.avg_mark" class="list-group-item">Het gemiddelde cijfer is een {{module.avg_mark.toFixed(1)}}</li>
        </ul>
      </div>
    </div>
    <studentaveragechart></studentaveragechart>
  </div>
</template>
<script>
import Popper from 'vue-popperjs';
import 'vue-popperjs/dist/css/vue-popper.css';
import markmodal from "./MarkModal";
import studentamountchart from "./StudentAmountChart";

export default {
  name: "statistics",
  data: function() {
    return {
      statistics: []
    };
  },
  components: {
    'popper': Popper,
    markmodal
  },
  created: function() {
    this.getStatistics();
  },
  methods: {
    getStatistics: function() {
      axios
        .get("/api/statistics")
        .then(res => {
          this.statistics = res.data;
        })
        .catch(error => {
          console.log("Er is iets foutgegaan tijdens het ophalen van de data.");
        });
    },
    print: function() {
      window.print();
    }
  }
};
</script>
