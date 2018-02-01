<script>
import { HorizontalBar } from "vue-chartjs";

export default {
  extends: HorizontalBar,
  data: function() {
    return {
      datacollection: {},
      markAverage: []
    };
  },
  props: ["studentModule"],
  created: function() {
    this.getAverage();
  },
  mounted: function() {},
  methods: {
    getAverage: function() {
      axios
        .get(
          "/api/studentmodule/average/" +
            _.last(window.location.pathname.split("/"))
        )
        .then(res => {
          this.markAverage = res.data;
          this.datacollection = {
            datacollection: {
              labels: this.markAverage.labels,
              datasets: [
                {
                  label: this.studentModule.first_name + (this.studentModule.prefix == null ? " " : " " + this.studentModule.prefix + " ")  + this.studentModule.last_name,
                  backgroundColor: "#f87979",
                  data: this.markAverage.marks
                },
                {
                  label: "Gemiddelde",
                  backgroundColor: "#005cbf",
                  data: this.markAverage.average_marks
                }
              ]
            }
          };
          this.renderChart(this.datacollection.datacollection, {
            responsive: true,
            maintainAspectRatio: false
          });
        })
        .catch(error => {
          console.log("Er is iets foutgegaan tijdens het ophalen van de data.");
        });
    }
  }
};
</script>