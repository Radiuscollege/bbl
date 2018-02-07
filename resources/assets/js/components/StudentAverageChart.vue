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
  },
  mounted: function() {
    console.log(this.studentModule);
    this.datacollection = {
      datacollection: {
        labels: "Gemiddelde",
        datasets: [
          {
            label: _.map(this.studentModule, "name"),
            backgroundColor: "#f87979",
            data: _.map(this.studentModule, "mark")
          }
        ]
      }
    };
    this.renderChart(this.datacollection.datacollection, {
      responsive: true,
      maintainAspectRatio: false
    });
  }
};
</script>