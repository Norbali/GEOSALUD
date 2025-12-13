alert("✔️ El archivo JS SÍ SE CARGÓ");

const form = document.querySelector("form");

const expresionesRegulares = {
    nombre: /^[a-zA-ZñÑ\s]{2,20}$/,
    apellido: /^[a-zA-ZñÑ\s]{2,20}$/,
    correo: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
    contraseña: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&._-])[A-Za-z\d@$!%*?&._-]{8,16}$/,
    telefono: /^[0-9]{10,11}$/,
    documento: /^[0-9]{9,10}$/
};


const errores = {
    nombre: "El nombre debe tener entre 2 y 20 letras",
    apellido: "El apellido debe tener entre 2 y 20 letras",
    correo: "Correo inválido. Debe incluir una extensión y dominio, por ejemplo: usuario@dominio.extension",
    contraseña: "La contraseña debe tener entre 8 y 16 caracteres, incluir 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial",
    telefono: "Número inválido. Debe ser un número telefónico válido, por ejemplo: 3123456789.",
    documento: "Número de documento invalido, el número de documento debe tener 9 o 10 dígitos"
};

const campos = {
    documento: false,
    nombre: false,
    apellido: false,
    telefono: false,
    correo: false,
    contraseña: false,
    id_rol: false
};

// Selecciona todos los inputs
const inputs = form.querySelectorAll("input");

const validarFormulario = (e) => {
    const input = e.target;
    const msj = input.nextElementSibling;

    switch (input.id) {
        case "documento":
            validarCampo(expresionesRegulares.documento, input, errores.documento);
            break;

        case "nombre":
            validarCampo(expresionesRegulares.nombre,  input, errores.nombre);
            break;

        case "apellido":
            validarCampo(expresionesRegulares.apellido,  input, errores.apellido);
            break;

        case "telefono":
            validarCampo(expresionesRegulares.telefono, input, errores.telefono);
            break;

        case "correo":
            validarCampo(expresionesRegulares.correo, input, errores.correo);
            break;

        case "contraseña":
            validarCampo(expresionesRegulares.contraseña, input, errores.contraseña);
            break;
    }
};

const validarCampo = (expresion, input, mensaje) => {
    const msj = input.nextElementSibling;

    if (input.value.trim() === "") {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        msj.textContent = "Este campo es obligatorio.";
        campos[input.id] = false;
        return;
    }

    if (expresion.test(input.value.trim())) {
        input.classList.remove("is-invalid");
        input.classList.add("is-valid");
        msj.textContent = "";
        campos[input.id] = true;
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        msj.textContent = mensaje;
        campos[input.id] = false;
    }
};

// Eventos
inputs.forEach((input) => {
    input.addEventListener("keyup", validarFormulario);
    input.addEventListener("blur", validarFormulario);
});

// Validacion del SELECT
const rol = document.getElementById("id_rol");

rol.addEventListener("change", () => {
    const msj = rol.nextElementSibling;

    if (rol.value === "") {
        rol.classList.add("is-invalid");
        rol.classList.remove("is-valid");
        msj.textContent = "Debe seleccionar un rol.";
        campos["id_rol"] = false;
    } else {
        rol.classList.remove("is-invalid");
        rol.classList.add("is-valid");
        msj.textContent = "";
        campos["id_rol"] = true;
    }
});

// Validación al enviar
form.addEventListener("submit", (e) => {
    let valido = true;

    for (const campo in campos) {
        if (!campos[campo]) {
            valido = false;
        }
    }

    if (!valido) {
        e.preventDefault();
    }else{
        e.preventDefault(); 
    }
});
