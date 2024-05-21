function habilitarBoton() {
    var tweet = document.getElementById("tweet").value;
    var botonPostear = document.getElementById("boton-postear");
    
    if (tweet.trim() !== "") {
        botonPostear.disabled = false;
    } else {
        botonPostear.disabled = true;
    }
}

const botonAbrirPopup = document.getElementById("boton-editar-perfil");
const contenedorPopup = document.querySelector(".popup-fondo");
const popup = document.querySelector(".popup-editar-perfil");
const botonCancelar = document.getElementById("boton-cancelar-cambios");

function cerrarPopup() {
    contenedorPopup.style.visibility = "hidden";
    popup.style.visibility = "hidden";
}

botonAbrirPopup.addEventListener("click", function(){
    contenedorPopup.style.visibility = "visible";
    popup.style.visibility = "visible";
});

botonCancelar.addEventListener("click", function(e){
    e.preventDefault();
    contenedorPopup.style.visibility = "hidden";
    popup.style.visibility = "hidden";
});