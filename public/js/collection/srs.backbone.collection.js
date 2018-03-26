/**
 * Configure Marionette to use Mustache templates instead of underscore
 */
Backbone.Marionette.TemplateCache.prototype.compileTemplate = function(rawTemplate) {
    return Mustache.compile(rawTemplate);
};
Backbone.emulateHTTP=true;

Backbone.Marionette.Region.Dialog = Backbone.Marionette.Region.extend({
    onShow: function(view){
        var that = this;
        var config = view.dialogOpts || {};
        var options = $.extend(this.getDefaultOptions(), {
            close: function(e, ui){
                that.closeDialog();
            },
            open: function(){
                if ( 'onSaveDf' in view)
                    $(this).dialog().data('onSaveDf', view.onSaveDf);
                if ( 'onShowDf' in view)
                    view.onShowDf.resolve.call(view);
            }
        }, config);
        this.$el.dialog(options);
    },
    getDefaultOptions: function(options){
        //console.debug('getDefaultOptions', options);
        options = options || {};
        var that = this;
        return $.extend({
            title: "Default Title",
            modal: true,
            buttons: {
                "Save": function(){
                    $(this).dialog('close');
                }
            },
            close: function(e, ui){
                that.closeDialog();
            }
        }, options);
    },
    closeDialog: function(){
        this.close();
        this.$el.dialog('destroy');
    }
});

var App = new Backbone.Marionette.Application();
App.addRegions({
    collectionListContainer: "#bbCollectionListWrapper",
    studentCollectionManager: "#bbStudentCollectionManager",
    groupContainer: "#bbGroupContainer",
    dialog: Backbone.Marionette.Region.Dialog.extend({el: '#dialog-region'})
});

App.AddCollectionDialog = Backbone.Marionette.ItemView.extend({
    template: '#add-collection',
    id: 'addCollectionDialog',
    dialogOpts: {
        width:400,
        buttons: {
            "Cancel": function(){
                $(this).dialog('close');
            },
            "Create": function(){
                var self = $(this);
                $('.ui-dialog-buttonpane').hide();

                var overwrite = $(this).find('.collection_overwrite').is(':checked') ? 1 : 0;
                var closeDialog = false;
                var collectionName = $(this).find('.collection_name').val();

                var collectionItem = new App.CollectionItem();
                collectionItem.save({name : collectionName},{
                    success :function(xhr, returnObject) {
                        //console.debug('add success', returnObject.id);
                        self.dialog('close');
                        $('#studentCollectionMenu').val(returnObject.id);
                        /**
                         * reboot app to display new settings
                         */
                        App.collectionManager.initialize();
                        localStorage.setItem("studentCollectionMenuValue",returnObject.id);
                        localStorage.setItem("studentCollectionMenuText", collectionName);
                    },
                    error: function(xhr, textStatus) {
                        console.debug('error', xhr);
                        console.debug('error', textStatus);
                    }
                });
                return false;
                var data = $(this).find('form');
            }
        },
        title: "Create Collection Bro"
    }
});

/**
 * models
 * ====================================================================================================================
 */
    /**
     * student
     */
    App.Student = Backbone.Model.extend({
        methodToURL: {
            'read': '/api-student/get',
            'create': '/api-student/create',
            'update': '/api-student/add-student-to-collection',
            'delete': '/api-student/remove-student-from-collection'
        },
        sync: function(method, model, options) {
            options = options || {};
            if('create' == method.toLowerCase()) {
                options.url = model.methodToURL[method.toLowerCase()]+'/name/'+model.get('name');
            } else if('update' == method.toLowerCase()) {
                options.url = model.methodToURL[method.toLowerCase()]+'/id/'+model.get('id')+'/collectionName/'+$('#studentCollectionMenu').find('option[value='+$('#studentCollectionMenu').val()+']').html().trim();
            } else if('delete' == method.toLowerCase()) {
                options.url = model.methodToURL[method.toLowerCase()]+'/id/'+model.get('id')+'/collectionName/'+$('#studentCollectionMenu').find('option[value='+$('#studentCollectionMenu').val()+']').html().trim();
            } else {
                options.url = model.methodToURL[method.toLowerCase()]+'/id/'+model.get('id');
            }
            return Backbone.sync(method, model, options);
        }
    });

