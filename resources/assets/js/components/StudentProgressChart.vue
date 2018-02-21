<script>
import { HorizontalBar } from "vue-chartjs";

export default {
  extends: HorizontalBar,
  data: function() {
    return {
      datacollection: {}
    };
  },
  props: ["student"],
  created: function() {},
  mounted: function() {
    this.datacollection = {
      datacollection: {
        datasets: [
          {
            label:
              this.student.first_name +
              (this.student.prefix == null
                ? " "
                : " " + this.student.prefix + " ") +
              this.student.last_name,
            backgroundColor: "#f87979",
            data: [this.student.progress.toFixed(1), 0]
          },
          {
            label: "Geschatte voortgang",
            backgroundColor: "#005cbf",
            data: [this.student.estimated_progress.toFixed(1), 0]
          }
        ]
      }
    };
    this.renderChart(this.datacollection.datacollection, {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        xAxes: [
          {
            ticks: {
              min: 0,
              max: 100
            }
          }
        ]
      }
    });
  }
};
</script>