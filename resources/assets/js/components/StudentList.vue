<template>
<div class="container">
  <div class="col-lg-3 float-right">
	  <div class="input-group">
	  	<input v-model="search" type="text" class="form-control" placeholder="Zoek voor student...">
	  	<span class="input-group-btn"></span>
	  </div>
  </div>
  <div class="card" style="width: 18rem;">
    <div class="card-body">
      <h5 class="card-title">Filter</h5>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="firstname" value="Firstname" v-model="criteria">
        <label class="form-check-label">
          Voornaam
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="lastname" value="Lastname" v-model="criteria">
        <label class="form-check-label">
          Achternaam
        </label>
      </div>
          <div class="form-check">
        <input class="form-check-input" type="radio" id="ovnumber" value="OVNumber" v-model="criteria">
        <label class="form-check-label">
          OV-nummer
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" id="cohort" value="Cohort" v-model="criteria">
        <label class="form-check-label">
          Cohort
        </label>
      </div>
          <div class="form-check">
        <input class="form-check-input" type="radio" id="progress" value="Progress" v-model="criteria">
        <label class="form-check-label">
          Voortgang
        </label>
      </div>
      <br>
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-secondary">
          <input v-model="order" type="radio" name="options" id="ascend" value="asc" autocomplete="off"> Oplopend
        </label>
        <label class="btn btn-secondary">
          <input v-model="order" type="radio" name="options" id="descend" value="desc" autocomplete="off"> Aflopend
        </label>
      </div>
    </div>
  </div>
  <div class="list-group mt-3 text-center col-sm" v-for="student in sortedStudents" :key="student.id" v-if="student">
        <a v-bind:href="'/student/' + student.id" class="list-group-item list-group-item-action">
          {{student.student_id}} - {{student.first_name}} {{student.prefix}} {{student.last_name}} - 
          {{student.cohort.name}} - {{student.progress.toLocaleString()}}%
        </a>
  </div>
</div>
</template>
<script>
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";

export default {
  name: "studentlist",
  data: function() {
    return {
      students: [],
      criteria: "",
      order: "asc",
      search: ""
    };
  },
  components: {
    FontAwesomeIcon
  },
  created: function() {
    this.getStudents();
  },
  methods: {
    getStudents: function() {
      axios.get("/api/student").then(res => {
        this.students = res.data;
      });
    }
  },
  computed: {
    //checks in which order it must be shown with criteria
    //then checks if the search term string is included in the list
    sortedStudents: {
      cache: false,
      get: function() {
      var vm = this;
      var obj = [];
      if (vm.criteria === "") {
        var list = _.orderBy(vm.students, "first_name");
        
        list.forEach(function(d) {
          //return Object.keys(d).map(e => d[e].toLowerCase().includes(vm.search.toLowerCase()));
          

          //wacht ik heb natuurlijk ook nog de cohort object, die kan neit toLowerCase
          Object.keys(d).forEach(function (key){
            if (_.isString(d[key]))
            {
              if (d[key].toLowerCase().includes(vm.search.toLowerCase()))
              {
                obj.push(d);
              }
            }
          });
          console.log(obj);
          return obj;
          //filter is ook nog een soort van foreach, kijk of ik die kan verwijderen
          //het probleem is nu namelijk dat de laatste leeg is..., hij moet
          //de obj onthouden en niet opnieuw leeg maken 
          //this blijft ook niet, moet vooral this houden.
          //Alleen als er een letter van de search is veranderd, moet obj weer leeg.
          //of ik zorg ervoor dat de laatste die die teruggeeft niet leeg is maar dit is gwn zo.
          //wacht hij is gwn vol nu XD maar hij laat niks zien ofzo.
            //punt is ook het geeft herhaaldelijk een object terug, dit is dus de allerlaatste
            //in de foreach, ik zou het nogsteeds in een array moeten doen maar wel eentje dat die ook
            //echt de student[0]. kan doen.
            //misschien is het omdat de return niks returns, als ik return bij object.keys toevoeg
            //veranderd er alleen niks
            //dus het geeft dingen terug maar alleen true en false, maar dat staat ook eigenlijk dat 
            //die dat doet maar als ik het los doet doetie t ook
          
          //met de d first name
          //de foreach loop werkt dus gewoon niet....
          // return d["first_name"].toLowerCase().includes(vm.search.toLowerCase()); dit werkt wel maar de bovenste niet hmmmmmmmmmm. het is gewoon hetzlefde alleen geeft
          //die de value van bijvoorbeeld de ID direct xD het is of omdat het een integer is, of omdat die niet daadwerkelijk checked in de array maar puur de value
          //en een value heeft natuurlijk neit includes enz toch..... zou wel meoten xD
        });
      }
       else if (vm.criteria === "Firstname") {
        return _.orderBy(
          d["first_name"].toLowerCase().includes(vm.search.toLowerCase()),
          "first_name",
          vm.order
        );
      } else if (vm.criteria === "Lastname") {
        return _.orderBy(vm.students, ["last_name"], ["asc"]);
      } else if (vm.criteria === "OVNummer") {
        return _.orderBy(
          d["student_id"].toLowerCase().includes(vm.search.toLowerCase()),
          "student_id",
          vm.order
        );
      } else if (vm.criteria === "Cohort") {
        return _.orderBy(
          d["cohort"].toLowerCase().includes(vm.search.toLowerCase()),
          "cohort.name",
          vm.order
        );
      } else if (vm.criteria === "Voortgang") {
        return _.orderBy(d, "progress", order);
      }
      //kan bijna de if hetzelfde als de first name maken en dan gewoon een foreach doen,...
      //return _.orderBy(vm.students, 'first_name');

      //ik kan eigenlijk gewoon een for loop doen door elke [""], bij een goede en als die returned dan is het al goed eigenlijk.
      //Dan moet ik nog wel de orderby goed hebben, misschien door de filter werkt t niet
      //return v[0]; })) > -1 MAP IT, check if it does something, return the true ones and dont return
      //something empty back cause if the last one isnt good then it returns empty
      //but thats the thing, it needs to return the array fi one of the values is good already
      //but it must be done with looping.... ig eus

      /*

      */

      //de orderby werkt gewoon niet xD hij gaat gewoon in de if..yyyy
      //ahhhhhhhhhhhhhhhhhhhhhhhhhh hij geeft de keys niet terug, alleen de valeus
    }
    }
  }
};
</script>
