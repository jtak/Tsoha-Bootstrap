$(document).ready(function(){
  //alert('Hello World!');
});

$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $("#vote_options_wrap"); //Fields wrapper
    var add_button      = $("#add_field_button"); //Add button ID
    var option_num 		= $("#num_of_options"); //Number of options
    
    var x = 2; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<tr> \
				        <td><input class="form-control" type="text" name="option_name[]"/></td> \
				        <td><input class="form-control" type="text" name="option_desc[]"/></td> \
				        <td><a href="#" class="btn btn-default" id="remove_field">Remove</a></td> \
                                </tr>');
            $(option_num).val(x);
        }
    });
    
    $(wrapper).on("click","#remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).closest('tr').remove(); x--; $(option_num).val(x);
    })
});