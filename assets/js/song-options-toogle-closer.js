$(document).ready(function(){
    $('#optionsDropdown-toggle').click(function(){
      $('#optionsDropdown-menu').toggle();
      return false;
    });
  
    $('html').click(function() {
      $('#optionsDropdown-menu').hide();
    });
  
    $('#optionsDropdown-menu').click(function(event){
      event.stopPropagation();
    });
  });