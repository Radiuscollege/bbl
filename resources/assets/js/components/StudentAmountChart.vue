<script>
import { Bar } from "vue-chartjs";

export default {
  extends: Bar,
  props: { moduleInfo: { type: Array, required: true } },
  data: function() {
    return {
      datacollection: {}
    };
  },
  mounted: function() {
    this.datacollection = {
      datacollection: {
        labels: _.map(this.moduleInfo, "name"),
        datasets: [
          {
            label: "Studenten bezig",
            backgroundColor: "#7EA6E0",
            data: _.map(this.moduleInfo, "student_modules_count")
          },
          {
            label: "Studenten afgerond",
            backgroundColor: "#f87979",
            data: _.map(this.moduleInfo, "finished_modules_count")
          }
        ]
      }
    };
    this.renderChart(this.datacollection.datacollection, {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        yAxes: [
          {
            ticks: {
              stepSize: 1,
              min: 0,
              autoSkip: false
            }
          }
        ],
        xAxes: [
          {
            stacked: false,
            beginAtZero: true,
            scaleLabel: {
              labelString: "Month"
            },
            ticks: {
              stepSize: 1,
              min: 0,
              autoSkip: false
            }
          }
        ]
      }
    });
  }
};
</script>
