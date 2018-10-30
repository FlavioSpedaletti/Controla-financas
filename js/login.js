/*
**	@desc:	PHP ajax login form using jQuery
**	@author:	programmer@chazzuka.com
**	@url:		http://www.chazzuka.com/blog
**	@date:	15 August 2008
**	@license:	Free!, but i'll be glad if i my name listed in the credits'
*/

$(document).ready(function(){

	var wrapperId 	=	'#wrapper';		// main container
	var waitId		=	'#wait';		// wait message container
	var userId		=	'#u';			// user input identifier
	var passId		=	'#p';			// password input identifier
	var waitNote	=	'Carregando...';											// loading message
	var AuthNote	=	'Autenticando...';											// authenticating message
	var jsErrMsg	=	'Usu&aacute;rio e/ou senha inv&aacute;lidos';					// clientside error message
	var postFile	=	'webservices/login.php';	// post handler

	// FirstLoad
	$(waitId).html(waitNote).fadeIn('fast',function(){
		// get request to show if session is already active
		$.getJSON(postFile, function(data){
			if(data.status==true) {
				$(waitId).hide().html('Redirecionando...').fadeIn('fast', function(){window.location=data.url;});
			} else {
				// hide message
				$(waitId).fadeOut('fast').html();				
			}
		 })
		.error(function(data) { $(waitId).html(data.responseText); })
	});

	//*/ submit handler
	$("#frmlogin").submit( function() { 
		// authenticating
		$(waitId).html(AuthNote).fadeIn();
			
		var _u = $(userId).val();	// form user
		var _p = $(passId).val();	// form id
		
		//@ valid user ( modify as needed )
		if(_u.length<4) 
			{
				$(waitId).html(jsErrMsg).fadeIn('fast',function(){ 
					$(userId).focus();
				});
			} 
		else
			{
				//@ valid password ( modify as needed )
				if(_p.length<4)
					{
						$(waitId).html(jsErrMsg).fadeIn('fast',function(){ 
							$(passId).focus();
						});
					}
				else
					{
						$.post(postFile, { u: _u, p: _p }, function(data) {
							if(data.status==true){ 
								$(waitId).html('Redirecionando...').fadeIn('fast', function(){
									window.location=data.url;
								});
							} else {
								$(waitId).html(data.message).slideDown('fast', function(){ 
									$(userId).focus(); 
								}); 
							}
						}
						,'json');
					}
			}
		return false;
	});			
	$(userId).focus();
});