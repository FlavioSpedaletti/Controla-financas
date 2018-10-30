$.editable.addInputType("float", {
    element : function(settings, original) {

	var input = $("<input />");

	input.keypress(function(event) {
	    if(event.which != 44 && event.which != 13 && event.which != 8 &&
			(event.which < 48 || event.which > 57)) {
		event.preventDefault();
	    } // prevent if not comma/enter/backspace

	    if(event.which == 44 && $(this).val().indexOf(',') != -1) {
		event.preventDefault();
	    } // prevent if already dot
	});
        
    $(this).append(input);
    return(input);
    }
});
