$(document).ready(function () {
   $(".menu-burger").click(function () {
       $(".nav-links").toggleClass("mobile-menu");
       $("body").toggleClass("no-scroll"); // Ajoutez cette ligne
   });

   $(".close").click(function () {
       $(".nav-links").removeClass("mobile-menu");
       $("body").removeClass("no-scroll"); // Ajoutez cette ligne
   });
});
