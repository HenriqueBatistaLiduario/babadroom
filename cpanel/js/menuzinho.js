( function( $ ) {
$( document ).ready(function() {
$('#cssmenu > ul > li > a').click(function() {
  $('#cssmenu li').removeClass('active');
  $(this).closest('li').addClass('active');	
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('fast');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    $('#cssmenu ul ul:visible').slideUp('fast');
    checkElement.slideDown('fast');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;	
  }		
});
});
} )( jQuery );


( function( $ ) {
$( document ).ready(function() {
$('div.csssubmenu > ul > li > a').click(function() {
  $('div.csssubmenu li').removeClass('subactive');
  $(this).closest('li').addClass('subactive');	
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('subactive');
    checkElement.slideUp('fast');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':hidden'))) {
    $('div.csssubmenu ul ul:visible').slideUp('fast');
    checkElement.slideDown('fast');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;	
  }		
});
});
} )( jQuery );