/**
 * collection item
 */
    App.CollectionItem = Backbone.Model.extend({
        methodToURL: {
            'read': '/api-collection/get',
            'create': '/api-collection-item/create',
            'update': '/api-collection-item/update',
            'delete': '/api-collection-item/delete'
        },
        sync: function(method, model, options) {
            options = options || {};
            if('create' == method.toLowerCase()) {
                options.url = model.methodToURL[method.toLowerCase()]+'/name/'+model.get('name');
            } else {
                options.url = model.methodToURL[method.toLowerCase()]+'/id/'+model.get('id');
            }
            return Backbone.sync(method, model, options);
        }
    });

    /**
     * student collection
     */
    App.StudentCollection = Backbone.Collection.extend({
        model: App.Student,
        url: '/api-student',
        methodToURL: {
            'read': '/api-student/get-students-in-collection',
            'create': '/api-student/create',
            'update': '/api-student/update',
            'delete': '/api-student/remove'
        },
        initialize: function(options) {
            setFromStorage = false
            if(typeof(Storage) !== "undefined") {
                // Code for localStorage/sessionStorage.
                if('' != localStorage.getItem("studentCollectionMenuValue")) {
                    if(App.collectionManager.checkIfItemExistsInCollectionMenu(this.id, this.name)) {
                        setFromStorage = true
                    }
                }
            }
            if(setFromStorage) {
                this.id = localStorage.getItem("studentCollectionMenuValue");
                this.name = localStorage.getItem("studentCollectionMenuText");
            } else {
                if(options['name']) {
                    this.name= options['name'];
                }
                if(options['id']) {
                    this.id= options['id'];
                }
            }
        },
        sync: function(method, model, options) {
            //console.debug('App.StudentCollection sync', method, model, options);
            options = options || {};
            options.url = model.methodToURL[method.toLowerCase()]+'/id/'+model.id+'/name/'+model.name;
            return Backbone.sync(method, model, options);
        },
    });

    App.Collection = Backbone.Collection.extend({
        model: App.CollectionItem,
        url: '/api-collection',
        methodToURL: {
            'read': '/api-collection/get',
            'create': '/api-collection/create',
            'update': '/api-collection/update',
            'delete': '/api-collection/remove'
        },
        sync: function(method, model, options) {
//            console.debug('App.Collection sync', method, model, options);
            options = options || {};
            options.url = model.methodToURL[method.toLowerCase()];
            return Backbone.sync(method, model, options);
        },
    });

/**
 * end models
 * ====================================================================================================================
 * ====================================================================================================================
 */

/**
 * views
 * ====================================================================================================================
 */
/**
 * Student Row
 */
App.StudentView = Backbone.Marionette.ItemView.extend({
    template: "#student-template",
    tagName: "li",
    model: App.Student,
    initialize: function(){
        this.model.on('destroy', this.remove, this);
    }
});
/**
 * Collection of Student Rows
 */
App.StudentCollectionView = Backbone.Marionette.CollectionView.extend({
    id:"studentCollectionList",
    tagName: "ul",
    itemView: App.StudentView,
    events: {
        'click a.groupCollectionDelete': function(e, msg){
            var studentId = $(e.target).attr('data-student-id');

            var studentCollectionItem = this.collection.get(studentId);
            this.collection.remove(studentCollectionItem);
            studentCollectionItem.destroy({
                success:function(xhr, result) {
                    var listCheckbox = $('.collectionCheckbox[data-student-id='+studentId+']');
                    listCheckbox.prop('checked', false);
                    App.collectionManager.conditionalDisplayStudentCollectionOperations();
                }
            });
        },
        'click a.groupCollectionEdit': function(e, msg){
            var studentId = $(e.target).attr('data-student-id');
            window.location.href = '/student/edit/id_student/'+studentId;
            return false;
        },
        'click a.collectionLink': function(e, msg){
            var studentId = $(e.target).attr('data-student-id');
            var clickStudentLink = $('#clickStudentLink').val();
            if('viewStudent' == clickStudentLink) {
                window.location.href = '/student/search-forms/id_student/'+studentId;
            } else if('nssrsReport' == clickStudentLink) {
                window.location.href = '/report/nssrs/id_student/'+studentId;
            }
            return false;
        }
    },
    toggleStudentSelected: function (studentModel, checkboxElement, options) {
        var studentCollectionItem = this.collection.get(studentModel.id);
        if(undefined == studentCollectionItem) {
            // it is not in the list
            studentModel.save({id:studentModel.id, name:'Refreshing'});
        } else {
            // it's in the list
            this.collection.remove(studentCollectionItem);
            studentCollectionItem.destroy();
        }
    }

});

