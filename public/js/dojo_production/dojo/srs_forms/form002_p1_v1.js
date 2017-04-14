/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


function sudoRadioButton(id)
{
    if (id == 'initial_verification-1' || id == 'initial_verification-0')
    {
        $("input[name='never_verified']").attr('checked', false);
        $("#never_verified-0").attr('checked', true);
    }

    if (id == 'never_verified-1')
    {
        $("input[name='initial_verification']").attr('checked', false);
    }
}
