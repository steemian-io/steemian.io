( function( $ ) {

  $(document).ready(function($){

    // Trigger mmenu
    $('#mob-menu').mmenu();

    // Search in Header
    if( $('#btn-search-icon').length > 0 ) {
      $('#btn-search-icon').click(function(e){
          e.preventDefault();
          $("#header-search-form").fadeToggle();
      });

    }
    // Implement go to top
    if ( $('#btn-scrollup').length > 0 ) {

      $(window).scroll(function(){
          if ($(this).scrollTop() > 100) {
              $('#btn-scrollup').fadeIn('slow');
          } else {
              $('#btn-scrollup').fadeOut('slow');
          }
      });

      $('#btn-scrollup').click(function(){
          $("html, body").animate({ scrollTop: 0 }, 600);
          return false;
      });

    }


  });

} )( jQuery );
