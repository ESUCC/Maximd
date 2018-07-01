function clearRadios()
{
    $('#myform :radio:checked').each(function(){
        $(this).attr('checked', false);  
    });
}
