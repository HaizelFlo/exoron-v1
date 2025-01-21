//------- Función de configuración del formulario FAQ-------*/
function toggleAccordion(header) {
    // Seleccionar elementos
    const content = header.nextElementSibling;
    const allHeaders = document.querySelectorAll(".accordion-header");
    const allContents = document.querySelectorAll(".accordion-content");

    // Cerrar todos los demás acordeones
    allHeaders.forEach((item) => {
        if (item !== header) {
            item.classList.remove("active");
            item.nextElementSibling.style.display = "none";
        }
    });

    // Alternar el acordeón actual
    header.classList.toggle("active");
    if (header.classList.contains("active")) {
        content.style.display = "block";
    } else {
        content.style.display = "none";
    }
}

//------- Selecciona todos los enlaces del menú-------------*/
document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll(".nav-link"); 

    // Configura el índice predeterminado en la sección "Inicio"
    const defaultIndex = 0; 

    // Recupera el índice del menú seleccionado de localStorage
    const activeMenuIndex = localStorage.getItem("activeMenuIndex") !== null 
        ? localStorage.getItem("activeMenuIndex")
        : defaultIndex; // Si no existe, usa el índice por defecto

    menuItems.forEach((item, index) => {
        const svgBlue = item.querySelector(".svg-blue");
        const svgPurple = item.querySelector(".svg-purple");

        if (index == activeMenuIndex) {
            svgBlue.classList.add("active");
            svgPurple.classList.add("active");
        } else {
            svgBlue.classList.remove("active");
            svgPurple.classList.remove("active");
        }

        // Agrega el evento de clic para cada enlace
        item.addEventListener("click", function () {
            // Guarda el índice en localStorage
            localStorage.setItem("activeMenuIndex", index);

            // Actualiza la clase activa
            menuItems.forEach((menuItem, menuIndex) => {
                const menuSvgBlue = menuItem.querySelector(".svg-blue");
                const menuSvgPurple = menuItem.querySelector(".svg-purple");

                if (menuIndex === index) {
                    menuSvgBlue.classList.add("active");
                    menuSvgPurple.classList.add("active");
                } else {
                    menuSvgBlue.classList.remove("active");
                    menuSvgPurple.classList.remove("active");
                }
            });
        });
    });
});


/*---------POP del video--------*/
function openVideoPopup() {
    const modal = document.getElementById('videoModal');
    modal.style.display = 'flex';
}

function closeVideoPopup() {
    const videoModal = document.getElementById("videoModal");

    if (videoModal) {
        videoModal.style.display = "none"; // Oculta el modal

        // Pausar y reiniciar el video al cerrar
        const video = videoModal.querySelector("video");
        if (video) {
            video.pause();
            video.currentTime = 0;
        }
    }
}

// Cerrar el modal si se hace clic fuera del contenido
window.addEventListener('click', (e) => {
    const modal = document.getElementById('videoModal');
    if (e.target === modal) {
        closeVideoPopup();
    }
});

