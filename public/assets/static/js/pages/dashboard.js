var optionsProfileVisit = {
  annotations: {
    position: "back",
  },
  dataLabels: {
    enabled: false,
  },
  chart: {
    type: "bar",
    height: 300,
  },
  fill: {
    opacity: 1,
  },
  plotOptions: {},
  series: [
    {
      name: "sales",
      data: [9, 20, 30, 20, 10, 20, 30, 20, 10, 20, 30, 20],
    },
  ],
  colors: "#435ebe",
  xaxis: {
    categories: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
  },
}
let optionsVisitorsProfile = {
  series: [70, 30],
  labels: ["Male", "Female"],
  colors: ["#435ebe", "#55c6e8"],
  chart: {
    type: "donut",
    width: "100%",
    height: "350px",
  },
  legend: {
    position: "bottom",
  },
  plotOptions: {
    pie: {
      donut: {
        size: "30%",
      },
    },
  },
}

var optionsEurope = {
  series: [
    {
      name: "series1",
      data: [310, 800, 600, 430, 540, 340, 605, 805, 430, 540, 340, 605],
    },
  ],
  chart: {
    height: 80,
    type: "area",
    toolbar: {
      show: false,
    },
  },
  colors: ["#5350e9"],
  stroke: {
    width: 2,
  },
  grid: {
    show: false,
  },
  dataLabels: {
    enabled: false,
  },
  xaxis: {
    type: "datetime",
    categories: [
      "2018-09-19T00:00:00.000Z",
      "2018-09-19T01:30:00.000Z",
      "2018-09-19T02:30:00.000Z",
      "2018-09-19T03:30:00.000Z",
      "2018-09-19T04:30:00.000Z",
      "2018-09-19T05:30:00.000Z",
      "2018-09-19T06:30:00.000Z",
      "2018-09-19T07:30:00.000Z",
      "2018-09-19T08:30:00.000Z",
      "2018-09-19T09:30:00.000Z",
      "2018-09-19T10:30:00.000Z",
      "2018-09-19T11:30:00.000Z",
    ],
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: false,
    },
  },
  show: false,
  yaxis: {
    labels: {
      show: false,
    },
  },
  tooltip: {
    x: {
      format: "dd/MM/yy HH:mm",
    },
  },
}

let optionsAmerica = {
  ...optionsEurope,
  colors: ["#008b75"],
}
let optionsIndonesia = {
  ...optionsEurope,
  colors: ["#dc3545"],
}

// Only create charts if the elements exist
const chartProfileVisitEl = document.querySelector("#chart-profile-visit");
const chartVisitorsProfileEl = document.getElementById("chart-visitors-profile");
const chartEuropeEl = document.querySelector("#chart-europe");
const chartAmericaEl = document.querySelector("#chart-america");
const chartIndonesiaEl = document.querySelector("#chart-indonesia");

if (chartProfileVisitEl) {
  var chartProfileVisit = new ApexCharts(chartProfileVisitEl, optionsProfileVisit);
  chartProfileVisit.render();
}

if (chartVisitorsProfileEl) {
  var chartVisitorsProfile = new ApexCharts(chartVisitorsProfileEl, optionsVisitorsProfile);
  chartVisitorsProfile.render();
}

if (chartEuropeEl) {
  var chartEurope = new ApexCharts(chartEuropeEl, optionsEurope);
  chartEurope.render();
}

if (chartAmericaEl) {
  var chartAmerica = new ApexCharts(chartAmericaEl, optionsAmerica);
  chartAmerica.render();
}

if (chartIndonesiaEl) {
  var chartIndonesia = new ApexCharts(chartIndonesiaEl, optionsIndonesia);
  chartIndonesia.render();
}
