<template>
  <div class="container pt-3">
    <span v-if="filteredModules">
      <div 
        v-if="success" 
        class="alert alert-primary"
      >
        {{ success }}
      </div>
      <div 
        v-for="module in filteredModules"
        v-if="module"    
        :key="module.id"
        class="row"
      >
        <div class="col-8">
          <div class="card bg-light mt-3">
            <div class="card-header">{{ module.name }}</div>
            <div class="card-body">
              <p class="card-text">{{ module.sub_description }}</p>
            </div>
            <div class="card-footer bg-transparent border-success">
              <a 
                v-if="module.long_description" 
                :href="&quot;#collapse&quot; + module.id" 
                :aria-controls="&quot;collapse&quot; + module.id"
                data-toggle="collapse" 
                aria-expanded="true"
              >
                Uitleg
              </a>
              <div class="float-right">
                <a 
                  :href="'/module/' + module.id" 
                  role="button" 
                  class="btn btn-primary"
                >
                  Wijzigen
                </a>
                <button 
                  v-if="!module.used" 
                  :disabled="submittedDelete" 
                  class="btn btn-danger"
                  role="button" 
                  @click="deleteModule(module.id)"
                >
                  Verwijderen
                </button>
              </div>
              <div 
                :id="'collapse' + module.id" 
                class="collapse" 
                role="tabpanel" 
                aria-labelledby="heading" 
                data-parent="#accordion"
              >
                <div class="card-body">
                  <medium-editor 
                    :text="module.long_description" 
                    :options="options"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-2 my-auto">
          <div class="card text-center">
            <div class="card-body">
              <p class="card-text">{{ module.week_duration / 8 }} periode</p>
              <p class="card-text">=</p>
              <p class="card-text">{{ module.week_duration }} weken</p>
            </div>
          </div>
        </div>
      </div>
    </span>
    <span v-else>
      <div 
        class="alert alert-primary" 
        role="alert"
      >
        Er is geen module bij deze cohort gevonden, voeg een toe bij <a href="module/add">Module toevoegen</a>
      </div>
    </span>
  </div>
</template>
<script>
import editor from "vue2-medium-editor";

export default {
  name: "Modulelist",
  components: {
    "medium-editor": editor
  },
  props: { cohort: { type: String, required: true } },
  data: function() {
    return {
      modules: [],
      options: { disableEditing: true, toolbar: false, placeholder: false },
      submittedDelete: false,
      success: ""
    };
  },
  computed: {
    //returns modules depending on what cohort is selected in dropdownlist
    filteredModules: function() {
      if (this.cohort == "Alle cohorten tonen") {
        return this.modules;
      }
      //filters the modules by checking in the child of a module if the name matches
      var filtered = _.filter(this.modules, {
        cohorts: [{ name: this.cohort }]
      });
      console.log(filtered);
      if (!Array.isArray(filtered) || !filtered.length) {
        return null;
      }
      return filtered;
    }
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
      if (window.confirm("Weet je zeker dat je de module wilt verwijderen?")) {
        this.submittedDelete = true;
        axios.delete("/api/module/" + id).then(res => {
          this.getModules();
          this.submittedDelete = false;
          this.success = "De module is succesvol verwijderd!";
        });
      }
    }
  }
};
</script>
