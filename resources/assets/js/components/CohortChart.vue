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
            //random list color generator would be handy once there are a lot of cohorts
            backgroundColor: ["#41B883", "#E46651", "#00D8FF", "#DD1B16", "#007bff", "#212529", "#f8f9fa", "#6f42c1", "#ffc107", "#6c757d"],
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