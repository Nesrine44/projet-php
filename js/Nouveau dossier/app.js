$(document).ready(function(){
	$('.btn-danger').on('submit', function(e){

		e.preventDefault();  
		var $a = ($this);
		var url = $a.attr('href'); 
		$.ajax(url);                                                                                                                                                                                                 
	});
 
});