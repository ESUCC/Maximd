    dojo.xhrGet( {
        // The following URL must match that used to test the server.
        url: '/form'+formNum+'/myrecords/myrecordsType/'+myrecordsType,
        handleAs: "json",
        sync: true,
        load: function(data, ioArgs) {
            // Now you can just use the object
			// after the page loads, disable the save button
            // get items from the json object
//        	c = json_decode(json);
            console.debug('data', data);
            
            var returneditems = data.items;
        	dojo.forEach(data.items, function(item){
        		// loop through properties
            	for(var propertyName in item) {
            		// propertyName is what you want
         		    // you can get the value like this: myObject[propertyName]
            		console.debug(propertyName, 'propertyName');
            	}
        	});
			return returneditems;
        }
        // More properties for xhrGet...
    });    
