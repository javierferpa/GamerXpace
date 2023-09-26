function EnvLike(event) {
// coge la id del mensaje
    var id = event.getAttribute("id")

    // si ya tiene pulsado el boton de like envia up al servidor y lo marca
    if(event.classList.contains('is-empty')){
        event.setAttribute("class", "nes-icon is-medium like")
        like = "up";
    }  else {
        event.setAttribute("class", "nes-icon is-medium like is-empty")
        like = "down";
    }
// si se le ha dado like suma 1 al numero de likes
    if(like == "up"){
        console.log(parseInt(event.previousSibling.innerHTML) + 1)
        console.log(event.previousSibling.value)
        event.previousSibling.innerHTML = parseInt(event.previousSibling.innerHTML) + 1 + " "
    } else {
        event.previousSibling.innerHTML = parseInt(event.previousSibling.innerHTML) - 1 + " "
    }


    if (window.XMLHttpRequest) {
        peticion = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        peticion = ActiveXObject("Microsoft.XMLHTTP");
    }
    // envia la id, si se suma o se resta el like y el usuario que lo ha dado
    datos = {}
    datos.idMen = id;
    datos.like = like;
    datos.usu = usuario;


    peticion.onreadystatechange = function () {
        if (peticion.readyState == 4) {
            if (peticion.status == 200) {
                console.log("peticion: " + peticion.responseText)
                
            }
        }
    }
// envia los datos al servidor
    peticion.open("POST", "enviarlikes.php");
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send("datos=" + JSON.stringify(datos));
}

function enviarMens(event){
    // recoge la id del hilo al que se esta creando el mensaje
    var id = event.getAttribute("id")
    // recoge el mensaje
    var mensaje = document.getElementById("textarea_field").value
    console.log(mensaje + " " + id)


    if (window.XMLHttpRequest) {
        peticion = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        peticion = ActiveXObject("Microsoft.XMLHTTP");
    }
    // guarda en un objeto el id del hilo, el mensaje y el usuario que lo crea
    datos = {}
    datos.idHilo = id;
    datos.mens = mensaje;
    datos.usu = usuario;

    
    peticion.onreadystatechange = function () {
        if (peticion.readyState == 4) {
            if (peticion.status == 200) {
                console.log("peticion: " + peticion.responseText)
                window.location="/tfg/mensajes.php?hilo="+id;
                console.log("cambia de pag")
            }
        }
    }
// envia los datos al server
    peticion.open("POST", "enviarMensaje.php");
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send("datos=" + JSON.stringify(datos));
}

function enviarHilo(){
// crea el neuvo hilo
// recoge el tema que se va a crear y el usuario que lo hace
    var tema = document.getElementById("tema").value
    console.log(tema + " " + usuario)
// si no esta vacio lo envia
    if(tema != ""){
        if (window.XMLHttpRequest) {
            peticion = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            peticion = ActiveXObject("Microsoft.XMLHTTP");
        }
        // guarda el tema y el usuario en un objeto
        datos = {}
        datos.tema = tema;
        datos.usu = usuario;


        peticion.onreadystatechange = function () {
            if (peticion.readyState == 4) {
                if (peticion.status == 200) {
                    console.log(peticion.responseText)
                    var respuesta = peticion.responseText;
                    if(respuesta == "    "){
                        // si el envio es correcto recarga la pagina y si devuelve un error lo comunica
                        window.location="/tfg/foro.php";
                    } else {
                        document.getElementById("error").innerHTML = peticion.responseText
                    }
                    
                }
            }
        }
// envia los datos
        peticion.open("POST", "enviarHilo.php");
        peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        peticion.send("datos=" + JSON.stringify(datos));

        
    } else{
        // dice el error si el campo esta en blanco
        document.getElementById("error").innerHTML = "El tema no puede estar en blanco"
    }
}

