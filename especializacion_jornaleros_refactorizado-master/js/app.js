var bdJornaleros = JSON.parse(localStorage.getItem('bdJornaleros'));

//Condicional ternario ? significa si
//                     : significa sino 

//Investigar el error del condicional ternario en
// bdJornaleros === null ? [] : bdJornaleros;

if (bdJornaleros === null) {
    bdJornaleros = [];
}


function conecction() {

    var canSync = navigator.onLine;

    if (canSync) {
        alertify.success('Estas conectado!!!.');
        $('#btnSincronizar').removeClass('ocultar').addClass('visualizar');
    } else {
        alertify.message('No tienes conexión a internet.');
        $('#btnSincronizar').removeClass('visualizar').addClass('ocultar');
    }

}

window.addEventListener('offline', function () {
    conecction();
});

window.addEventListener('online', function () {
    conecction();
});


$(document).ready(function () {

    listar();

    conecction();

    $('[data-toggle="tooltip"]').tooltip();

    $('#formJornaleros').on('submit', function (event) {
        event.preventDefault();

        var codigoJornalero = $('#txtCodigo').val();

        console.log(codigoJornalero);

        if (codigoJornalero == '') {
            guardar();
            listar();
        } else {
            actualizar(codigoJornalero);
        }
    });

    $('#btnCancelar').on('click', function () {
        limpiar();
    });

    $('#btnSincronizar').on('click', function(){
        sync();
    })

});


function guardar() {

    var jornalero = {};
    jornalero.codigo = new Date().getTime();
    jornalero.nombre = document.getElementById('txtNombre').value;
    jornalero.correoElectronico = $('#txtCorreoElectronico').val();
    jornalero.fechaNacimiento = $('#txtFechaNacimiento').val();
    jornalero.peso = $('#txtPeso').val();

    bdJornaleros.push(jornalero);

    console.log(bdJornaleros);

    var bdJornalerosString = JSON.stringify(bdJornaleros);

    console.log(bdJornalerosString);

    localStorage.setItem('bdJornaleros', bdJornalerosString);

    limpiar();

    alertify.success('Jornalero guardado exitosamente.');
}

function listar() {

    $('#tblJornaleros tbody').empty();

    if (bdJornaleros.length !== 0) {

        var template = '';

        //Foreach
        for (var i in bdJornaleros) {
            var jornalero = bdJornaleros[i];

            template += `<tr>
            <th class="text-center" scope="row">${jornalero.codigo}</th>
            <td>${jornalero.nombre}</td>
            <td>${jornalero.correoElectronico}</td>
            <td>${jornalero.fechaNacimiento}</td>
            <td>${jornalero.peso}Kg</td>
            <td>
                <button class="btn btn-primary" onclick='modificar(${jornalero.codigo})'>
                    <i class="fa fa-edit"></i>
                    Editar
                </button>

                <button class="btn btn-danger btn-eliminar" id="${jornalero.codigo}">
                    <i class="fa fa-trash-alt"></i>
                    Eliminar
                </button>
            </td>
        </tr>`;
        }

        //document.ge  
        $('#tblJornaleros tbody').append(template);

        $('.btn-eliminar').on('click', function () {

            var codigoJornalero = $(this).attr('id');
            alertify.confirm(
                'Mensaje de alerta',
                `¿En verdad deseas eliminar el elemento: ${codigoJornalero}?`,
                function () {
                    console.log('ok');
                    eliminar(codigoJornalero);
                },
                function () {
                    console.log('cancelar');
                }).set('labels', {
                ok: 'Confirmar',
                cancel: 'Cancelar'
            });

        });

    } else {
        $('#tblJornaleros tbody').append('<tr> <td colspan="6"> No se encuentran datos para mostrar </td> </tr>');
    }

}

function limpiar() {
    $('#formJornaleros')[0].reset();
    $('#fgCodigoJornalero').removeClass('visualizar').addClass('ocultar');
    $('#btnGuardar').removeClass('btn-primary').addClass('btn-success').text('Guardar');
}

function modificar(codigoJornalero) {

    var jornalero = bdJornaleros.filter(function (jornalero, index) {
        return jornalero.codigo == codigoJornalero;
    })[0];

    $('#txtCodigo').val(jornalero.codigo);
    $('#txtNombre').val(jornalero.nombre);
    $('#txtCorreoElectronico').val(jornalero.correoElectronico);
    $('#txtFechaNacimiento').val(jornalero.fechaNacimiento);
    $('#txtPeso').val(jornalero.peso);

    $('#fgCodigoJornalero').removeClass('ocultar').addClass('visualizar');
    $('#btnGuardar').removeClass('btn-success').addClass('btn-primary').text('Modificar');

}

function eliminar(codigoJornalero) {
    bdJornaleros = bdJornaleros.filter(function (jornalero, index) {
        return jornalero.codigo != codigoJornalero;
    });
    var bdJornalerosString = JSON.stringify(bdJornaleros);
    localStorage.setItem('bdJornaleros', bdJornalerosString);
    listar();
}

function actualizar(codigoJornalero) {

    bdJornaleros.map(function (jornalero) {
        if (jornalero.codigo == codigoJornalero) {
            jornalero.nombre = $('#txtNombre').val();
            jornalero.correoElectronico = $('#txtCorreoElectronico').val();
            jornalero.fechaNacimiento = $('#txtFechaNacimiento').val();
            jornalero.peso = $('#txtPeso').val();
        }
    });

    var bdJornalerosString = JSON.stringify(bdJornaleros);
    localStorage.setItem('bdJornaleros', bdJornalerosString);

    limpiar();
    listar();
    alertify.success(`Jornalero con código: ${codigoJornalero} ha sido modificado con éxito.`);

}

function sync() {

    var jornaleros = bdJornaleros;

    jornaleros.map(function (jornalero) {

        var fecha = jornalero.fechaNacimiento.split('-');
        jornalero.fechaNacimiento = `${fecha[2]}/${fecha[1]}/${fecha[0]}`;
        jornalero.nombres = jornalero.nombre;
        delete jornalero.nombre;
        delete jornalero.codigo;
        return jornalero;

    });

    var url = 'http://localhost:80/api_restful/public/v1/jornaleros/sync';
    var parametros = {
        method: 'post',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json;charset=UTF-8'
        },
        body: JSON.stringify(jornaleros)
    }

    fetch(url, parametros)
        .then(respuesta => respuesta.json())
        .then(datos => {
            console.log(datos);
            alertify.success('Los jornaleros han sido sincronizados con éxito');
            bdJornaleros = [];

            var bdJornalerosString = JSON.stringify(bdJornaleros);
            localStorage.setItem('bdJornaleros', bdJornalerosString);
            listar();
        })
        .catch(error => {
            console.log(`Ocurrio un error inesperado: ${e}`);
            alertify.message('Los jornaleros no fueron sincronizados');
        });

}