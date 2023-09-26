
var boton = document.getElementById("play");
var inicio = 0;

var iz = document.getElementById("iz");
var der = document.getElementById("der");

iz.addEventListener("mousedown", function(e){ console.log("izP") }, false);
der.addEventListener("mousedown", function(e){ console.log("derP")}, false);

iz.addEventListener("mouseup", function(e){ console.log("izU") }, false);
der.addEventListener("mouseup", function(e){ console.log("derU")}, false);


function arkanoid(){
    inicio = 1;
    

var canvas = document.getElementById("myCanvas");
canvas.style.display="block";

canvas.setAttribute('width', main.clientWidth * 0.8); 
canvas.setAttribute('height', (main.clientWidth * 0.8) /1.5); 

var ancho = main.clientWidth * 0.8;
var alto = ((main.clientWidth * 0.8) /1.5);

var ctx = canvas.getContext("2d");

// tamaño del balon
var ballRadius = ancho/48;
// posicion del balon
var x = canvas.width/2;
var y = canvas.height * 0.9;
// velocidad de movimiento dle balon
var dx = (ancho/240);
var dy = -(ancho/240);
// alto y ancho de la pala
var paddleHeight = alto/24;
var paddleWidth = ancho/4;
// posicion de la pala
var paddleX = (canvas.width-paddleWidth)/2;
// comprobar que boton esta pulsado
var rightPressed = false;
var leftPressed = false;
// cantidad de ladrillos
var brickRowCount = 5;
var brickColumnCount = 4;

// medidas de los ladrillos
var brickWidth = ancho/6.4;
var brickHeight = alto/16;
// separacion entre ladrillos
var brickPadding = ancho/48;
// separacion de los ladrillos con los margenes
var brickOffsetTop = ancho/10.6;
var brickOffsetLeft = ancho/16;
// puntuacion
var score = 0;
// intervalo
var id;
// ciclos que lleva de ejecucion el juego
var temp = 0;
// imagenes
var gameOver = new Image();
gameOver.src = "../../img/gameOver.png";

var youWin = new Image();
youWin.src = "../../img/youWin.jpg";

var pala = new Image();
pala.src = "../../img/arkanoid.png"

var iz = document.getElementById("iz");
var der = document.getElementById("der");


// arrai de ladrillos y en el que se generan los ladrillos, el staus indica si esta roto o sigue vivo
var bricks = [];
for(c=0; c<brickColumnCount; c++) {
    bricks[c] = [];
    for(r=0; r<brickRowCount; r++) {
        bricks[c][r] = { x: 0, y: 0, status: 1 };
    }
}

document.addEventListener("keydown", keyDownHandler, false);
document.addEventListener("keyup", keyUpHandler, false);

// document.addEventListener("mousemove", mouseMoveHandler, false);

iz.addEventListener("touchstart",function(e){ keyDownHandler(e, "iz")} , false);
der.addEventListener("touchstart",function(e){ keyDownHandler(e, "der")}, false);
iz.addEventListener("touchend",function(e){ keyUpHandler(e, "iz")} , false);
der.addEventListener("touchend",function(e){ keyUpHandler(e, "der")} , false);

function keyDownHandler(e, but) {
    if(e.keyCode == 39) {
        rightPressed = true;
    }
    else if(e.keyCode == 37) {
        leftPressed = true;
    } 
    else if(but == "der"){
        rightPressed = true;
    } 
    else if(but == "iz"){
        leftPressed = true;
    }
}
function keyUpHandler(e, but) {
    if(e.keyCode == 39) {
        rightPressed = false;
    }
    else if(e.keyCode == 37) {
        leftPressed = false;
    }
    else if(but == "der"){
        rightPressed = false;
    } 
    else if(but == "iz"){
        leftPressed = false;
    }
}

function collisionDetection() {
    for(c=0; c<brickColumnCount; c++) {
        for(r=0; r<brickRowCount; r++) {
            var b = bricks[c][r];
            if(b.status == 1) {
                if(x + ballRadius >= b.x && x <= b.x+brickWidth && y + ballRadius >= b.y && y <= b.y+brickHeight) {
                    dy = -dy;
                    b.status = 0;
                    score++;
                    // gana
                    if(score == brickRowCount*brickColumnCount) {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(youWin, 0, 0, canvas.width, canvas.height);
                        clearInterval(id);
                        inicio = 0;
                    }
                }
            }
        }
    }
}

function drawBall() {
    ctx.beginPath();
    ctx.arc(x, y, ballRadius, 0, Math.PI*2);
    ctx.fillStyle = "#0095DD";
    ctx.fill();
    ctx.closePath();
}
function drawPaddle() {
   
    ctx.drawImage(pala, paddleX, canvas.height-paddleHeight, paddleWidth, paddleHeight);
}
function drawBricks() {
    for(c=0; c<brickColumnCount; c++) {
        for(r=0; r<brickRowCount; r++) {
            if(bricks[c][r].status == 1) {
                var brickX = (r*(brickWidth+brickPadding))+brickOffsetLeft;
                var brickY = (c*(brickHeight+brickPadding))+brickOffsetTop;
                bricks[c][r].x = brickX;
                bricks[c][r].y = brickY;
                ctx.beginPath();
                ctx.rect(brickX, brickY, brickWidth, brickHeight);
                ctx.fillStyle = "#0095DD";
                ctx.fill();
                ctx.closePath();
            }
        }
    }
}
function drawScore() {
    ctx.font = "16px Arial";
    ctx.fillStyle = "#0095DD";
    ctx.fillText("Score: "+score, 8, 20);
}

function draw() {
    temp += 1
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawBricks();
    drawBall();
    drawPaddle();
    drawScore();
    collisionDetection();
// toca pared
    if(x + dx > canvas.width-ballRadius || x + dx < ballRadius) {
        dx = -dx;
    }
// Toca techo
    if(y + dy < ballRadius) {
        dy = -dy;
    }
// toca la pala o se cae   
    if(y + dy >= canvas.height - paddleHeight - ballRadius) {
        if(x + ballRadius > paddleX  && x < paddleX + paddleWidth) {
            dy = -dy;
        } 
        else{
            // fin del juego
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(gameOver, 0, 0, canvas.width, canvas.height);
            clearInterval(id);
            inicio = 0;
            console.log(usuario)
            console.log(score)
            if(usuario != null && usuario != ""){
                EnvPunt();
                console.log("envia puntos")
            }

        }
    }
    
    if(rightPressed && paddleX < canvas.width-paddleWidth) {
        paddleX += 7;
    }
    else if(leftPressed && paddleX > 0) {
        paddleX -= 7;
    }
    
    x += dx;
    y += dy;
}



id = setInterval(draw, 10);

function EnvPunt(){

    console.log(usuario)
    console.log(score)

    if (window.XMLHttpRequest) {
        peticion=new XMLHttpRequest(); 
          } else if (window.ActiveXObject) {
        peticion=ActiveXObject("Microsoft.XMLHTTP"); 
          } 

          datos={}
          datos.punt = score
          datos.user = usuario
   
          peticion.onreadystatechange = function(){
        if(peticion.readyState == 4) {
          if (peticion.status == 200) {
            //  var asd=JSON.parse(peticion.responseText)
            //  console.log(asd)
            console.log(peticion.responseText)
          }
        }
          }
   
          peticion.open("POST", "./Puntuacion.php");
          peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          peticion.send("datos="+JSON.stringify(datos));
}

}





boton.addEventListener('click', function(){
if (inicio == 0){
    arkanoid();
}
    this.innerHTML = "restart";
})

    




