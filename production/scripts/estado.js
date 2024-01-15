var tabla;
var refdata, refdataant;
var ctx, mybarChart;
var ctxant, mybarChartant;


//funcion que se ejecuta iniciando
function init() {
    CargarDatos();
}



function CargarDatos() {

    $.post("../ajax/estado.php?op=traerdatos", function (data, status) {
        console.log(data);
        data = JSON.parse(data);
        //Actualizamos valores
        $("#num_año").html(data.datos.anio);
        $("#num_mes").html(data.datos.mes);
        $("#num_dia").html(data.datos.dia);

        //Grafico
        data = data.grafico;
        refdata = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        for (var i = 0; i < data.aaData.length; i++) {
            refdata[data.aaData[i][0] - 1] = data.aaData[i][1];
        }
        

        ctx = document.getElementById("mybarChart");
        ctx.height = 125;
        mybarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                datasets: [{
                        label: 'SERVICIOS EN EL AÑO',
                        backgroundColor: "#26B99A",
                        data: refdata
                    }]
            },

            options: {
                scales: {
                    yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                }
            }
        });

    });
}
init();