/**
 * select menu and options
 * @type {*}
 */
App.SelectCollectionOption = Backbone.Marionette.ItemView.extend({
    template: "#select-collection-option",
    tagName: "option",
    model: App.Collection,
    constructor : function (options) {
        this.attributes = {
            value : options.model.id
        };
        if(typeof(Storage) !== "undefined") {
            // Code for localStorage/sessionStorage.
            if('' != localStorage.getItem("studentCollectionMenuValue") && localStorage.getItem("studentCollectionMenuValue") == options.model.id) {
                this.attributes.selected = true;
            }
        }
        Backbone.Marionette.ItemView.prototype.constructor.apply(this, arguments);
    }
});
App.SelectCollectionMenu = Backbone.Marionette.CollectionView.extend({
    id:"studentCollectionMenu",
    tagName: "select",
    itemView: App.SelectCollectionOption,
    initialize: function(options) {
        if(options.studentCollectionView) {
            this.studentCollectionView = options.studentCollectionView
            //console.debug('this.studentCollectionView', this.studentCollectionView);
        }
    },
    events: {
        'change': function(e){
            /**
             * update name and id and refresh the collection
             * @type {*|jQuery}
             */
            this.studentCollectionView.collection.id = $(e.target).val();
            this.studentCollectionView.collection.name = $(e.target).find('option[value='+$(e.target).val()+']').html().trim();
            this.studentCollectionView.collection.fetch({
                success:function() {
                    App.matchCheckedStateToList();
                }
            });

            if(typeof(Storage) !== "undefined") {
                // Code for localStorage/sessionStorage.
                localStorage.setItem("studentCollectionMenuValue", $('#studentCollectionMenu option:selected').val());
                localStorage.setItem("studentCollectionMenuText", $.trim($('#studentCollectionMenu option:selected').text()));
            }

        }
    }
});

/**
 * Collection Row
 */
App.CollectionItemView = Backbone.Marionette.ItemView.extend({
    template: "#collection-item",
    tagName: "li",
    model: App.Collection,
    initialize: function(){
        this.model.on('destroy', this.remove, this);
    },
    ui: {
        deleteLink: '.deleteLink',
    },
    events: {
        'click .deleteLink': function(e){
            if ( !confirm('Are you sure you want to delete this collection?')) return;

            /**
             * remove item from select menu
             */
            $("#studentCollectionMenu option[value='"+this.model.get('id')+"']").remove();
            this.model.destroy();

            /**
             * reboot app to display new settings
             */
            App.collectionManager.initialize();
        }
    },
    remove: function(){
        this.$el.remove();
    }
});

/**
 * Collection of Collection Rows
 * Used to manage collections (currently just delete collection)
 */
App.CollectionsView = Backbone.Marionette.CollectionView.extend({
    id:"listOfStudentsInCurrentCollection",
    tagName: "ul",
    itemView: App.CollectionItemView,
});

/**
 * end views
 * ====================================================================================================================
 * ====================================================================================================================
 */
