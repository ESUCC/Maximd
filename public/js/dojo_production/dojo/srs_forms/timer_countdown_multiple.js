/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


  /*
  	Author:		Robert Hashemian (http://www.hashemian.com/)
  	Modified by:	Munsifali Rashid (http://www.munit.co.uk/)
  	Modified by:	Tilesh Khatri
  	
  	
  	I don't think this file is used.
  */
  
  function StartCountDown(myDiv,myTargetDate)
  {
	console.debug('StartCountDown');
    var dthen	= new Date(myTargetDate);
    var dnow	= new Date();
    ddiff		= new Date(dthen-dnow);
    gsecs		= Math.floor(ddiff.valueOf()/1000);
    
    if(switchFlagElement = document.getElementById(myDiv + '_timer_switch'))
    	switchFlagElement.innerHTML = 1; 
    	
    CountBack(myDiv,gsecs);
  }
  function ClearCountDown(myDiv, msg)
  {
	if(switchFlagElement = document.getElementById(myDiv + '_timer_switch'))
	{
	    switchMsgHtml = '<span id="' + myDiv + '_timer_switch_msg" style="visibility:hidden">'+msg+'</span>';
		switchFlagElement.innerHTML = "-1" + switchMsgHtml;
	}
  }
  
  function Calcage(secs, num1, num2)
  {
    s = ((Math.floor(secs/num1))%num2).toString();
    if (s.length < 2) 
    {	
      s = "0" + s;
    }
    return (s);
  }
  
  function CountBack(myDiv, secs)
  {
	switchFlagMsg = "Checkout Expired One";
	switchFlag = 1;
	
	if(switchFlagElement = document.getElementById(myDiv + '_timer_switch'))
	{
		if(switchFlagMsgElement = document.getElementById(myDiv + '_timer_switch_msg'))
		{
			switchFlagMsg = switchFlagMsgElement.innerHTML;
		}
		switchFlagAndMsg = switchFlagElement.innerHTML;
//		console.debug('switchFlagAndMsg', switchFlagAndMsg, switchFlagAndMsg.replace(/(<([^>]+)>)/ig,""));
		switchFlag = switchFlagAndMsg.replace(/(<(.+))/ig,""); 
	}
//	
//	console.debug('switchFlag', switchFlag);
//	console.debug('switchFlagMsg', switchFlagMsg);

	var DisplayStr;
    var DisplayFormat = "%%M%%:%%S%%";//%%D%% Days %%H%%:%%M%%:%%S%%
    DisplayStr = DisplayFormat.replace(/%%D%%/g,	Calcage(secs,86400,100000));
    DisplayStr = DisplayStr.replace(/%%H%%/g,		Calcage(secs,3600,24));
    DisplayStr = DisplayStr.replace(/%%M%%/g,		Calcage(secs,60,60));
    DisplayStr = DisplayStr.replace(/%%S%%/g,		Calcage(secs,1,60));
    
    if(secs > 0 && 1 == switchFlag)
    {	
    	// clock running
//    	console.debug('clock running');
    	switchHtml = '<span id="' + myDiv + '_timer_switch" style="visibility:hidden">'+switchFlag+'</span>';
    	document.getElementById(myDiv).innerHTML = DisplayStr + ' minutes'+ switchHtml;
    	setTimeout("CountBack('" + myDiv + "'," + (secs-1) + ");", 990);
    }
    else if(-1 == switchFlag)
    {
    	// ClearCountDown called
//    	console.debug('ClearCountDown called');
        document.getElementById(myDiv).innerHTML = switchFlagMsg;
    } else {
    	// ClearCountDown called
    	//console.debug('TIMED OUT');
    	document.getElementById(myDiv).innerHTML = switchFlagMsg;
      
    	// set checkout to false so we relase this form
    	dojo.byId("zend_checkout").value = 0;
    	//checkoutTimout("/form004/jsonupdateiep");
    }
  }
