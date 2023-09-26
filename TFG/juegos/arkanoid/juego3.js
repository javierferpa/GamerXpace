

// variable que indica si el juego esta iniciado (0=no,1=si)
var inicio = 0;
// variables de botones
var boton = document.getElementById("play");

var iz = document.getElementById("iz");
var der = document.getElementById("der");


var puntuacionTotal = 0;
var nivel = 1;

function arkanoid(){
    // se inicia el juego
    inicio = 1;
    // creacion de variables
var backgroundImage = new Image();
backgroundImage.src = "/tfg/img/fondoArk.jpg";
    
var bola = new Image();
bola.src = "/tfg/img/bola.png";

var canvas = document.getElementById("myCanvas");
// cambia el display del canvas de none a block
canvas.style.display="block";
// setea el ancho y el alto del canvas
canvas.setAttribute('width', main.clientWidth * 0.8); 
canvas.setAttribute('height', (main.clientWidth * 0.8) /1.5); 
// guarda el alto y el ancho en una variable
var ancho = main.clientWidth * 0.8;
var alto = ((main.clientWidth * 0.8) /1.5);

var ctx = canvas.getContext("2d");

// tamaño del balon
var PelotaRadius = ancho/48;
// posicion del balon
var x = canvas.width/2;
var y = canvas.height * 0.9;
// velocidad de movimiento del balon
var dx = (ancho/240)*nivel;
var dy = -(ancho/240)*nivel;


// alto y ancho de la pala
var PalaHeight = alto/24;
var PalaWidth = ancho/4;
// posicion de la pala
var PalaX = (canvas.width-PalaWidth)/2;
// comprobar que boton esta pulsado
var derechaP = false;
var izquierdaP = false;
// cantidad de ladrillos
var brickRowCount = 5;
var brickColumnCount = 4;

// medidas de los ladrillos
var anchoLadrillo = ancho/6.4;
var altoLadrillo = alto/16;
// separacion entre ladrillos
var separacionLadrillo = ancho/48;
// separacion de los ladrillos con los margenes
var separacionAlto = ancho/10.6;
var separacionIz = ancho/16;
// puntuacion
var puntuacion = 0;
// intervalo
var id;
// imagenes
var gameOver = new Image();
gameOver.src = "../../img/gameOver.png";

var youWin = new Image();
youWin.src = "../../img/youWin.jpg";

var pala = new Image();
pala.src = "../../img/arkanoid.png"


// array de ladrillos y en el que se generan los ladrillos, el status indica si esta roto o sigue vivo
var Ladrillo = [];
for(c=0; c<brickColumnCount; c++) {
    Ladrillo[c] = [];
    for(r=0; r<brickRowCount; r++) {
        Ladrillo[c][r] = { x: 0, y: 0, status: 1 };
    }
}
// crea un evento de escucha para cuando se pulsa una tecla
document.addEventListener("keydown", keyDownHandler, false);
document.addEventListener("keyup", keyUpHandler, false);

// crea los eventos de escucha para los botones de la version movil
iz.addEventListener("touchstart",function(e){ keyDownHandler(e, "iz")} , false);
der.addEventListener("touchstart",function(e){ keyDownHandler(e, "der")}, false);
iz.addEventListener("touchend",function(e){ keyUpHandler(e, "iz")} , false);
der.addEventListener("touchend",function(e){ keyUpHandler(e, "der")} , false);
// comprueba el evento de pulsacion tanto de boton como de tecla y comprueba cual se ha pulsado
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
    }
}
// compruebo el evento de dejar de levantar la tecla o el boton
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
// se comprueba en cada frame si la pelota esta tocando algun ladrillo
function colision() {
    for(c=0; c<brickColumnCount; c++) {
        for(r=0; r<brickRowCount; r++) {
            var b = Ladrillo[c][r];
            // comprueba si el ladrillo esta activo
            if(b.status == 1) {
                // si la posicion de la pelota mas su radio esta en contacto con la posicion del ladrillo mas su ancho y alto
                if(x + PelotaRadius >= b.x && x <= b.x+anchoLadrillo && y + PelotaRadius >= b.y && y <= b.y+altoLadrillo) {
                    // si entra en contacto la pelota cambia de direccion
                    dy = -dy;
                    // cambia el estado del ladrillo a no activo
                    b.status = 0;
                    // suma un punto
                    puntuacion++;
                    // si los puntos igualan al numero de ladrillos se gana
                    if(puntuacion == brickRowCount*brickColumnCount) {
                        // borra el canvas
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        // muestra la imagen de victoria
                        ctx.drawImage(youWin, 0, 0, canvas.width, canvas.height);
                        // reinicia el juego
                        clearInterval(id);
                        inicio = 0;
                        // muestra la puntuación
                        document.getElementById("instrucciones").style.display = "block";
                        document.getElementById("punt").innerHTML = "Tu puntuacion es de: " + (puntuacionTotal + puntuacion)
                        document.getElementById("text").style.display = "none";
                        // sube de nivel
                        nivel += 0.2;
                        // suma la puntuacion de esta fase a la total de la partida
                        puntuacionTotal += puntuacion
                    }
                }
            }
        }
    }
}
// dibuja la pelota
function dibujaPelota() {
    ctx.drawImage(bola, x, y, ancho/15, ancho/15)
}
// dibuja la pala
function dibujaPala() {
//    dibuja la pala, se especifican la imagen, la posicion X e Y y sus dimensiones
    ctx.drawImage(pala, PalaX, canvas.height-PalaHeight, PalaWidth, PalaHeight);
}
// dibuja los ladrillos
function dibujaLadrillo() {
    for(c=0; c<brickColumnCount; c++) {
        for(r=0; r<brickRowCount; r++) {
            // recorre el array de los ladrillo y dibuja los que estan activos
            if(Ladrillo[c][r].status == 1) {
                // para la posicion del ladrillo se multiplica la fila por la suma del ancho del ladrillo y la separacion entre ladrillos y se le suma la separacion izquierda
                var ladrilloX = (r*(anchoLadrillo+separacionLadrillo))+separacionIz;
                var ladrilloY = (c*(altoLadrillo+separacionLadrillo))+separacionAlto;
                // setea la posicion en el array
                Ladrillo[c][r].x = ladrilloX;
                Ladrillo[c][r].y = ladrilloY;
                ctx.beginPath();
                // dibuja el ladrillo
                ctx.rect(ladrilloX, ladrilloY, anchoLadrillo, altoLadrillo);
                ctx.fillStyle = " #ff8000";
                ctx.fill();
                ctx.closePath();
            }
        }
    }
}
// dibuja el juego
function dibuja() {
    // borra el canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    // dibuja el fondo
    ctx.drawImage(backgroundImage, 0, 0, ancho, alto)

    dibujaLadrillo();
    dibujaPelota();
    dibujaPala();
    colision();
// toca pared
    if(x + dx > canvas.width-PelotaRadius || x + dx < PelotaRadius) {
        dx = -dx;
    }
// Toca techo
    if(y + dy < PelotaRadius) {
        dy = -dy;
    }
// toca la pala o se cae   
    if(y + dy >= canvas.height - PalaHeight - PelotaRadius) {
        if(x + PelotaRadius > PalaX  && x < PalaX + PalaWidth) {
            dy = -dy;
        }else{
            // fin del juego pierdes
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            // muestra imagen perder
            ctx.drawImage(gameOver, 0, 0, canvas.width, canvas.height);
            // reinicia el intervalo
            clearInterval(id);
            inicio = 0;
            // cambia el boton a restart
            boton.innerHTML= "Restart";
            // muestra la puntuacion
            document.getElementById("instrucciones").style.display = "block";
            document.getElementById("text").style.display = "none";
            document.getElementById("punt").innerHTML = "Tu puntuacion es de: " + (puntuacionTotal + puntuacion)
            // resetea el nivel a 1
            nivel = 1;
            // si esta logueado se envia la puntuacion y en caso de no estarlo indica que la puntuacion no se guardara
            if(usuario != null && usuario != ""){
                EnvPunt();
            } else {
                document.getElementById("punt").innerHTML = document.getElementById("punt").innerHTML + "<br>Para guardar su puntuacion debe de estar logado"
            }
        }
    }
    // si derechaP es true y no esta al borde derecho aumenta la posicion x de la pala
    if(derechaP && PalaX < canvas.width-PalaWidth) {
        PalaX += canvas.width/68;
    }else if(izquierdaP && PalaX > 0) {
     // si izquierdaP es true y no esta al borde izquierdo disminuye la posicion x de la pala
        PalaX -= canvas.width/68;
    }
    // aumenta la posicion de la bola(x e Y) con la velocidad de la bola(dx y dy)
    x += dx;
    y += dy;
}


// crea el intervalo del dibujado
id = setInterval(dibuja, 10);
// funcion para enviar los puntos
function EnvPunt(){


    if (window.XMLHttpRequest) {
        peticion=new XMLHttpRequest(); 
          } else if (window.ActiveXObject) {
        peticion=ActiveXObject("Microsoft.XMLHTTP"); 
          } 
// crea un objeto con la puntuacion y el usuario
          datos={}
          datos.punt = (puntuacionTotal + puntuacion);
          datos.user = usuario;
   

          peticion.onreadystatechange = function(){
        if(peticion.readyState == 4) {
          if (peticion.status == 200) {
            
            console.log(peticion.responseText)
            console.log("puntuacion enviada")
          }
        }
          }
//    envia la peticion con POST y los datos en JSON
          peticion.open("POST", "./Puntuacion.php");
          peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          peticion.send("datos="+JSON.stringify(datos));
}

}




// inicia el juego al pulsar el boton de inicio
boton.addEventListener('click', function(){
if (inicio == 0){
    arkanoid();
    console.log("nivel boton: " + nivel)
}
    this.innerHTML = "siguiente";
    // oculta las instrucciones al iniciar el juego
    document.getElementById("instrucciones").style.display = "none";
})

    