App.CollectionManagerLayout = Backbone.Marionette.Layout.extend({
    template: '#bbCollectionManager',
    selectCollectionMenu: null,
    regions: {
        listOfStudentsInCurrentCollection: '#listOfStudentsInCurrentCollection',
        currentCollectionMenu: '#currentCollectionMenu'
    },
    ui: {
        collectionOperations: '.collectionOperations',
        studentCollectionOperations: '.studentCollectionOperations',
        addCollection: 'a.addCollectionLink'
    },
    events: {
        'click a.addCollectionLink': function(event){
            App.dialog.show(new App.AddCollectionDialog({}));
            return false;
        },
        'change select#groupAction': function(event){
            if('print'==$('#groupAction').val()) {
                App.groupPrint(); // defined below
            } else if('transfer'==$('#groupAction').val()) {
                App.groupTransfer(); // defined below
            } else if('assign-team-member'==$('#groupAction').val()) {
                App.assignTeamMember();
            } else if('assign-case-manager'==$('#groupAction').val()) {
                App.assignCaseManager();
            } else if('nssrs-report'==$('#groupAction').val()) {
                if('' != $('.collectionLink').first().data('student-id')) {
                    var studentId = $('.collectionLink').first().data('student-id');
                    window.location.href = '/report/nssrs/id_student/'+studentId;
                }
            }

            /**
             * clear value to return menu to selectable state
             */
            $('#groupAction').val('');
        }
    },
    conditionalDisplayStudentCollectionOperations: function () {
        /**
         * if the student list has any values, show the
         * batch operations menu
         */
        if($('#studentCollectionList li').size() > 0) {
            $(this.ui.studentCollectionOperations).show();
        } else {
            $(this.ui.studentCollectionOperations).hide();
        }
    },
    checkIfItemExistsInCollectionMenu: function (value, name) {
        this.collectionsCollection.each(function(model, index){
            if(model.id == value && model.attributes.name == name) {
                return true;
            }
        });
        return false;
    },
    showHideCollectionMenu: function () {
        /**
         * if the student list has any values, show the
         * batch operations menu
         */
        if(undefined == this.collectionsCollection || 0 == this.collectionsCollection.length) {
            $(this.ui.collectionOperations).hide();
        } else {
            var selectCollectionMenu = new App.SelectCollectionMenu({
                collection: this.collectionsCollection,
                studentCollectionView: this.studentCollectionView
            });
            this.currentCollectionMenu.show(selectCollectionMenu);

            $(this.ui.collectionOperations).show();
        }
    },
    showHideStudentListCheckboxes: function() {
        /**
         * enable checkboxes if there any values in the collection select
         */
        if($('#studentCollectionMenu').val()) {
            App.checkboxDisplay(true);
        } else {
            App.checkboxDisplay(false);
        }
    },
    showHideManageTab: function() {
        /**
         * enable checkboxes if there any values in the collection select
         */
        if(undefined == this.collectionsCollection || 0 == this.collectionsCollection.length) {
            $('#collectionTabs').tabs('select', 0);
            $('#collectionTabs').tabs('disable', 1);

        } else {
            $('#collectionTabs').tabs('enable', 1);
        }
    },
    initialize: function (params) {
        $('#studentCollectionList').hide();
        $(this.ui.studentCollectionOperations).hide();

        var self = this;
        var collections = new App.Collection();
        collections.fetch({
            success: function(collections, response) {
                App.processCollections(collections, response, self);
            },
            error: function() { console.log('errorLogger', arguments); }
        });
    },
    reRenderInterface: function() {
        //console.debug('reRenderInterface');

        // menu
        this.conditionalDisplayStudentCollectionOperations();

        this.showHideStudentListCheckboxes();
        App.matchCheckedStateToList();

        this.showHideCollectionMenu();

        this.showHideManageTab();

    }
});
App.processCollections = function(collections, response, collectionManagerLayout) {
//    console.debug('processCollections', response.length);
    if(response.length) {
        /**
         * put collection of collections into the main layout
         */
        collectionManagerLayout.collectionsCollection = collections;

        /**
         * enable display of list on manage tab
         */
        var collectionsView = new App.CollectionsView({
            collection: collections
        });
        App.collectionListContainer.show(collectionsView);

        if(null != localStorage.getItem("studentCollectionMenuValue")) {
        	var collection_menu_id = localStorage.getItem("studentCollectionMenuValue");
            var collection_menu_name = localStorage.getItem("studentCollectionMenuText");
        } else {
        	var collection_menu_id = response[0].id_collection;
            var collection_menu_name = response[0].name;
        }

        var studentCollection = new App.StudentCollection({
            id: collection_menu_id,
            name: collection_menu_name
        });
        studentCollection.fetch({
            error: function() { console.log('errorLogger', arguments); },
            success: function(collectionOfStudents, studentCollectionResponse) {
                App.processStudentCollection(collectionOfStudents, studentCollectionResponse, collectionManagerLayout);
            }
        });
    } else {
        /**
         * empty the existing student list
         */
        $('#studentCollectionList li').remove();
        collectionManagerLayout.reRenderInterface();
    }
}

