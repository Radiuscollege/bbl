<script>
import { Pie } from "vue-chartjs";

export default {
  extends: Pie,
  props: ["cohortInfo"],
  mounted: function() {
    this.datacollection = {
      datacollection: {
        labels: _.map(this.cohortInfo, "name"),
        datasets: [
          {
            label: "Studenten bezig",
            backgroundColor: ["#41B883", "#E46651", "#00D8FF", "#DD1B16"],
            data: _.map(this.cohortInfo, "student_cohort_count")
          }
        ]
      }
    };
    this.renderChart(this.datacollection.datacollection, {
      responsive: false,
      maintainAspectRatio: false
    });
  }
};
</script>