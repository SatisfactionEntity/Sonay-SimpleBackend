//Laden van content zonder refresh!

$(document).ready(function(){
        var trigger = $('#nav ul li a'),
            container = $('#content').fadeOut(0).fadeIn(600);
			
        trigger.on('click', function(){
          var $this = $(this),//.fadeOut(0).fadeIn(500),
            target = $this.data('target');
       
          container.load(target).fadeOut(0).fadeIn(600);
          
          return false;
        });
      });