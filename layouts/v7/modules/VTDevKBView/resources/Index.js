/* ********************************************************************************
 * The content of this file is subject to the VTDevKBView("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is vtdevsolutions.com
 * Portions created by vtdevsolutions.com are Copyright(C) vtdevsolutions.com
 * All Rights Reserved.
 * ****************************************************************************** */

Vtiger_List_Js("VTDevKBView_Index_Js",{},{
    registerQuickEditEvent:function(){
        var thisInstance = new Vtiger_Index_Js();
        jQuery('.kbQuickEdit').on('click',function(e){
            var currentVTDevKBBox = jQuery(this).closest('.kbBoxTask');
            var requestParams = jQuery(this).data('url');
            app.request.post({url:requestParams}).then(
                function(err,data){
                    app.helper.hideProgress();
                    var callbackparams = {
                        'cb' : function (container){
                            thisInstance.registerPostReferenceEvent(container);
                            app.event.trigger('post.QuickCreateForm.show',form);
                            app.helper.registerLeavePageWithoutSubmit(form);
                            app.helper.registerModalDismissWithoutSubmit(form);
                        },
                        backdrop : 'static',
                        keyboard : false
                    }

                    app.helper.showModal(data, callbackparams);
                    var form = jQuery('form[name="QuickCreate"]');
                    var moduleName = form.find('[name="module"]').val();
                    app.helper.showVerticalScroll(jQuery('form[name="QuickCreate"] .modal-body'), {'autoHideScrollbar': true});

                    var targetInstance = new Vtiger_Index_Js();
                    var moduleInstance = Vtiger_Edit_Js.getInstanceByModuleName(moduleName);
                    if(typeof(moduleInstance.quickCreateSave) === 'function'){
                        targetInstance = moduleInstance;
                        targetInstance.registerBasicEvents(form);
                    }

                    vtUtils.applyFieldElementsView(form);
                    var callback = function (data,err) {
                        if(err == null){
                            jQuery('.kbTaskTitle a',currentVTDevKBBox).html(data._recordLabel);
                            jQuery.each(data,function (key,obj) {
                                if(key != '_recordId' && key != '_recordLabel'){
                                    var rowElement = jQuery('.fieldValue[data-field-name="'+key+'"]',currentVTDevKBBox);
                                    if(rowElement.length > 0){
                                        jQuery('.value',rowElement).attr('title',obj.display_value);
                                        jQuery('.value',rowElement).html(obj.display_value);
                                        jQuery('.fieldBasicData',rowElement).data('displayvalue',obj.display_value);
                                        jQuery('.fieldBasicData',rowElement).data('value',obj.value);
                                    }
                                    if(key == 'vtdevkb_color'){
                                        currentVTDevKBBox.css('background',obj.value);
                                    }
                                }
                            })
                        }
                    }
                    targetInstance.quickCreateSave(form,{callbackFunction:callback});
                    app.helper.hideProgress();
                }
            );
        });
    },

    //This will show the notification message using pnotify

    registerSortableEvent:function(){
        var thisInstance = this;
        var mesParams={};
        jQuery('.kbBoxContent').sortable({
            connectWith: ".kbBoxContent",
            handle: ".kbTaskHeader",
            cursor: "move",
            start: function(e,ui){
                var item = ui.item;
                mesParams.itemName = item.find('.kbTaskTitle a').text();
                mesParams.from = item.closest('.kanbanBox').find('.kbBoxTitle').text();
            },
            stop: function(event,ui){
                var item = ui.item;
                var primaryFieldName = jQuery('#primaryFieldName').val();
                var primaryFieldId = jQuery('#primaryFieldId').val();
                var recordId = item.find('input[name="recordId"]').val();

                var nextRecordId= item.next('.kbBoxTask').find('input[name="recordId"]').val();
                if(typeof nextRecordId == "undefined"){
                    nextRecordId = -1;
                }

                var prevRecordId = item.prev('.kbBoxTask').find('input[name="recordId"]').val();
                if(typeof prevRecordId == "undefined"){
                    prevRecordId = -1;
                }

                var primaryValue = item.closest('.kanbanBox').find('input[name="primaryValue"]').val;
                mesParams.to = item.closest('.kanbanBox').find('.kbBoxTitle').text();
                jQuery('.kanbanBox').each(function(){
                    var container = jQuery(this);
                    jQuery(this).find('input[name="recordId"]').each(function () {
                        if(jQuery(this).val() == recordId){
                            primaryValue = container.find('input[name="primaryValue"]').val();
                        }

                    });
                });
                var params={
                    'primaryFieldName':primaryFieldName,
                    'primaryFieldId':primaryFieldId,
                    'recordId':recordId,
                    'nextRecordId':nextRecordId,
                    'prevRecordId':prevRecordId,
                    'primaryValue':primaryValue,
                    'module':'VTDevKBView',
                    'action':'ActionAjax',
                    'mode':'updatePrimaryFieldValue',
                    'source_module':jQuery('#kbSourceModule').val()
                }
                app.request.post({data:params}).then(
                    function(data){
                        if(mesParams.from == mesParams.to ){
                            return;
                        }
                        var txtMessage = mesParams.itemName + " updated from "+ mesParams.from +" to "+ mesParams.to;
                        app.helper.showSuccessNotification({message:txtMessage});
                        //Show popup contact
                        thisInstance.showCustomPopupAjaxRequest(mesParams.to,recordId);
                    }
                );
            }
        }).disableSelection();
    },
    fixWidthColumns: function () {
        var kanbanBox = jQuery('#detailView .kbParentContainer .kanbanBox');
        var containerWidth = jQuery('#detailView .kbParentContainer').width();
        kanbanBox.width((containerWidth - 80)/4);
        var kbContainer = jQuery('#detailView .kbParentContainer .kbContainer').width(kanbanBox.length *(kanbanBox.width()+20));
    },
    registerAjaxEditEvent : function(){
        var thisInstance = this;
        jQuery('.kbParentContainer').on('click','.fieldValue .editAction', function(e) {
            var selection = window.getSelection().toString();
            if(selection.length == 0) {
                var currentTdElement = jQuery(e.currentTarget).closest('.kbValueContainer');
                thisInstance.ajaxEditHandling(currentTdElement);
            }
        });
    },
    fixWidthEditElement : function (currentTdElement) {
        var containerWidth = currentTdElement.width();
        jQuery('.editElement',currentTdElement).css('min-width','initial');
    },
    ajaxEditHandling : function(currentTdElement){
        var thisInstance = this;
        var detailViewValue = jQuery('.value',currentTdElement);
        var editElement = jQuery('.edit',currentTdElement);
        var fieldBasicData = jQuery('.fieldBasicData', editElement);
        var fieldName = fieldBasicData.data('name');
        var fieldType = fieldBasicData.data('type');
        var value = fieldBasicData.data('displayvalue');
        var rawValue = fieldBasicData.data('value');
        var self = this;
        var fieldElement = jQuery('[name="'+ fieldName +'"]', editElement);

        // If Reference field has value, then we are disabling the field by default
        if(fieldElement.attr('disabled') == 'disabled' && fieldType != 'reference'){
            return;
        }

        if(editElement.length <= 0) {
            return;
        }

        if(editElement.is(':visible')){
            return;
        }

        if(fieldType === 'multipicklist') {
            var multiPicklistFieldName = fieldName.split('[]');
            fieldName = multiPicklistFieldName[0];
        }

        var customHandlingFields = ['owner','ownergroup','picklist','multipicklist','reference','currencyList','text'];
        if(jQuery.inArray(fieldType, customHandlingFields) !== -1){
            value = rawValue;
        }
        if(jQuery('.editElement',editElement).length === 0){
            var fieldInfo;
            fieldInfo = uimeta.field.get(fieldName);
            fieldInfo['value'] = value;
            var fieldObject = Vtiger_Field_Js.getInstance(fieldInfo);
            var fieldModel = fieldObject.getUiTypeModel();

            var ele = jQuery('<div class="input-group editElement"></div>');
            var actionButtons = '<span class="pointerCursorOnHover input-group-addon input-group-addon-save inlineAjaxSave"><i class="fa fa-check"></i></span>';
            actionButtons += '<span class="pointerCursorOnHover input-group-addon input-group-addon-cancel inlineAjaxCancel"><i class="fa fa-close"></i></span>';
            //wrapping action buttons with class called input-save-wrap
            var inlineSaveWrap=jQuery('<div class="input-save-wrap"></div>');
            inlineSaveWrap.append(actionButtons);
            // we should have atleast one submit button for the form to submit which is required for validation
            ele.append(fieldModel.getUi()).append(inlineSaveWrap);
            ele.find('.inputElement').addClass('form-control');
            editElement.append(ele);
            thisInstance.fixWidthEditElement(currentTdElement);

        }
        
        // for reference fields, actual value will be ID but we need to show related name of that ID
        if(fieldType === 'reference'){
            if(value !== 0){
                jQuery('input[name="'+fieldName+'"]',editElement).prop('value',jQuery.trim(detailViewValue.text()));
                var referenceElement = jQuery('input[name="'+fieldName+'"]',editElement);
                if(!referenceElement.attr('disabled')) {
                    referenceElement.attr('disabled','disabled');
                    editElement.find('.clearReferenceSelection').removeClass('hide')
                }
            }
        }

        detailViewValue.css('display', 'none');
        editElement.removeClass('hide').show().children().filter('input[type!="hidden"]input[type!="image"],select').filter(':first').focus();
        vtUtils.applyFieldElementsView(currentTdElement);
        var vtigerInstance = Vtiger_Index_Js.getInstance();
        vtigerInstance.registerAutoCompleteFields(currentTdElement);
        vtigerInstance.referenceModulePopupRegisterEvent(currentTdElement);
        editElement.addClass('ajaxEdited');
        thisInstance.registerSaveOnEnterEvent(editElement);
        jQuery('.editAction').addClass('hide');

        if(fieldType == 'picklist' || fieldType == 'ownergroup' || fieldType == 'owner') {
            var sourcePicklistFieldName = thisInstance.getDependentSourcePicklistName(fieldName);
            if(sourcePicklistFieldName) {
                thisInstance.handlePickListDependencyMap(sourcePicklistFieldName);
            }
        }
    },
    getDependentSourcePicklistName : function(fieldName) {
        var container = jQuery('#detailView')
        var picklistDependcyElemnt = jQuery('[name="picklistDependency"]',container);
        if(picklistDependcyElemnt.length <= 0) {
            return '';
        }

        var picklistDependencyMapping = JSON.parse(picklistDependcyElemnt.val());
        var sourcePicklists = Object.keys(picklistDependencyMapping);
        if(sourcePicklists.length <= 0){
            return '';
        }
        var sourcePicklistFieldName = '';
        jQuery.each(picklistDependencyMapping, function(sourcePicklistName, configuredDependencyObject) {
            var picklistmap = configuredDependencyObject["__DEFAULT__"];
            jQuery.each(picklistmap,function(targetPickListName,targetPickListValues){
                if(targetPickListName == fieldName){
                    sourcePicklistFieldName = sourcePicklistName;
                }
            });
        });

        return sourcePicklistFieldName;
    },
    registerSaveOnEnterEvent: function(editElement) {
        editElement.find('.inputElement:not(textarea)').on('keyup', function(e) {
            var textArea = editElement.find('textarea');
            var ignoreList = ['reference','picklist','multipicklist','owner'];
            var fieldType = jQuery(e.target).closest('.ajaxEdited').find('.fieldBasicData').data('type');
            if(ignoreList.indexOf(fieldType) !== -1) return;
            if(!textArea.length){
                (e.keyCode || e.which) === 13  && editElement.find('.inlineAjaxSave').trigger('click');
            }
        });
    },
    registerAjaxEditSaveEvent : function(contentHolder){
        var thisInstance = this;
        if(typeof contentHolder === 'undefined') {
            contentHolder = jQuery('.kbContainer');
        }

        contentHolder.on('click','.inlineAjaxSave',function(e){
            e.preventDefault();
            e.stopPropagation();
            var currentTarget = jQuery(e.currentTarget);
            var currentTdElement = currentTarget.closest('.kbValueContainer');
            var detailViewValue = jQuery('.value',currentTdElement);
            var editElement = jQuery('.edit',currentTdElement);
            var actionElement = jQuery('.editAction', currentTdElement);
            var fieldBasicData = jQuery('.fieldBasicData', editElement);
            var fieldName = fieldBasicData.data('name');
            var fieldType = fieldBasicData.data("type");
            var previousValue = jQuery.trim(fieldBasicData.data('displayvalue'));

            var fieldElement = jQuery('[name="'+ fieldName +'"]', editElement);
            var ajaxEditNewValue = fieldElement.val();
            var currentVTDevKBBox = currentTdElement.closest('.kbBoxTask');
            var recordId = jQuery('[name="recordId"]',currentVTDevKBBox).val();

            // ajaxEditNewValue should be taken based on field Type
            if(fieldElement.is('input:checkbox')) {
                if(fieldElement.is(':checked')) {
                    ajaxEditNewValue = '1';
                } else {
                    ajaxEditNewValue = '0';
                }
                fieldElement = fieldElement.filter('[type="checkbox"]');
            } else if(fieldType == 'reference'){
                ajaxEditNewValue = fieldElement.attr('value');
            }

            // prev Value should be taken based on field Type
            var customHandlingFields = ['owner','ownergroup','picklist','multipicklist','reference','boolean'];
            if(jQuery.inArray(fieldType, customHandlingFields) !== -1){
                previousValue = fieldBasicData.data('value');
            }

            // Field Specific custom Handling
            if(fieldType === 'multipicklist'){
                var multiPicklistFieldName = fieldName.split('[]');
                fieldName = multiPicklistFieldName[0];
            }

            var fieldValue = ajaxEditNewValue;

            //Before saving ajax edit values we need to check if the value is changed then only we have to save
            if(previousValue == ajaxEditNewValue) {
                detailViewValue.css('display', 'inline-block');
                editElement.addClass('hide');
                editElement.removeClass('ajaxEdited');
                jQuery('.editAction').removeClass('hide');
                actionElement.show();
            }else{
                var fieldNameValueMap = {};
                fieldNameValueMap['value'] = fieldValue;
                fieldNameValueMap['field'] = fieldName;
                fieldNameValueMap['record'] = recordId;
                fieldNameValueMap['module'] = jQuery('#kbSourceModule').val();
                var form = currentTarget.closest('form');
                var params = {
                    'ignore' : 'span.hide .inputElement,input[type="hidden"]',
                    submitHandler : function(form){
                        var preAjaxSaveEvent = jQuery.Event(Vtiger_Detail_Js.PreAjaxSaveEvent);
                        app.event.trigger(preAjaxSaveEvent,{form:jQuery(form),tiggeredFiledInfo:fieldNameValueMap});
                        if(preAjaxSaveEvent.isDefaultPrevented()) {
                            return false;
                        }

                        jQuery(currentTdElement).find('.input-group-addon').addClass('disabled');
                        app.helper.showProgress();
                        thisInstance.saveFieldValues(fieldNameValueMap).then(function(response) {
                            app.helper.hideProgress();
                            var postSaveRecordDetails = response;
                            if(fieldBasicData.data('type') == 'picklist' && app.getModuleName() != 'Users') {
                                var color = postSaveRecordDetails[fieldName].colormap[postSaveRecordDetails[fieldName].value];
                                if(color) {
                                    var contrast = app.helper.getColorContrast(color);
                                    var textColor = (contrast === 'dark') ? 'white' : 'black';
                                    var picklistHtml = '<span class="picklist-color" style="background-color: ' + color + '; color: '+ textColor + ';">' +
                                        postSaveRecordDetails[fieldName].display_value +
                                        '</span>';
                                } else {
                                    var picklistHtml = '<span class="picklist-color">' +
                                        postSaveRecordDetails[fieldName].display_value +
                                        '</span>';
                                }
                                detailViewValue.html(picklistHtml);
                            } else if(fieldBasicData.data('type') == 'multipicklist' && app.getModuleName() != 'Users') {
                                var picklistHtml = '';
                                var rawPicklistValues = postSaveRecordDetails[fieldName].value;
                                rawPicklistValues = rawPicklistValues.split('|##|');
                                var picklistValues = postSaveRecordDetails[fieldName].display_value;
                                picklistValues = picklistValues.split(',');
                                for(var i=0; i< rawPicklistValues.length; i++) {
                                    var color = postSaveRecordDetails[fieldName].colormap[rawPicklistValues[i].trim()];
                                    if(color) {
                                        var contrast = app.helper.getColorContrast(color);
                                        var textColor = (contrast === 'dark') ? 'white' : 'black';
                                        picklistHtml = picklistHtml +
                                            '<span class="picklist-color" style="background-color: ' + color + '; color: '+ textColor + ';">' +
                                            picklistValues[i] +
                                            '</span>';
                                    } else {
                                        picklistHtml = picklistHtml +
                                            '<span class="picklist-color">' +
                                            picklistValues[i] +
                                            '</span>';
                                    }
                                    if(picklistValues[i+1]!==undefined)
                                        picklistHtml+=' , ';
                                }
                                detailViewValue.html(picklistHtml);
                            } else if(fieldBasicData.data('type') == 'currency' && app.getModuleName() != 'Users') {
                                detailViewValue.find('.currencyValue').html(postSaveRecordDetails[fieldName].display_value);
                                contentHolder.closest('.detailViewContainer').find('.detailview-header-block').find('.'+fieldName).html(postSaveRecordDetails[fieldName].display_value);
                            }else {
                                detailViewValue.html(postSaveRecordDetails[fieldName].display_value);
                                //update namefields displayvalue in header
                                if(contentHolder.hasClass('overlayDetail')) {
                                    contentHolder.find('.overlayDetailHeader').find('.'+fieldName)
                                        .html(postSaveRecordDetails[fieldName].display_value);
                                } else {
                                    contentHolder.closest('.detailViewContainer').find('.detailview-header-block')
                                        .find('.'+fieldName).html(postSaveRecordDetails[fieldName].display_value);
                                }
                            }
                            fieldBasicData.data('displayvalue',postSaveRecordDetails[fieldName].display_value);
                            fieldBasicData.data('value',postSaveRecordDetails[fieldName].value);
                            jQuery(currentTdElement).find('.input-group-addon').removeClass("disabled");

                            detailViewValue.css('display', 'inline-block');
                            editElement.addClass('hide');
                            editElement.removeClass('ajaxEdited');
                            jQuery('.editAction').removeClass('hide');
                            actionElement.show();
                            var postAjaxSaveEvent = jQuery.Event(Vtiger_Detail_Js.PostAjaxSaveEvent);
                            app.event.trigger(postAjaxSaveEvent, fieldBasicData, postSaveRecordDetails, contentHolder);
                            //After saving source field value, If Target field value need to change by user, show the edit view of target field.
                            if(thisInstance.targetPicklistChange) {
                                var sourcePicklistname = thisInstance.sourcePicklistname;
                                thisInstance.targetPicklist.find('.editAction').trigger('click');
                                thisInstance.targetPicklistChange = false;
                                thisInstance.targetPicklist = false;
                                thisInstance.handlePickListDependencyMap(sourcePicklistname);
                                thisInstance.sourcePicklistname = false;
                            }
                        });
                    }
                };
                validateAndSubmitForm(form,params);
            }
        });
    },
    handlePickListDependencyMap : function(sourcePicklistName) {
        var container = jQuery('#detailView');
        var picklistDependcyElemnt = jQuery('[name="picklistDependency"]',container);
        if(picklistDependcyElemnt.length <= 0) {
            return;
        }
        var picklistDependencyMapping = JSON.parse(picklistDependcyElemnt.val());
        var sourcePicklists = Object.keys(picklistDependencyMapping);
        if(sourcePicklists.length <= 0){
            return;
        }

        var configuredDependencyObject = picklistDependencyMapping[sourcePicklistName];
        var selectedValue = container.find('[data-name='+sourcePicklistName+']').data('value');
        var targetObjectForSelectedSourceValue = configuredDependencyObject[selectedValue];
        var picklistmap = configuredDependencyObject["__DEFAULT__"];
        if(typeof targetObjectForSelectedSourceValue == 'undefined'){
            targetObjectForSelectedSourceValue = picklistmap;
        }
        jQuery.each(picklistmap,function(targetPickListName,targetPickListValues){
            var targetPickListMap = targetObjectForSelectedSourceValue[targetPickListName];
            if(typeof targetPickListMap == "undefined"){
                targetPickListMap = targetPickListValues;
            }
            var targetPickList = jQuery('[name="'+targetPickListName+'"]',container);
            if(targetPickList.length <= 0){
                return;
            }

            var listOfAvailableOptions = targetPickList.data('available-options');
            if(typeof listOfAvailableOptions == "undefined"){
                listOfAvailableOptions = jQuery('option',targetPickList);
                targetPickList.data('available-options', listOfAvailableOptions);
            }

            var targetOptions = new jQuery();
            var optionSelector = [];
            optionSelector.push('');
            for(var i=0; i<targetPickListMap.length; i++){
                optionSelector.push(targetPickListMap[i]);
            }

            jQuery.each(listOfAvailableOptions, function(i,e) {
                var picklistValue = jQuery(e).val();
                if(jQuery.inArray(picklistValue, optionSelector) != -1) {
                    targetOptions = targetOptions.add(jQuery(e));
                }
            })
            var targetPickListSelectedValue = '';
            targetPickListSelectedValue = targetOptions.filter('[selected]').val();
            if(targetPickListMap.length == 1) {
                targetPickListSelectedValue = targetPickListMap[0]; // to automatically select picklist if only one picklistmap is present.
            }
            if((targetPickListName == 'group_id' || targetPickListName == 'assigned_user_id') && jQuery("[data-name="+ sourcePicklistName +"]").data('value') == ''){
                return false;
            }
            targetPickList.html(targetOptions).val(targetPickListSelectedValue).trigger("change");
        })

    },
    saveFieldValues : function (fieldDetailList) {
        var aDeferred = jQuery.Deferred();
        var data = {};
        if(typeof fieldDetailList != 'undefined'){
            data = fieldDetailList;
        }
        data['action'] = 'SaveAjax';

        app.request.post({data:data}).then(
            function(err, reponseData){
                if(err === null){
                    app.helper.showSuccessNotification({"message":""});
                    aDeferred.resolve(reponseData);
                } else {
                    app.helper.showErrorNotification({"message":err});
                }
            }
        );

        return aDeferred.promise();
    },
    registerAjaxEditCancelEvent : function(contentHolder){
        var thisInstance = this;
        if(typeof contentHolder === 'undefined') {
            contentHolder = jQuery('.kbContainer');
        }
        contentHolder.on('click','.inlineAjaxCancel',function(e){
            e.preventDefault();
            e.stopPropagation();
            var currentTarget = jQuery(e.currentTarget);
            var currentTdElement = currentTarget.closest('.kbValueContainer');
            var detailViewValue = jQuery('.value',currentTdElement);
            var editElement = jQuery('.edit',currentTdElement);
            var actionElement = jQuery('.editAction', currentTdElement);
            detailViewValue.css('display', 'inline-block');
            editElement.addClass('hide');
            editElement.find('.inputElement').trigger('Vtiger.Validation.Hide.Messsage')
            editElement.removeClass('ajaxEdited');
            jQuery('.editAction').removeClass('hide');
            actionElement.show();
        });
    },

    //customize quick create
    registerQuickCreateEvent:function(){
        var thisInstance = new Vtiger_Index_Js();
        jQuery('.kbQuickCreate').on('click',function(e){
            var currentVTDevKBBox = jQuery(this).closest('.kbBoxTask');
            var requestParams = jQuery(this).data('url');
            app.request.post({url:requestParams}).then(
                function(err,data){
                    app.helper.hideProgress();
                    var callbackparams = {
                        'cb' : function (container){
                            thisInstance.registerPostReferenceEvent(container);
                            app.event.trigger('post.QuickCreateForm.show',form);
                            //app.helper.registerLeavePageWithoutSubmit(form);
                            //app.helper.registerModalDismissWithoutSubmit(form);
                        },
                        backdrop : 'static',
                        keyboard : false
                    }

                    app.helper.showModal(data, callbackparams);
                    var form = jQuery('form[name="QuickCreate"]');
                    var moduleName = form.find('[name="module"]').val();
                    app.helper.showVerticalScroll(jQuery('form[name="QuickCreate"] .modal-body'), {'autoHideScrollbar': true});

                    var targetInstance = new Vtiger_Index_Js();
                    var moduleInstance = Vtiger_Edit_Js.getInstanceByModuleName(moduleName);
                    if(typeof(moduleInstance.quickCreateSave) === 'function'){
                        targetInstance = moduleInstance;
                        targetInstance.registerBasicEvents(form);
                    }

                    vtUtils.applyFieldElementsView(form);
                    var callback = function (data,err) {
                        if(err == null){
                            jQuery('.kbTaskTitle a',currentVTDevKBBox).html(data._recordLabel);
                            jQuery.each(data,function (key,obj) {
                                if(key != '_recordId' && key != '_recordLabel'){
                                    var rowElement = jQuery('.fieldValue[data-field-name="'+key+'"]',currentVTDevKBBox);
                                    if(rowElement.length > 0){
                                        jQuery('.value',rowElement).attr('title',obj.display_value);
                                        jQuery('.value',rowElement).html(obj.display_value);
                                        jQuery('.fieldBasicData',rowElement).data('displayvalue',obj.display_value);
                                        jQuery('.fieldBasicData',rowElement).data('value',obj.value);
                                    }
                                    if(key == 'vtdevkb_color'){
                                        currentVTDevKBBox.css('background',obj.value);
                                    }
                                }
                            });
                            window.location.replace(window.location.href);
                        }
                    }
                    targetInstance.quickCreateSave(form,{callbackFunction:callback});
                    app.helper.hideProgress();
                }
            );
        });
    },
    registerRemoveBoxEvent: function (){
        //removeThisBox
        if(typeof contentHolder === 'undefined') {
            contentHolder = jQuery('.kbContainer');
        }
        contentHolder.on('click','.removeThisBox',function(e){
            if(confirm("Do you really want to remove this field from kanban vỉew?")){
                e.preventDefault();
                e.stopPropagation();
                var currentTarget = jQuery(e.currentTarget);
                var primaryFieldValue = currentTarget.data('box-name');
                var targetModule = $("#kbSourceModule").val();
                var primaryFieldId = $("#primaryFieldId").val();
                //call removeFieldForVTDevKBViewSetting
                var params = {
                    'module':'VTDevKBView',
                    'action':'SaveAjax',
                    'mode': 'removeFieldForVTDevKBViewSetting',
                    'primaryFieldId':primaryFieldId,
                    'targetModule':targetModule,
                    'primaryFieldValue':primaryFieldValue
                };
                app.helper.showProgress();
                app.request.post({data:params}).then(
                    function(err,data){
                        if(err == null){
                            app.helper.hideProgress();
                            app.helper.hideModal();
                            window.location.reload();
                        }
                    }
                );
            }
        });
    },
    showCustomPopupAjaxRequest: function (color,contactId) {
        var params = {};
        params.module = "Contacts";
        params.view = 'ShowCustomPopup';
        params.record = contactId;
        params.color = color;
        if (color == 'Appointment' || color == 'Closed') {
            app.helper.showProgress();
            app.request.get({ data: params }).then(
                function (error, data) {
                    app.helper.hideProgress();
                    var callback = function (container) {
                        container.find("button[name='contactButtonsSave']").on('click', function(e) {
                            var form = jQuery(e.currentTarget).closest('form');
                            var params = {
                                submitHandler: function (frm) {
                                    jQuery("button[name='contactButtonsSave']").attr("disabled", "disabled");
                                    if (this.numberOfInvalids() > 0) {
                                        return false;
                                    }
                                    var form = jQuery(frm);
                                    form.find("textarea").each(function () {
                                        eleName = $(this).attr("name");
                                        if (CKEDITOR.instances[$(this).attr("name")]) {
                                            $(this).val(CKEDITOR.instances[eleName].document.getBody().getText());
                                        }
                                    })
                                    var formData = jQuery(frm).serialize();
                                    app.helper.showProgress();
                                    app.request.post({data: formData}).then(function (err, data2) {
                                        app.helper.hideProgress();
                                        if (!err) {
                                            // if (color == 'Appointment') {
                                            //     var array_fields = ['cf_919','cf_921'];
                                            //     $.each(array_fields, function( index, value ) {
                                            //         var kbTaskSection = $("div.fieldValue[data-record-id='" + contactId +"']");
                                            //         var fieldValue = form.find('[name="'+value+'"]').val();
                                            //         var targetValuePanel = kbTaskSection.find("#Contacts_detailView_fieldValue_" + value);
                                            //         targetValuePanel.find('span.value').html(fieldValue);
                                            //         targetValuePanel.find('[data-name="'+value+'"]').attr('data-displayvalue', fieldValue);
                                            //         targetValuePanel.find('[data-name="'+value+'"]').attr('data-value', fieldValue);
                                            //     });
                                            // }
                                            // if (color == 'Closed') {
                                            //
                                            // }
                                            window.location.reload();
                                            app.helper.showSuccessNotification({"message": "Update fields " + data2});
                                            app.helper.hideModal();
                                        } else {
                                            app.helper.showErrorNotification({"message": err});
                                        }
                                    });
                                }
                            };
                            form.vtValidate(params);
                            form.submit();
                        });
                    };
                    app.helper.showModal(data, {
                        'cb': callback
                    });
                }
            )
        }
    },
    /*
	 * function to trigger send Email
	 * @params: send email url , module name.
	 */
    triggerSendOneEmail: function (massActionUrl, module, recordId) {
        var data = app.convertUrlToDataParams(massActionUrl);
        var params = {
            'search_params' :'',
            'nolistcache' :  0,
            'selected_ids' : [recordId],
            'excluded_ids' : null,
            'sourceModule' : app.getModuleName(),
            'sourceRecord' : recordId
        };
        jQuery.extend(params, data);
        Vtiger_Index_Js.showComposeEmailPopup(params);
    },
    /**
     * Function to send Email when click to email link
     */
    registerEmailLinkClick: function () {
        var thisIntance = this;
        $(".kbBoxTask").on("click","a.emailField",function(event){
            event.preventDefault();
            var recordId = $(this).closest("div.fieldValue").data('record-id');
            thisIntance.triggerSendOneEmail('index.php?module=Contacts&view=MassActionAjax&mode=showComposeEmailForm&step=step1&relatedLoad=true','Emails',recordId);
        });
    },
    registerEvents : function() {
        this._super();
        var thisInstance = this;
        thisInstance.fixWidthColumns();
        var detailContentsHolder = jQuery('div.kbContainer');
        vtUtils.applyFieldElementsView(detailContentsHolder);
        thisInstance.registerQuickEditEvent();
        thisInstance.registerSortableEvent();
        this.registerAjaxEditEvent();
        this.registerAjaxEditSaveEvent();
        this.registerAjaxEditCancelEvent();
        jQuery('#createFilter').data('url','index.php?module=CustomView&view=EditAjax&source_module='+jQuery('#kbSourceModule').val());
        app.event.on('post.listViewFilter.click', function (event, searchRow) {
            thisInstance.registerEvents();
        });
        jQuery('#detailView').on('submit',function (e) {
            e.preventDefault();
        })
        thisInstance.registerQuickCreateEvent();
        thisInstance.registerRemoveBoxEvent();
        thisInstance.registerEmailLinkClick();
    }
});