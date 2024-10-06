// Configuration du graphique Chart.js
document.addEventListener("DOMContentLoaded", function() {
    new Chart(document.getElementById("chartjs-bar"), {
        type: "bar",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Last year",
                backgroundColor: window.theme ? window.theme.primary : "#007bff",
                borderColor: window.theme ? window.theme.primary : "#007bff",
                hoverBackgroundColor: window.theme ? window.theme.primary : "#007bff",
                hoverBorderColor: window.theme ? window.theme.primary : "#007bff",
                data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
                barPercentage: 0.75,
                categoryPercentage: 0.5
            }, {
                label: "This year",
                backgroundColor: "#dee2e6",
                borderColor: "#dee2e6",
                hoverBackgroundColor: "#dee2e6",
                hoverBorderColor: "#dee2e6",
                data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51, 68],
                barPercentage: 0.75,
                categoryPercentage: 0.5
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        display: false
                    },
                    stacked: false
                }],
                xAxes: [{
                    stacked: false,
                    gridLines: {
                        color: "transparent"
                    }
                }] // Correction : fermeture correcte du tableau ici
            }
        }
    });

    // Configuration du graphique ApexCharts
    var options = {
        chart: {
            height: 350,
            type: "bar",
            stacked: true,
        },
        plotOptions: {
            bar: {
                horizontal: true,
            },
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "Marine Sprite",
            data: [44, 55, 41, 37, 22, 43, 21]
        }, {
            name: "Striking Calf",
            data: [53, 32, 33, 52, 13, 43, 32]
        }, {
            name: "Tank Picture",
            data: [12, 17, 11, 9, 15, 11, 20]
        }, {
            name: "Bucket Slope",
            data: [9, 7, 5, 8, 6, 9, 4]
        }, {
            name: "Reborn Kid",
            data: [25, 12, 19, 32, 25, 24, 10]
        }],
        xaxis: {
            categories: [2008, 2009, 2010, 2011, 2012, 2013, 2014],
            labels: {
                formatter: function(val) {
                    return val + "K";
                }
            }
        },
        yaxis: {
            title: {
                text: undefined
            },
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + "K";
                }
            }
        },
        fill: {
            opacity: 1
        },
        legend: {
            position: "top",
            horizontalAlign: "left",
            offsetX: 40
        }
    };

    var chart = new ApexCharts(document.querySelector("#apexcharts-bar"), options);
    chart.render();
});
