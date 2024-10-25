const telefonoRegex = /^\+(\d{1,3})[\s-]?(\d{1,4})[\s-]?(\d{1,4})[\s-]?(\d{1,9})$/;
const correoRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
const imageRegex = /.jpg$|.png$|.jpeg$/gi;
const pass = /^(?=.*\d)(?=(.*\W){1})(?=.*[a-zA-Z])(?!.*\s).{8,20}$/
const urlRegex = /^(https?:\/\/)?([a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})(\/[\w .-]*)*\/?(\?[;&a-zA-Z0-9%_.+-=]*)?(\#[a-zA-Z0-9_-]*)?$/;
const nombreApellidoRegex = /^[A-Za-zÀ-ÿ' ]*$/;



function nombreValido(input, nullo = true) {
    let valor = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (valor.length === 0 && nullo)
        return true;
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    return nombreApellidoRegex.test(valor);
}

function nombreAlfaValido(input, nullo = true) {
    let valor = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (valor.length === 0 && nullo)
        return true;
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    return true
}
function descripcioinValida(input, nullo = true) {
    let valor = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (valor.length === 0 && nullo)
        return true;
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    return true
}
function fechaValida(input,futuro=true, nullo = true) {
    let valor = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (valor.length === 0 && nullo)
        return true;
    var fechaIngresada = new Date(input.value);
    var fechaActual = new Date();
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    return true
}

function apellidoValido(input, nullo = true) {
    return nombreValido(input, nullo);
}


function telefonoValido(input, nullo = true) {
    let valor = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (valor.length === 0 && nullo)
        return true;
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    return telefonoRegex.test(valor);
}

function urlValida(input, nullo = true) {
    let valor = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (valor.length === 0 && nullo)
        return true;
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    return urlRegex.test(valor);
}

function correoValido(input, nullo = true) {
    let valor = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (valor.length === 0 && nullo)
        return true;
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    return correoRegex.test(valor);
}
function etiquetaValida(input, nullo = true) {
    let valor = input.value;
    valor = valor.replace(/,+/g, ',')  // Reemplaza múltiples comas consecutivas por una sola coma
                  .replace(/^,+|,+$/g, '')  // Elimina comas al principio y al final
                  .replace(/\s+/g, '')  // Reemplaza múltiples espacios por un solo espacio
                  .trim().toLowerCase(); //elimina espacios al inicio y al final y pasa a minusculas

    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    
    if (valor.length === 0 && nullo)
        return true;
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    let etiquetas  = valor.split(',');
    for (let i = 0; i < etiquetas.length;i++){
        if(!nombreApellidoRegex.test(etiquetas[i]))
            return false;
    }
    return true;
}

function passValido(input, nullo = true) {
    let valor = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (valor.length === 0 && nullo)
        return true;
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    return correoRegex.test(valor);
}
function isbnValido(input, nullo = true) {
    let valor = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (valor.length === 0 && nullo)
        return true;
    if (valor.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    return validarISBN(valor);
}




function validarContrasena(input, nullo = true) {
    let password = input.value;
    $d = $(input).parent().find('[erika-tooltip]');
    $d.html($d.data('mensaje'))
    if (password.length === 0 && nullo)
        return true;
    if (password.length === 0 && !nullo) {
        $d.html($d.data('requerido'))
        return false;
    }
    let faltaNumero = !/\d/.test(password);
    let faltaCaracterEspecial = !/\W/.test(password);
    let faltaMinuscula = !/[a-z]/.test(password);
    let faltaMayuscula = !/[A-Z]/.test(password);
    let tieneEspacios = /\s/.test(password);
    let longitudInvalida = password.length < 8 || password.length > 20;

    let lis = $d.find('li')
    $(lis).find('svg').addClass('text-green-400')
    if (faltaNumero) {
        $(lis[2]).find('svg').removeClass('text-green-400')
    }
    if (faltaCaracterEspecial) {
        $(lis[3]).find('svg').removeClass('text-green-400')
    }
    if (faltaMinuscula) {
        $(lis[0]).find('svg').removeClass('text-green-400')
    }
    if (faltaMayuscula) {
        $(lis[1]).find('svg').removeClass('text-green-400')
    }
    if (tieneEspacios) {
        $(lis[4]).find('svg').removeClass('text-green-400')
    }
    if (longitudInvalida) {
        $(lis[5]).find('svg').removeClass('text-green-400')
    }

    if (!faltaNumero && !faltaCaracterEspecial && !faltaMinuscula && !faltaMayuscula && !tieneEspacios && !longitudInvalida) {
       return true;
    } else return false
}


