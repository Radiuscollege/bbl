<template>
  <div v-if="module" class="modal-open" id="#markModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="markModalLabel">Beoordelen</h5>
          <button type="button" class="close" v-on:click="$emit('close')">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group row">
              <label for="inputMark" class="col-sm-2 col-form-label">Cijfer:</label>
              <div class="col">
                <input v-model="module.mark" type="number" class="form-control" placeholder="6.0" step="0.1" min="0" max="10">
              </div>
              <label for="or" class="col-sm-2 col-form-label">of</label>
              <div class="col">
                <div class="form-check">
                  <input v-model="module.pass" class="form-check-input" type="checkbox" id="gridCheck">
                  <label class="form-check-label" for="gridCheck">
                    Voldoende
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">Notitie:</label>
              <textarea class="form-control" id="message-text"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button v-on:click="setMark(module.id, module.mark, module.pass); $emit('close');" type="button" class="btn btn-primary">Beoordeel</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import FontAwesomeIcon from "@fortawesome/vue-fontawesome";

export default {
  name: "markmodal",
  data: function() {
    return {
      module: { id: this.studentModule, mark: 6.5, pass: true }
    };
  },
  props: ['studentModule'],
  components: {
    FontAwesomeIcon
  },
  methods: {
    setMark: function(moduleID, mark, passed) {
      axios
        .post("/api/studentmodule/mark/" + moduleID, {
          mark: mark,
          pass: passed
        })
        .then(res => {})
        .catch(err => console.error(err));
    }
  }
};
</script>

