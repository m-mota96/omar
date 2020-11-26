$(document).ready(()=> {
    $('.input-daterange input').each(function() {
        $(this).datepicker({
            language: 'es'
        });
    });
});

function chart(final_day, sales, pending) {
    final_day = parseInt(final_day);
    var month = [];
    for (var i = 1; i <= final_day; i++) {
        month[i-1] = i;
    }
    Highcharts.chart('graphic', {

        title: {
            text: 'Historial de venta'
        },
        xAxis: {
            categories: month,
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
                data: Object.values(sales)
            },
            {
                name: 'Boletos pendientes',
                data: Object.values(pending)
            }
        ]

    });
}

// function chart() {
//     var options={
//         chart: {
//                renderTo: 'div_grafica_barras',
//                //type: 'column'
//            },
//            title: {
//                text: 'Historial de boletos'
//            },
//            xAxis: {
//                categories: [],
//                 title: {
//                    text: 'Días del mes'
//                },
//                crosshair: true
//            },
//            yAxis: {
//                min: 0,
//                title: {
//                    text: 'Boletos vendidos por día'
//                }
//            },
//            tooltip: {
//                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
//                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
//                    '<td style="padding:0"><b>{point.y} </b></td></tr>',
//                footerFormat: '</table>',
//                shared: true,
//                useHTML: true
//            },
//            plotOptions: {
//                column: {
//                    pointPadding: 0.2,
//                    borderWidth: 0
//                }
//            },
//            series: [
//                {
//                    name: 'Boletos pagados',
//                    data: []
//                },
//                {
//                    name: 'Boletos pendientes',
//                    data: []
//                }
//            ]
//    }

   
//    // console.log(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());

//    $.ajax({
//        dataType: 'json',
//        url: URLactual+'grafica',
//        method: 'post',
//        data: {
//            "_token": $("meta[name='csrf-token']").attr("content"),
//            year: year,
//            month: month
//        },
//        success: function(response) {
//            var datos= response;
//            var totaldias=datos.totaldias;
//            var i=0;
//            for(i=1;i<=totaldias;i++) {
//                options.series[0].data.push( response.registrosdias[i] );
//                options.xAxis.categories.push(i);
//            }
//            for(i=1;i<=totaldias;i++) {
//                options.series[1].data.push( response.pendientes[i] );
//                options.xAxis.categories.push(i);
//            }
//            //options.title.text="aqui se podria cambiar el titulo dinamicamente";
//            chart = new Highcharts.Chart(options);
//        },
//        error: function() {
//            console.log('error');
//        },
//    });
// }