 $("a.test").click(function() {
			  	s=$(this).attr("href");
				$.ajax({
				url: s,
			    success: function(retour){
				 	$("#contenu").empty().append(retour);},
			   	error: function (xhr, ajaxOptions, thrownError){
						alert(s);
						alert(xhr.status);
						alert(thrownError);
					}
				});
				return false;
			});