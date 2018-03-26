<script>
import { Pie } from "vue-chartjs";
import randomColor from "randomcolor";

export default {
  extends: Pie,
  props: { cohortInfo: { type: Array, required: true } },
  mounted: function() {
    this.datacollection = {
      datacollection: {
        labels: _.map(this.cohortInfo, "name"),
        datasets: [
          {
            label: "Studenten bezig",
            backgroundColor: randomColor({
              count: this.cohortInfo.length
            }),
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
