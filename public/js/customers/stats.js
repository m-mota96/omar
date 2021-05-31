$(document).ready(()=> {
    var date = new Date(), year = date.getFullYear(), month = date.getMonth() + 1;
    if (month < 10) {
        month = '0'+month;
    }
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDate();
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    if (firstDay < 10) {
        firstDay = '0'+firstDay;
    }
    if (lastDay < 10) {
        lastDay = '0'+lastDay;
    }
    firstDay = year+'-'+month+'-'+firstDay;
    lastDay = year+'-'+month+'-'+lastDay;
    $('#start_date').val(firstDay);
    $('#end_date').val(lastDay);
    chargingGraphic();
});

$('#start_date').change(()=> {
    chargingGraphic();
});

$('#end_date').change(()=> {
    chargingGraphic();
});

function chargingGraphic() {
    $.ajax({
        url: $('#URL').val()+'chargingGraphic',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            event_id: $('#event_id').val(),
            initial_date: $('#start_date').val(),
            final_date: $('#end_date').val()
        },
        success: (res)=> {
            $('#totalSales').text(res.totalSales);
            $('#totalPending').text(res.totalPending);
            $('#totalExpired').text(res.totalExpired);
            chart(31, res.sales, res.pending, res.expired);
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
}

function chart(final_day, sales, pending, expired) {
    final_day = parseInt(final_day);
    var dateStart = new Date($('#start_date').val());
    var dateEnd    = new Date($('#end_date').val());
    var dates = [];
    var cont = 0;
    while(dateEnd.getTime() >= dateStart.getTime()) {
        dateStart.setDate(dateStart.getDate() + 1);
        var month = dateStart.getMonth() + 1;
        var day = dateStart.getDate();
        if (month < 10) {
            month = '0' + month;
        }
        if (day < 10) {
            day = '0' + day;
        }
        var dateParse = day+'/'+month+'/'+dateStart.getFullYear();
        dates[cont] = dateParse;
        cont++;
    }
    // console.log(sales);
    Highcharts.chart('graphic', {

        title: {
            text: 'Historial de venta'
        },
        xAxis: {
            type: 'date',
            categories: dates,
            title: {
                text: 'Día del mes'
            },
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Boletos vendidos por día'
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
            }
        },
        series: [
            {
                name: 'Boletos pagados',
                data: Object.values(sales),
                colorByPoint: false,
                color: '#22c7bf'
            },
            {
                name: 'Boletos pendientes',
                data: Object.values(pending),
                colorByPoint: false,
                color: '#ffa800'
            },
            {
                name: 'Boletos expirados',
                data: Object.values(expired),
                colorByPoint: false,
                color: '#f64e60'
            }
        ]

    });
}