	$('.clickme').live('click', function(event){
		$('#info').remove();
		var href = $(this).attr('href');
		$.ajax({
			url: href,
			success: function(html){
				$('<div id="info"><img src="./medias/icones/close_button.png" class="hycusclosepopup"/><div class="hycusinside">'+html+'</div></div>').hide().appendTo('#contenu').fadeIn( function() {$(this).draggable({ cursor: 'move' }); });
				
			}
			
		});
		return false;
	});
	
	$('.hycusclosepopup').live('click', function(event){
		$('#info').fadeOut( function() { $(this).remove();} );
	});
