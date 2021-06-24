$(document).ready(()=> {
    chart();
    chartYears();
});

function chart() {
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var ganancia = [10000, 15000, 5000, 20000, 17000, 9000, 21000, 6000, 12000, 10000, 19000, 5000];
    Highcharts.chart('graphicMonths', {
        chart: {
            type: 'column',
            backgroundColor: '#eeeceb',
        },
        title: {
            text: 'Ganancias por mes'
        },
        xAxis: {
            type: 'time',
            categories: months,
            title: {
                text: 'Mes'
            },
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Ganancias'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '${point.y:f}'
                }
            }
        },
        credits: {
            enabled: false
        },
        series: [
            {
                name: 'Ganancias',
                data: Object.values(ganancia),
                colorByPoint: false,
                color: '#22c7bf'
            },
        ]
    });
}

function chartYears() {
    var years = [2021];
    var ganancia = [51500];
    Highcharts.chart('graphicYears', {
        chart: {
            type: 'column',
            backgroundColor: '#eeeceb',
        },
        title: {
            text: 'Ganancias por año'
        },
        xAxis: {
            type: 'time',
            categories: years,
            title: {
                text: 'Año'
            },
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Ganancias'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '${point.y:f}'
                }
            }
        },
        credits: {
            enabled: false
        },
        series: [
            {
                name: 'Ganancias',
                data: Object.values(ganancia),
                colorByPoint: false,
                color: '#4308df'
            },
        ]
    });
}