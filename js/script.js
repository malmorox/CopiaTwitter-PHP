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

botonAbrirPopup.addEventListener("click", function(){
    contenedorPopup.style.visibility = "visible";
    popup.style.visibility = "visible";
});