App.processStudentCollection = function(collectionOfStudents, response, collectionManagerLayout) {
//    console.debug('App.processStudentCollection', collectionOfStudents.length, collectionOfStudents);

    collectionManagerLayout.studentCollection = collectionOfStudents;

    if(collectionOfStudents.length > 0) {
        $(collectionManagerLayout.ui.studentCollectionOperations).show();
    }

    /**
     * build list of students in selected collection
     * @type {App.StudentCollectionView}
     */
    var studentCollectionView = new App.StudentCollectionView({ collection: collectionOfStudents });
    collectionManagerLayout.listOfStudentsInCurrentCollection.show(studentCollectionView);
    collectionManagerLayout.studentCollectionView = studentCollectionView;

    /**
     * build collection menu
     */
    var selectCollectionMenu = new App.SelectCollectionMenu({
        collection: collectionManagerLayout.collectionsCollection,
        studentCollectionView: studentCollectionView
    });
    collectionManagerLayout.currentCollectionMenu.show(selectCollectionMenu);

    App.collectionManager.reRenderInterface();
}

App.matchCheckedStateToList = function () {
    //console.debug('App.matchCheckedStateToList');
    /**
     * remove all checkboxes
     */
    $('input.collectionCheckbox:checkbox').attr('checked', false);
    /**
     * check checkboxes that match ids in collectionList
     */
    var count = 0;
    $('.collectionLink').each(function(index, link) {
        $("input:checkbox[data-student-id='"+$(link).attr('data-student-id')+"']").attr('checked', true);
        count += 1;
    });
    if(0 == count) {
        $('.studentCollectionOperations').hide();
    } else {
        //console.debug('studentCollectionOperations show 2');
        $('.studentCollectionOperations').show();
    }
}

/**
 * display checkboxes next to search results rows of students
 * @param toggle
 */
App.checkboxDisplay = function (toggle) {
    if(toggle) {
        /**
         * remove checkboxes and the checkall/uncheckall links
         * this ensures we don't get a double add
         */
        $('.collectionCheckbox').parent().remove();
        if($('#CollectionCheckAll').length > 0) {
            $('#CollectionCheckAll').closest('.checkbox').remove();
        }

        /**
         * add checkboxes to left of student list
         * scrape student_id from the options list
         * which displays regardless of output type
         */
        $('.studentOptions').each(function(index, item) {
            var studentId = App.getStudentIdFromStudentOptionsList(item);
            $(item).closest('ul').prepend('<li class="checkbox">' +
                '<input type="checkbox" class="collectionCheckbox" data-student-id="'+studentId+'">' +
                '</li>');
        });
        /**
         * add check all and uncheck all
         */
        if($('#CollectionCheckAll').length == 0) {
            $('#searchPseudoTable li.head').prepend('<li class="checkbox">' +
                '<a href="#" id="CollectionCheckAll">Check All</a>' +
                '/' +
                '<a href="#" id="CollectionUncheckAll">Uncheck All</a>' +
                '</li>');
        }
    } else {
        /**
         * remove checkboxes and the checkall/uncheckall links
         */
        $('.collectionCheckbox').parent().remove();
        if($('#CollectionCheckAll').length > 0) {
            $('#CollectionCheckAll').closest('.checkbox').remove();
        }
    }
}

/**
 * add/remove to/from collection checkbox
 * called when checkbox is clicked by the user
 * @param checkboxElement
 */
