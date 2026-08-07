// JavaScript Document

var homeURL;

function InitializeValues(home) {
	homeURL = home;
	salesPaymentsGraph('.graph-sales-payments', '100%', 425);
  if(callBack != null && callBack != '') {
    processCallBack(callBack);
  }
}

function processCallBack(callBack) {
  switch(callBack) {
    case 'deniedAccess':
      ShowToastMessage('Acceso denegado', 'error');
      break;
  }
}

/* Line chart */
function salesPaymentsGraph(idName, width, height = "245") {
  var optionRadial = {
    chart: {
      width: width,
      height: height,
      type: 'line',
      toolbar: {
        show: false,
      },
      animations: {
        enabled: true,
        easing: 'easeinout',
        speed: 800,
        animateGradually: {
          enabled: true,
          delay: 150
        },
        dynamicAnimation: {
          enabled: true,
          speed: 350
        }
      },
    },
    colors: ['#7811FF', '#00AAFF'],
    dataLabels: {
      enabled: false,
    },
    stroke: {
      show: true,
      curve: 'smooth',
      lineCap: 'butt',
      colors: undefined,
      width: 3,
      dashArray: 0,
    },
    legend: {
      show: false,
    },
    tooltip: {
      enabled: true,
      style: {
        fontSize: '14px',
        fontFamily: '"Jost", sans-serif',
      },
      tooltip: {
        custom: function ({
          series,
          seriesIndex,
          dataPointIndex,
          w
        }) {
          return (
            '<div class="arrow_box">' +
            "<span>" +
            w.globals.labels[dataPointIndex] +
            ": " +
            series[seriesIndex][dataPointIndex] +
            "</span>" +
            "</div>"
          );
        }
      },
      y: {
        formatter: (val) => {
          return val + "K";
        },
      },

    },
    grid: {
      borderColor: '#485e9029',
      strokeDashArray: 5,
      padding: {
        top: 0,
        right: 0,
        bottom: 0,
      },
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '60%',
        borderRadius: 2,
      }
    },
    series: [{
      name: "Total Ventas",
    //   data: [10, 55, 42, 30, 42, 80, 35, 10, 53, 62, 45, 78],
      data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      color: "#3147D3",
    }, {
      name: "Total Pagos",
    //   data: [30, 45, 35, 10, 5, 60, 8, 42, 30, 70, 54, 25],
      data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      color: "#00AAFF",
    }],

    xaxis: {
      crosshairs: {
        show: false
      },
      labels: {
        style: {
          colors: '#747474',
          fontSize: '14px',
          fontFamily: '"Jost", sans-serif',
          fontWeight: 400,
          cssClass: 'apexcharts-yaxis-label',
        },
      },
      categories: [
        "Ene",
        "Feb",
        "Mar",
        "Abr",
        "May",
        "Jun",
        "Jul",
        "Ago",
        "Sep",
        "Oct",
        "Nov",
        "Dic",
      ],
      axisBorder: {
        show: false

      },
      axisTicks: {
        show: false,
      },
      tooltip: {
        enabled: false
      },
    },

    yaxis: {
      min: 0,
      max: 80,
      tickAmount: 4,
      labels: {
        offsetX: -15,
        formatter: (val) => {
          return val + "K";
        },
        style: {
          colors: ['#747474'],
          fontSize: '14px',
          fontFamily: '"Jost", sans-serif',
          fontWeight: 400,
          cssClass: 'apexcharts-yaxis-label',
        },

      },
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
    },
  };
  if (document.querySelectorAll(idName).length > 0) {
    new ApexCharts(document.querySelector(idName), optionRadial).render();
  }
};