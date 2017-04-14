/*
 * 20111208
 * this is now outdated.
 * 
 */

//function startSpellCheck(){
//    var myFrames = new Array();
////    var myFrameStr = '[';
//    var counter = 0;
//    $('iframe').each(function(){
//    	
//        // new
////        if(undefined!=$(this).attr('id')) {
////            if(counter>0) myFrameStr = myFrameStr + ', ';
////        	myFrameStr = myFrameStr + '"' + $(this).attr('id') + '"';
////    	}
//
//        // old
////    	myFrames[counter] = $(this).attr('id');
////        counter++;
//        if(undefined!=$(this).attr('id')) {
//        	myFrames[counter] = $(this).attr('id');//.replace("_iframe", "")
//            counter++;
//    	}
//    
//    });
////    myFrameStr = myFrameStr + ']';
//    
////    console.debug('myFrameStr', myFrameStr);
//    console.debug('myFrames', myFrames);
//    
////    myFrames = ["iep_form_004_goal_1-measurable_ann_goal-Editor_iframe" , 
////                "iep_form_004_goal_1-short_term_obj-Editor_iframe",
////                "iep_form_004_goal_1-stmt_of_progress-Editor_iframe"];
//    
//    
//    doSpell({    
//           ctrl:myFrames,
//           lang:'en_US',
//           onFinish:onFinish
//        });
//}
//onFinish = function(mSender){
//	for(var i=0;i<mSender.length;i++){
//		// updateInlineValueTextArea : textareaID, updateValue
//		try {
//			updateInlineValueTest(mSender[i].id.replace("_iframe", ""), $('#'+mSender[i].id).contents().find('body').html());
//		} catch (error) {
//			console.debug('updateInlineValueTest error');
//			console.debug('mSender[i]', mSender);
//			console.debug('error', error);
//		}
//		
//		
//	}
//	modified();
//};