App.clickCheckbox = function (checkboxElementDepreciated, e) {
    //console.debug('clickCheckbox');
    var checkboxElement = $(e.target);
    var studentId = App.getStudentIdFromStudentOptionsList(checkboxElement.closest('ul').find('.studentOptions'));
    var studentModel = new App.Student({id:studentId});
    var existingItem = App.collectionManager.studentCollectionView.collection.get(studentId);
    //console.debug('existingItem', existingItem);
    if(undefined == existingItem) {
        studentModel.save({id:studentModel.id, name:'Refreshing'}, {
            success:function(newStudent, result) {
                newStudent.name = result.name;
                App.collectionManager.studentCollectionView.collection.add(newStudent);
                checkboxElement.prop('checked', true);

                App.matchCheckedStateToList();
                App.collectionManager.conditionalDisplayStudentCollectionOperations();

            }
        });
    } else {
        App.collectionManager.studentCollectionView.collection.remove(existingItem);
        existingItem.destroy({
            success:function(student, result) {
                checkboxElement.prop('checked', false);

                App.matchCheckedStateToList();
                App.collectionManager.conditionalDisplayStudentCollectionOperations();

            }
        });
    }

}

App.groupPrint = function () {
    $('#groupPrint').dialog({
        title:'Batch Printing Options',
        modal:true,
        width:400,
        buttons: [
            {
                text: "Cancel",
                "class": 'jqueryUiButton',
                click: function() {
                    // Cancel code here
                    $(this).dialog("close");
                }
            },
            {
                text: "Print",
                "class": 'jqueryUiButton',
                click: function() {
                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        url:'/student/do-group/collection/'+$('#studentCollectionMenu').find('option[value='+$('#studentCollectionMenu').val()+']').html().trim()+'/run/print/printType/'+$('#printType').val()+'/formNum/'+$('#formNum').val(),
                        success: function(json) {
//                            console.debug('json', json.success, json.errorMessage);
                            if(1==json.success && false!=json.job) {
                                $('#printJobs').html('<li id="refresh_job_'+json.job+'" >waiting for print job...</li>');
                                App.waitForJobCompletion(json.job, json.fileName);
                            } else{
                                console.debug('failed to add the job to the queue.');
                                alert('Error:'+json.errorMessage);
                            }

                        },
                        error: function(json) {
                            console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/'+$('#printType').val()+'/formNum/'+$('#formNum').val());
                        }
                    });
                    $(this).dialog("close");
                }
            }
        ]
    });
}
App.groupTransfer = function () {
    $('#groupTransfer').dialog({
        title:'Batch Transfer Options',
        modal:true,
        width:400,
        open: function() {
            $('#id_county').trigger('change');
        },
        buttons: [
            {
                text: "Cancel",
                "class": 'jqueryUiButton',
                click: function() {
                    // Cancel code here
                    $(this).dialog("close");
                }
            },
            {
                text: "Transfer",
                "class": 'jqueryUiButton',
                click: function() {
                    /**
                     * be sure all elements are filled out
                     */

                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        url:'/student/do-group/collection/'+$('#studentCollectionMenu').find('option[value='+$('#studentCollectionMenu').val()+']').html().trim()+'/run/transfer/county/'+
                            $('#id_county').val()+'/district/'+$('#id_district').val()+'/school/'+
                            $('#id_school').val()+'/autoMoveForAsmOrBetter/'+$('#autoMoveForAsmOrBetter').is(':checked'),
                        success: function(json) {
//                            console.debug('json', json.success, json.errorMessage);
                            if(1==json.success && false!=json.job) {
                                if(''!=json.message) {
                                    $.growlUI('Success', json.message, 8000);
                                } else {
                                    $.growlUI('Success', 'Student transfers initiated.', 8000);
                                }
                            } else{
                                console.debug('An error occured in the transfer.');
                                $.growlUI('Errors', json.errorMessage, 30000);
                            }

                        },
                        error: function(json) {
                            console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/'+$('#printType').val()+'/formNum/'+$('#formNum').val());
                        }
                    });
                    $(this).dialog("close");
                }
            }
        ]
    });
}
App.assignCaseManager = function () {
    var areaMain = $('#assignCaseManager>div.areaMain');
    var areaMessage = $('#assignCaseManager>div.areaFetchingMsg');
    var submitButton = $('#assignCaseManager').next('submitButton');

    $('#assignCaseManager').dialog({
        title: 'Assign Case Manager',
        modal: true,
        width: 400,
        open: function() {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/api-student/assign-case-manager/collection/'+$('#studentCollectionMenu').find('option[value='+$('#studentCollectionMenu').val()+']').html().trim(),
                success: function (json) {
                    if(1 == json.success) {
                        /**
                         * hide message
                         */
                        areaMessage.hide();
                        var caseManagers = json.data.case_managers;

                        /**
                         * enable submit button
                         */
                        $('#assignCaseManager').parent().find('button.submitButton').removeAttr('disabled').removeClass( 'ui-state-disabled' );

                        /**
                         * update select
                         */
                        var options = '';
                        var optionsPrefix = '<option value="">Choose a case manager</option>';
                        var size = 0;
                        var selected = '';
                        var selectionMade = false;
                        $.each(caseManagers, function(optionValue, optionDisplay) {
                            options += '<option value="' + optionValue + '">' + optionDisplay + '</option>';
                            size += 1;
                        });
                        options = optionsPrefix+options;
                        areaMain.find('select.caseMgr').html(options);


                        $('#assignCaseManager>div.areaFetchingMsg').hide();
                        $('#areaMain>div.areaFetchingMsg').hide();
                    } else {
                        areaMessage.html(json.errorMessage);
                        areaMessage.show();
                    }
                },
                error: function (json) {
                    console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/' + $('#printType').val() + '/formNum/' + $('#formNum').val());
                    areaMessage.html(json.errorMessage);
                    areaMessage.show();
                }
            });

        },
        buttons: [
            {
                text: "Cancel",
                "class": 'jqueryUiButton',
                click: function () {
                    // Cancel code here
                    $(this).dialog("close");
                }
            },
            {
                text: "Assign Selected Case Manager",
                "class": 'jqueryUiButton submitButton',
                disabled:"disabled",
                click: function () {
                    var areaMain = $('#assignCaseManager>div.areaMain');

                    /**
                     * be sure all elements are filled out
                     */
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: '/api-student/assign-case-manager/collection/'+$('#studentCollectionMenu').find('option[value='+$('#studentCollectionMenu').val()+']').html().trim() +
                            '/id_case_mgr/' + areaMain.find('select.caseMgr').val(),
                        success: function (json) {
                            if (1 == json.success) {
                                if ('' != json.message) {
                                    $.growlUI('Success', json.message, 10000);
                                } else {
                                    $.growlUI('Success', 'Student transfers initiated.', 10000);
                                }
                            } else {
                                console.debug('An error occured in the transfer.');
                                $.growlUI('Error', json.errorMessage, 2000);
                            }
                        },
                        error: function (json) {
                            console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/' + $('#printType').val() + '/formNum/' + $('#formNum').val());
                        }
                    });
                    $(this).dialog("close");
                }
            }
        ]
    });
}
App.assignTeamMember = function (studentId) {

    // init
    var areaMain = $('#assignTeamMember>div.areaMain');
    var areaMessage = $('#assignTeamMember>div.areaFetchingMsg');
    var submitButton = $('#assignTeamMember').next('submitButton');
    areaMain.hide();
    areaMessage.show();

    $('#assignTeamMember').dialog({
        title: 'Assign Team Member',
        modal: true,
        width: 400,
        open: function() {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/api-student/assign-team-member/collection/'+$('#studentCollectionMenu').find('option[value='+$('#studentCollectionMenu').val()+']').html().trim(),
                success: function (json) {
                    /**
                     * hide message
                     */
                    areaMessage.hide();

                    if(1 == json.success) {

                        var student = json.data.students;
                        var possibleTeamMembers = json.data.possibleStudentTeamMembers;

                        if(0 == possibleTeamMembers.length) {
                            // nothing to choose
                            areaMessage.html('There are no personnel with privileges to be a team member for this student.');
                            areaMessage.show();
                        } else {
                            /**
                             * update display
                             */
//                            areaMain.find('span.studentName').html('<b>'+student.name_first + ' ' + student.name_last+'</b>');

                            /**
                             * enable submit button
                             */
                            $('#assignTeamMember').parent().find('button.submitButton').removeAttr('disabled').removeClass( 'ui-state-disabled' );
                            /**
                             * update select
                             */
                            var options = '';
                            var optionsPrefix = '<option value="">Choose a team member</option>';
                            var size = 0;
                            var selected = '';
                            var selectionMade = false;
                            $.each(possibleTeamMembers, function(optionValue, optionDisplay) {
                                options += '<option value="' + optionValue + '"' + selected + '>' + optionDisplay + '</option>';
                                size += 1;
                            });
                            if(!selectionMade) {
                                options = optionsPrefix+options;
                            }

                            /**
                             * inject params into dialog and show
                             */
                            areaMain.find('select.teamMember').html(options);
                            areaMain.find('input.studentId').attr('value', studentId);
                            areaMessage.hide();
                            areaMain.show();

                        }

                    } else {
                        areaMessage.html(json.errorMessage);
                        areaMessage.show();
                    }
                },
                error: function (json) {
                    console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/' + $('#printType').val() + '/formNum/' + $('#formNum').val());
                }
            });

        },
        buttons: [
            {
                text: "Cancel",
                "class": 'jqueryUiButton',
                click: function () {
                    // Cancel code here
                    $(this).dialog("close");
                }
            },
            {
                text: "Assign as Team Member",
                disabled:"disabled",
                "class": 'jqueryUiButton submitButton',
                click: function () {
                    var areaMain = $('#assignTeamMember>div.areaMain');
                    if(''==areaMain.find('select.teamMember').val() || ''==areaMain.find('select.role').val()) {
                        $.growlUI('Error', 'You must select a team member and a role', 2000);
                        return false;
                    }
                    var confirm = areaMain.find('input.confirm').attr("checked") ? 1 : 0;
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: '/api-student/assign-team-member/collection/' +$('#studentCollectionMenu').find('option[value='+$('#studentCollectionMenu').val()+']').html().trim() +
                            '/id_team_member/' + areaMain.find('select.teamMember').val() +
                            '/confirm/' + confirm +
                            '/role/' + areaMain.find('select.role').val(),
                        success: function (json) {
                            areaMain.find('div.areaConfirm').hide();
                            areaMain.find('input.confirm').attr("checked", false);
                            if (1 == json.success) {
                                $('#assignTeamMember').dialog("close");
                                if ('' != json.message) {
                                    $.growlUI('Success', json.message, 10000);
                                } else {
                                    $.growlUI('Success', 'Student transfers initiated.', 10000);
                                }

                            } else {
                                console.debug('An error occured in the transfer.');
                                if('This user already exists' == json.errorMessage.substr(0, 'This user already exists'.length)) {
                                    areaMessage.html("<b>"+json.errorMessage+"</b>");
                                    areaMain.find('div.areaConfirm').show();
                                    areaMain.find('input.confirm').val(1);
                                }
                                areaMessage.show();
                            }
                        },
                        error: function (json) {
                            console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/' + $('#printType').val() + '/formNum/' + $('#formNum').val());
                        }
                    });

                }
            }
        ],
    });
}
App.waitForJobCompletion = function (jobId, fileName) {
    $.ajax({
        type:'POST',
        dataType: 'json',
        url:'/student/get-job-status/id/'+jobId,
        success: function (json) {
            if(2==json.status) {
                setTimeout(function() {
                    App.waitForJobCompletion(jobId, fileName);
                }, 1000);
            } else if(3==json.status) {
                $('#printJobs').html('<li id="refresh_job_'+json.job+'" ><a id="download_job'+jobId+'" href="">Open Print File</a></li>');
                $('#download_job'+jobId).click(function(element) {
                    window.location = '/student/get-job-document/id/'+jobId+'/fileName/'+fileName;
                    return false;
                });
            }
        }
    });
}
App.addInitializer(function(options) {
    /**
     * this is the main app layout
     * @type {App.CollectionManagerLayout}
     */
    App.collectionManager = new App.CollectionManagerLayout();
    App.studentCollectionManager.show(App.collectionManager);
});

App.getStudentIdFromStudentOptionsList = function (item) {
    var student = '';
    $(item).children().each(function(i, option) {
        if(option.value) {
            var keyValueArr = option.value.split('&');
            $.each(keyValueArr, function(index, keyValue) {
                arr = keyValue.split('=');
                if('student'==arr[0]) {
                    student = arr[1];
                }
            });
        }
    });
    return student;
}
