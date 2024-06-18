document.addEventListener("DOMContentLoaded", function () {
  // Gestion de la Navbar
  let navbar = document.querySelector(".header .flex .navbar");
  let profile = document.querySelector(".header .flex .profile");

  document.querySelector("#menu-btn").addEventListener("click", () => {
    navbar.classList.toggle("active");
    profile.classList.remove("active");
  });

  document.querySelector("#user-btn").addEventListener("click", () => {
    profile.classList.toggle("active");
    navbar.classList.remove("active");
  });

  window.onscroll = () => {
    navbar.classList.remove("active");
    profile.classList.remove("active");
  };

  // Gestion des quick view
  let mainImage = document.querySelector(
    ".quick-view .box .row .image-container .main-image img"
  );
  let subImages = document.querySelectorAll(
    ".quick-view .box .row .image-container .sub-image img"
  );

  subImages.forEach((image) => {
    image.addEventListener("click", () => {
      let src = image.getAttribute("src");
      mainImage.src = src;
    });
  });

  // Gestion affichage mot de passe
  // const eyeOn = document.querySelector(".eye-on");
  // const eyeOff = document.querySelector(".eye-off");
  // const inputPassword = document.querySelector("#inputPassword");

  // eyeOn.addEventListener("click", () => {
  //   eyeOn.style.display = "none";
  //   eyeOff.style.display = "block";
  //   inputPassword.type = "text";
  // });

  // eyeOff.addEventListener("click", () => {
  //   eyeOff.style.display = "none";
  //   eyeOn.style.display = "block";
  //   inputPassword.type = "password";
  // });

  // Gestion des Menus déroulants
  const toggles = document.querySelectorAll(".toggle");

  toggles.forEach((toggle) => {
    toggle.addEventListener("click", function () {
      const targetId = this.getAttribute("data-target");
      const content = document.getElementById(targetId);

      if (content) {
        content.classList.toggle("show-cgv");
                  this.classList.toggle("open");
      }
    });
  });
});

     // Gestion de l'animation d'ajout au panier
    // const addToCartLinks = document.querySelectorAll('.add-to-cart');

    // addToCartLinks.forEach(link => {
    //     link.addEventListener('click', event => {
    //         event.preventDefault(); // Empêcher la navigation vers l'URL spécifiée

    //         // Ajoutez ici votre animation pour l'ajout au panier
    //         link.classList.add('added-to-cart');

    //         // Optionnel : attendez un moment pour simuler une animation
    //         setTimeout(() => {
    //             link.classList.remove('added-to-cart');
    //         }, 1000); // 1000 millisecondes (1 seconde) - ajustez selon votre animation

    //         // Optionnel : pouvez également ajouter une notification, etc.
    //     });
    // });


  function togglePasswordVisibility() {
            var passwordInput = document.getElementById("inputPassword");
            var icon = document.querySelector(".password-toggle i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
}
      






// console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
