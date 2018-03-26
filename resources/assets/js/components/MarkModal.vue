<template>
  <div>
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div 
            class="modal-dialog" 
            role="document"
          >
            <div class="modal-content">
              <div class="modal-header">
                <h5  
                  id="markModalLabel"
                  class="modal-title"
                >
                  Beoordelen
                </h5>
                <button 
                  type="button" 
                  class="close" 
                  @click="$emit('close')"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div 
                  v-if="error" 
                  class="alert alert-danger"
                >
                  {{ error }}
                </div>
                <form>
                  <div class="form-group row">
                    <label 
                      for="inputMark" 
                      class="col-sm-2 col-form-label"
                    >Cijfer:
                    </label>
                    <div class="col">
                      <input 
                        v-validate="'decimal:1|max_value:10'" 
                        v-model="module[0].mark" 
                        :class="{ 'input': true, 'form-control': true, 'invalid': errors.has('mark') }" 
                        name="mark" 
                        type="number" 
                        placeholder="0.0" 
                        step="0.1" 
                        min="0" 
                        max="10"
                      >
                    </div>
                    <label 
                      for="or" 
                      class="col-sm-2 col-form-label"
                    >
                      of
                    </label>
                    <div class="col my-auto">
                      <div class="form-check">
                        <input  
                          id="gridCheck"
                          v-model="module[0].pass" 
                          class="form-check-input" 
                          type="checkbox"
                        >
                        <label 
                          class="form-check-label" 
                          for="gridCheck"
                        >
                          Voldoende
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label 
                      for="beginDate" 
                      class="col-sm-3 col-form-label"
                    >
                      Begindatum:
                    </label>
                    <div class="col-sm-10">
                      <datepicker 
                        v-model="module[0].beginDate" 
                        :disabled="{from: module[0].finishDate}"
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label 
                      for="finishDate" 
                      class="col-sm-3 col-form-label"
                    >
                      Einddatum:
                    </label>
                    <div class="col-sm-10">
                      <datepicker 
                        v-model="module[0].finishDate" 
                        :disabled="{to: module[0].beginDate}"
                      />
                    </div>
                  </div>
                  <div class="form-group">
                    <label 
                      for="message-text" 
                      class="col-sm-2 col-form-label"
                    >
                      Notitie:
                    </label>
                    <div class="col">
                      <textarea 
                        id="message-text"
                        v-model="module[0].note" 
                        class="form-control" 
                      />
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button 
                  :disabled="submitted" 
                  class="btn btn-primary"
                  type="button" 
                  @click="validateForm"
                >
                  Beoordeel
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>
<script>
import Datepicker from "vuejs-datepicker";
import VeeValidate from "vee-validate";

export default {
  name: "Markmodal",
  components: {
    Datepicker
  },
  props: { studentModule: { type: Object, required: true } },
  data: function() {
    return {
      module: [
        {
          id: this.studentModule.id,
          mark: this.studentModule.mark,
          pass: this.studentModule.pass,
          beginDate: new Date(this.studentModule.beginDate),
          finishDate: new Date(this.studentModule.finishDate),
          note: this.studentModule.note
        }
      ],
      format: "yyyy-MM-dd",
      submitted: false,
      error: ""
    };
  },
  methods: {
    setMark: function() {
      this.submitted = true;
      axios
        .put("/api/studentmodule/" + this.module[0].id + "/mark", {
          student: _.last(window.location.pathname.split("/")),
          mark: this.module[0].mark,
          pass: this.module[0].pass,
          beginDate: this.module[0].beginDate,
          finishDate: this.module[0].finishDate,
          note: this.module[0].note
        })
        .then(res => {
          this.$emit("close");
        })
        .catch(err => {
          this.error = err.response.data;
          this.submitted = false;
        });
    },
    validateForm: function() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.setMark();
          return;
        }
        this.error = "Je hebt iets incorrect ingevuld.";
      });
    }
  }
};
</script>
<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}
</style>
