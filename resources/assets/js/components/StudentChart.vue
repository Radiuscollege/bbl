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
  props: ["student", "isStudent"],
  created: function() {
    this.getAverage();
  },
  mounted: function() {},
  methods: {
    getAverage: function() {
      axios
        .get(
          this.isStudent ? "/api/statistics/average" : "/api/statistics/" +
            _.last(window.location.pathname.split("/")) +
            "/average" 
        )
        .then(res => {
          this.markAverage = res.data;
          this.datacollection = {
            datacollection: {
              labels: this.markAverage.labels,
              datasets: [
                {
                  label:
                    this.student.first_name +
                    (this.student.prefix == null
                      ? " "
                      : " " + this.student.prefix + " ") +
                    this.student.last_name,
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
            maintainAspectRatio: false,
            scales: {
              xAxes: [
                {
                  beginAtZero: true,
                  ticks: {
                    stepSize: 1,
                    min: 0,
                    autoSkip: false
                  }
                }
              ]
            }
          });
        })
        .catch(error => {
          console.log("Er is iets foutgegaan tijdens het ophalen van de data.");
        });
    }
  }
};
</script>