$(document).ready(()=> {
    chargingGraphic();
    setInterval(chargingGraphic, 600000);
});

$('#refresh').click(()=> {
    chargingGraphic();
});

function chargingGraphic() {
    $.ajax({
        url: $('#URL').val()+'extractAssistence',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            event_id: $('#event_id').val(),
        },
        success: (res)=> {
            if (res.exhibitors.quantity != null) {
                $('#exhibitors').text(res.exhibitors.quantity);
            } else {
                $('#exhibitors').text('0');
            }
            if (res.courtesies.quantity != null) {
                $('#courtesies').text(res.courtesies.quantity);
            } else {
                $('#courtesies').text('0');
            }
            $('#spectators').text(res.spectators);
            chart(res.eventDate, res.assistence, res.taquilla);
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
}

function chart(date, assistence, taquilla) {
    var initial_time = (date != null) ? ""+date.initial_time+"" : "00:00";
    initial_time = initial_time.substr(0, 2);
    var final_time = (date != null) ? ""+date.final_time+"" : "23:59";
    final_time = final_time.substr(0, 2);
    var count = parseInt(final_time) - parseInt(initial_time);
    var hours = [], prueba = [];
    for (var i = 0; i < count; i++) {
        hours[i] = parseInt(initial_time)+' - '+ (parseInt(initial_time) + 1); 
        initial_time++;
    }
    Highcharts.chart('graphic', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Ingresos por hora'
        },
        xAxis: {
            type: 'time',
            categories: hours,
            title: {
                text: 'Hora'
            },
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Asistencia por hora'
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
                name: 'Códigos QR',
                data: Object.values(assistence),
                colorByPoint: false,
                color: '#22c7bf'
            },
            {
                name: 'Taquilla',
                data: Object.values(taquilla),
                colorByPoint: false,
                color: '#ffa800'
            }
        ]
    });
}