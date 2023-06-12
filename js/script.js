(function ($) {
  "use strict"

  /* 2. sticky And Scroll UP */
  $(window).on('scroll', function () {
      var scroll = $(window).scrollTop();
      if (scroll > 500) {
        $(".user-scroll").addClass("active");
        console.log(scroll);
      } else {
        $(".user-scroll").removeClass("active");
        console.log("class");
      }
  });

})(jQuery);

$(document).ready(function() {
    // Get the current URL
    var currentUrl = window.location.href;
  
    // Loop through each nav-link
    $('.nav-link').each(function() {
      var linkUrl = $(this).attr('href');
  
      // Check if the URL contains the href attribute
      if (currentUrl.indexOf(linkUrl) !== -1) {
        // Add the "disabled" class to the matched nav-link
        $(this).addClass('disabled');
      }
    });
  });

function togglePasswordVisibility(inputId, iconId) {
    var passwordInput = document.getElementById(inputId);
    var passwordIcon = document.getElementById(iconId);

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        passwordIcon.classList.remove("fa-eye");
        passwordIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        passwordIcon.classList.remove("fa-eye-slash");
        passwordIcon.classList.add("fa-eye");
    }
}


var images = document.getElementsByTagName('img');

  for (var i = 0; i < images.length; i++) {
    var image = images[i];

    image.addEventListener('load', function() {
        if(image.classList.contains('placeholder')){
            image.classList.remove('placeholder');
            console.log('removed');
        }
      console.log('Image loaded successfully');
    });

    image.addEventListener('error', function() {
        image.classList.add('placeholder');
      console.log('Failed to load image');
    });
  }  