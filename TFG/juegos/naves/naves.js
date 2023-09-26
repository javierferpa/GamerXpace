
// creacion de variables
var inicio = 0;

var boton = document.getElementById("play");
var iz = document.getElementById("iz");
var der = document.getElementById("der");


var puntuacionTotal = 0;
var nivel = 1;
var CantEneNiv = 3
var juegoFin = null

function naves(){
    // inicia el juego
    inicio = 1;
    

// recoge el canvas en una variable y lo muestra
var canvas = document.getElementById("myCanvas");
canvas.style.display="block";
// setea el ancho y el alto del canvas
var ancho = main.clientWidth * 0.8;
var alto = ((main.clientWidth * 0.8) /1.5);
canvas.setAttribute('width', ancho); 
canvas.setAttribute('height', alto); 

var ctx = canvas.getContext("2d");

// alto y ancho de la nave
var naveAncho = ancho * 0.15;
var naveAlto = alto * 0.15;
var naveY = canvas.height * 0.83;
var naveX = canvas.width/2;

// comprobar que boton esta pulsado
var derechaP = false;
var izquierdaP = false;
// puntuacion
var puntuacion = 0;
// intervalo
var id;
// misiles
var misiles = []
// imagenes
var gameOver = new Image();
gameOver.src = "../../img/gameOver.png";

var youWin = new Image();
youWin.src = "../../img/youWin.jpg";

// iamgen de la nave el fondo y los enemigos
var naveImage = new Image(); 
naveImage.src = "images/spaceship-pic.png";

var eneFoto1  = new Image(); 
eneFoto1.src     = "images/enemigo1.png";
    
var eneFoto2 = new Image(); 
eneFoto2.src     = "images/enemigo2.png"; 

var backgroundImage = new Image();
backgroundImage.src = "images/background-pic.jpg";

// obtengo los botones de la version movil
var iz = document.getElementById("iz");
var der = document.getElementById("der");
var fire = document.getElementById("fire");

// recoge las pulsaciones de las teclas y los botones
document.addEventListener("keydown", keyDownHandler, false);
document.addEventListener("keyup", keyUpHandler, false);

iz.addEventListener("touchstart",function(e){ keyDownHandler(e, "iz")} , false);
der.addEventListener("touchstart",function(e){ keyDownHandler(e, "der")}, false);
iz.addEventListener("touchend",function(e){ keyUpHandler(e, "iz")} , false);
der.addEventListener("touchend",function(e){ keyUpHandler(e, "der")} , false);

fire.addEventListener("touchstart",function(e){ keyDownHandler(e, "fire")}, false);

// recojo las pulsaciones y segun el boton dispara o se mueve
function keyDownHandler(e, but) {
    if(e.keyCode == 39 || e.keyCode == 68) {
        derechaP = true;
    }
    else if(e.keyCode == 37 || e.keyCode == 65) {
        izquierdaP = true;
    } 
    else if(but == "der"){
        derechaP = true;
    } 
    else if(but == "iz"){
        izquierdaP = true;
    } else if(e.keyCode == 70 || e.keyCode == 32){
        // evita que al pulsar el espacio al disparar vuelva al principio de la pagina
        e.preventDefault()
        // añade un misil al array de misiles
        misiles.push({x: naveX + naveAncho*.5, y: naveY, w: 3,h: 10});
    } else if(but == "fire"){
        e.preventDefault()

        misiles.push({x: naveX + naveAncho*.5, y: naveY, w: 3,h: 10});
    }

}
// detecta que se deja de pulsar la tecla o el boton
function keyUpHandler(e, but) {
    if(e.keyCode == 39 || e.keyCode == 68) {
        derechaP = false;
    }
    else if(e.keyCode == 37 || e.keyCode == 65) {
        izquierdaP = false;
    }
    else if(but == "der"){
        derechaP = false;
    } 
    else if(but == "iz"){
        izquierdaP = false;
    }
}

// plantilla para la creacion de los enemigos
var enemigo = function(options){
    return {
        id: options.id || '',
        x: options.x || '',
        y: options.y || '',
        w: options.w || '',
        h: options.h || '',
        image: options.image || ''
    }
}
// crea el array de enemigos
var enemigos = [];
// la posicion de los enemigos segun el nivel
var enevel = (ancho / 500) * nivel
// posicion X e Y de los enemigos
var xene
var yene = alto * 0.03
// bucle en el que se rellena el array de enemigos con los objetos de los enemgios del nivel
for (let z = 0; z < CantEneNiv; z++) {   
    // reinicia la posicion X a la primera columna del primero de cada fila 
    xene = ancho * 0.15
    for (let j = 0; j < 5; j++) {
        // define que las lineas pares tengan una imagen distinta
        if(z%2 == 0){
            // mete el enemigo en el array asignandole la id con la fila y la columna, la posicion X e Y, el ancho y el alto y la imagen
            enemigos.push(new enemigo({id: "enemigo" + j + z, x: xene, y: -yene, w: ancho * 0.07, h: alto * 0.05, image: eneFoto2 }))
        } else {
            enemigos.push(new enemigo({id: "enemigo" + j + z, x: xene, y: -yene, w: ancho * 0.07, h: alto * 0.05, image: eneFoto1 }))
        }
        // aumenta la posicion X a la que estara el siguiente enemigo
        xene += ancho * 0.17
        
    }
    // aumenta la posicion para la siguiente linea
    yene += alto * 0.1
}             

function dibujaEnemigos () {
    // si no quedan enemigos ganas y si no los dibuja
    if(enemigos.length == 0){
        // define juegoFin como true que indica que has ganado
        juegoFin = true
    } else{
        // recorre el array de enemigos y los dibuja
        for (var i = 0; i < enemigos.length; i++) {
            // dibuja al enemigo recogiendo la imagen, la posicion Y que aumenta segun el nivel, el ancho y el alto
            ctx.drawImage(enemigos[i].image, enemigos[i].x, enemigos[i].y += enevel, enemigos[i].w, enemigos[i].h);
            // llama al metodo que detecta que el enemigo llega al final
            colisionConNave(enemigos[i]);
        }
    }
}

function colisionConNave (enemigo){
    // pierde llega abajo
    if(enemigo.y > alto){
        // define juegoFin como false que indica que has perdido
        juegoFin = false
    }

    // pierde porque choca con la nave comprobando si el enemigo esta en contacto con la nave
   if ((enemigo.y < naveY + (ancho * 0.04) && enemigo.y > naveY - (ancho * 0.04)) &&
        (enemigo.x < naveX + (ancho * 0.075) && enemigo.x > naveX - (ancho * 0.075))) {
            juegoFin = false;
        }

    
}


function dibujaNave() {
    ctx.drawImage(naveImage, naveX ,naveY , naveAncho, naveAlto);
}

function contactoMisil (m, mi) {
    // bucle que comprueba en todos los enemigos si han entrado en contacto con un misil
    for (var i = 0; i < enemigos.length; i++) {
        //variable e se corresponde con un enemigo
        var e = enemigos[i];
        // comprueba si algun enemigo toca el misil
        if(m.x+m.w >= e.x && m.x <= e.x+e.w && m.y >= e.y && m.y <= e.y+e.h){
            // borra el misil del array
            misiles.splice(misiles[mi],1); 
            // borra el enemigo del array
            enemigos.splice(i, 1); 
            // suma un punto
            puntuacion += 1
            
        }
    }
}


function dibujaMisiles(){
    // recorre el array de los misiles
    for(var i=0; i < misiles.length; i++){
        // la variable m corresponde al misil
        var m = misiles[i];
        ctx.fillStyle = "green"
        // dibuja el misil y le resta la velocidad
        ctx.fillRect(m.x, m.y-=alto/60, m.w, m.h); 
        // llama al metodo que comprueba si entra en contacto con un enemigo
        contactoMisil(misiles[i],i);
        // si el misil llega arriba lo quita del array
        if(m.y <= 0){ 
            misiles.splice(i,1); 
        }
    }
}



function dibuja() {
    // borra todo lo del canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    // dibuja el fondo
    ctx.drawImage(backgroundImage, 0, 0, ancho, alto)
    dibujaNave();
    dibujaMisiles();
    dibujaEnemigos();
// si has perdido
    if(juegoFin === false){  
        // detiene el intervalo de dibujo
        clearInterval(id); 
        // borra el canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        // pone la imagen de fin has perdido
        ctx.drawImage(gameOver, 0, 0, canvas.width, canvas.height);
        
        boton.innerHTML= "Restart";
        // muestra las instrucciones
        document.getElementById("instrucciones").style.display = "block";
        document.getElementById("punt").innerHTML = "Tu puntuacion es de: " + (puntuacionTotal + puntuacion)
        // setea el nivel a 1 y las filas de enemigos a 3
        nivel = 1;
        CantEneNiv = 3
        // si estas logado envia la puntuacion y si no te lo indica
        if(usuario != null && usuario != ""){
            EnvPunt();
            console.log("envia puntos")
        } else {
            document.getElementById("punt").innerHTML = document.getElementById("punt").innerHTML + "<br>Para guardar su puntuacion debe de estar logado"
        }
        // resetea el juego y la puntuacion
        inicio = 0
        puntuacionTotal = 0
        
    } else if(juegoFin === true){
        // si has ganado 
        clearInterval(id)
        // borra el canvas y dibuja la imagen de ganar
        ctx.clearRect(0, 0, canvas.width, canvas.height)
        ctx.drawImage(youWin, 0, 0, canvas.width, canvas.height)
        // muestra la puntucion
        document.getElementById("instrucciones").style.display = "block";
        document.getElementById("text").style.display = "none";
        console.log("gana")
        inicio = 0  
        // suma la puntuacion al total y suma el nivel y la linea de enemigos
        puntuacionTotal += puntuacion
        nivel += 0.2;
        CantEneNiv += 1
    }

    // aumenta la posicion de la nave si esta pulsado el boton a la derecha o disminuye si esta pulsando la izquierda
    if(derechaP && naveX < canvas.width-naveAncho) {
        naveX += ancho/120;
    }
    else if(izquierdaP && naveX > 0) {
        naveX -= ancho/120;
    }

}


// crea el intervalo
id = setInterval(dibuja, 20);


// envia la puntuacion al php que actualiza la base de datos igual que en la clase de juego3.js de arkanoid
function EnvPunt(){

    console.log("Puntos en envio" + (puntuacionTotal + puntuacion))

    if (window.XMLHttpRequest) {
        peticion=new XMLHttpRequest(); 
          } else if (window.ActiveXObject) {
        peticion=ActiveXObject("Microsoft.XMLHTTP"); 
          } 

          datos={}
          datos.punt = (puntuacionTotal + puntuacion);
          datos.user = usuario;
   
          console.log(datos.punt)

          peticion.onreadystatechange = function(){
        if(peticion.readyState == 4) {
          if (peticion.status == 200) {
            console.log(peticion.responseText)
            console.log("puntuacion enviada")
          }
        }
          }
   
          peticion.open("POST", "./Puntuacion.php");
          peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          peticion.send("datos="+JSON.stringify(datos));
}

}




// inicia el juego al pulsar el boton
boton.addEventListener('click', function(){
if (inicio == 0){
    juegoFin = null
    naves();
    console.log("nivel boton: " + nivel)
}
    this.innerHTML = "siguiente";
    document.getElementById("instrucciones").style.display = "none";
})

    




