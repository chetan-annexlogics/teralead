Settings_Workflows_Edit_Js.prototype.registerVTEWEBHOOKSTaskEvents = function () {
    var thisInstance = this;
    this.registerAddFieldEvent();
    this.registerDisableParentsOfGroup();
    this.registerDeleteConditionEvent();
    this.registerFieldChangeVTEWEBHOOKS();
    this.fieldValueMap = false;
    if (jQuery('#fieldValueMapping').val() != '') {
        this.fieldValueReMapping();
    }
    var fields = jQuery('#save_fieldvaluemapping').find('select[name="fieldname"]');
    jQuery.each(fields, function (i, field) {
        thisInstance.loadFieldSpecificUiVTEWEBHOOKSTask(jQuery(field));
    });
    this.getPopUp(jQuery('#saveTask'));
};
/**
 * Function which will register field change event
 */
Settings_Workflows_Edit_Js.prototype.registerFieldChangeVTEWEBHOOKS = function () {
    var thisInstance = this;
    jQuery('#saveTask').on('change', 'input[name="fieldname"]', function (e) {
        var selectedElement = jQuery(e.currentTarget).closest('.conditionRow').find('select[name="fieldname"]');
        if (selectedElement.val() != 'none') {
            var conditionRow = selectedElement.closest('.conditionRow');
            var moduleNameElement = conditionRow.find('[name="modulename"]');
            if (moduleNameElement.length > 0) {
                var selectedOptionFieldInfo = selectedElement.find('option:selected').data('fieldinfo');
                var type = selectedOptionFieldInfo.type;
                if (type == 'picklist' || type == 'multipicklist') {
                    var moduleName = jQuery('#createEntityModule').val();
                    moduleNameElement.find('option[value="' + moduleName + '"]').attr('selected', true);
                    moduleNameElement.trigger('change');
                    moduleNameElement.select2("disable");
                }
            }
            thisInstance.loadFieldSpecificUiVTEWEBHOOKSTask(selectedElement);
        }
    });
};
Settings_Workflows_Edit_Js.prototype.registerVTUpdateFieldsTaskEvents = function () {
    var thisInstance = this;
    this.registerAddFieldEvent();
    this.registerDisableParentsOfGroup();
    this.registerDeleteConditionEvent();
    this.registerFieldChangeVTEWEBHOOKS();
    this.fieldValueMap = false;
    if (jQuery('#fieldValueMapping').val() != '') {
        this.fieldValueReMapping();
    }
    var fields = jQuery('#save_fieldvaluemapping').find('select[name="fieldname"]');
    jQuery.each(fields, function (i, field) {
        thisInstance.loadFieldSpecificUiVTEWEBHOOKSTask(jQuery(field));
    });
    this.getPopUp(jQuery('#saveTask'));
};
Settings_Workflows_Edit_Js.prototype.registerChangeCreateEntityEvent = function () {
    var thisInstance = this;
    jQuery('#createEntityModule').on('change', function (e) {
        var relatedModule = jQuery(e.currentTarget).val();
        var module_name = jQuery('#module_name').val();
        if( relatedModule == module_name ) {
            jQuery(e.currentTarget).closest('.taskTypeUi').find('.sameModuleError').removeClass('hide');
        } else{
            jQuery(e.currentTarget).closest('.taskTypeUi').find('.sameModuleError').addClass('hide');
        }
        var params = {
            module: app.getModuleName(),
            parent: app.getParentModuleName(),
            view: 'CreateEntity',
            relatedModule: jQuery(e.currentTarget).val(),
            for_workflow: jQuery('[name="for_workflow"]').val(),
            module_name: jQuery('#module_name').val()
        }
        app.helper.showProgress();
        app.request.get({data:params}).then(function (error, data) {
            app.helper.hideProgress();
            var createEntityContainer = jQuery('#addCreateEntityContainer');
            createEntityContainer.html(data);
            //vtUtils.showSelect2ElementView(createEntityContainer.find('.select2'));
            thisInstance.registerAddFieldEvent();
            thisInstance.registerDisableParentsOfGroup();
            thisInstance.fieldValueMap = false;
            if (jQuery('#fieldValueMapping').val() != '') {
                thisInstance.fieldValueReMapping();
            }
            var fields = jQuery('#save_fieldvaluemapping').find('select[name="fieldname"]');
            jQuery.each(fields, function (i, field) {
                thisInstance.loadFieldSpecificUiVTEWEBHOOKSTask(jQuery(field));
            });
        });
    });
};
Settings_Workflows_Edit_Js.prototype.registerAddFieldEvent = function () {
    jQuery('#addFieldBtn').on('click', function (e) {
        var max_index = $('#max_parent_index').val();
        var newAddFieldContainer = jQuery('.basicAddFieldContainer').clone(true, true).removeClass('basicAddFieldContainer hide').addClass('conditionRow');
        jQuery('select', newAddFieldContainer).addClass('select2');
        jQuery('#save_fieldvaluemapping').append(newAddFieldContainer);
        jQuery('#save_fieldvaluemapping').append('<br>');
        //vtUtils.showSelect2ElementView(newAddFieldContainer.find('.select2'));
        jQuery('button.add-group', newAddFieldContainer).removeAttr('data-index');
        var index = Number(max_index) +1;
        jQuery('button.add-group', newAddFieldContainer).attr('data-index',index);
        jQuery('button.add-group', newAddFieldContainer).attr('data-parent',0);
        jQuery('button.add-group', newAddFieldContainer).attr('data-group',1);
        $('#max_parent_index').val(index);
    });
    jQuery('#addFieldResponseBtn').on('click', function (e) {
        var newAddFieldContainer = jQuery('.response_basicAddFieldContainer').clone(true, true).removeClass('response_basicAddFieldContainer hide').addClass('conditionRow');
        jQuery('select', newAddFieldContainer).addClass('select2');
        jQuery('#save_response_fieldvaluemapping').append(newAddFieldContainer);
        vtUtils.showSelect2ElementView(newAddFieldContainer.find('.select2'));
    });
};
Settings_Workflows_Edit_Js.prototype.checkHaveChilds = function (index,rows) {
    var check = false;
    rows.each(function(k,item){
        var row = jQuery(item);
        if(index == row.data('parent')){
            check = true;
        }
    });
    return check;
};
Settings_Workflows_Edit_Js.prototype.registerDisableParentsOfGroup = function () {
    var self = this;
    var rows = jQuery('button.add-group');
    rows.each(function(k,item){
        var row = jQuery(item);
        var index = row.data('index');
        if(self.checkHaveChilds(index,rows)){
            var div = row.closest('div.conditionRow');
            div.find('input.getPopupUi.inputElement').val('');
            div.find('input.getPopupUi.inputElement').attr('disabled','disabled');
        }
    });
};
Settings_Workflows_Edit_Js.prototype.registerDeleteConditionEvent = function () {
    jQuery('#saveTask').on('click', '.deleteCondition', function (e) {
        var marginLeft = $(this).closest('.conditionRow').css('marginLeft');
        var marginLeft_next = $(this).closest('.conditionRow').next().css('marginLeft');
        if (marginLeft != marginLeft_next){
            $(this).closest('.conditionRow').prev().find('.fieldUiHolder').find('.getPopupUi').removeAttr('disabled');
        }
        var conditionRow = jQuery(e.currentTarget).closest('.conditionRow');
        conditionRow.next('br').remove();
        conditionRow.remove();
    })
    jQuery('#saveTask').on('click', '.response_deleteCondition', function (e) {
        var conditionRow = jQuery(e.currentTarget).closest('.conditionRow');
        conditionRow.remove();
    })
};

Settings_Workflows_Edit_Js.prototype.getVTEWEBHOOKSTaskFieldList = function () {
    var taskType = jQuery('input[name="taskType"]').val();
    return new Array('fieldname', 'value', 'valuetype');
};
Settings_Workflows_Edit_Js.prototype.preSaveVTEWEBHOOKSTask = function (tasktype) {
    var values = this.getValuesVTEWEBHOOKSTask(tasktype);
    jQuery('[name="field_value_mapping"]').val(JSON.stringify(values));
};

Settings_Workflows_Edit_Js.prototype.VTEWEBHOOKSTaskCustomValidation = function() {
    return this.checkDuplicateFieldsSelected();
};

Settings_Workflows_Edit_Js.prototype.loadFieldSpecificUiVTEWEBHOOKSTask = function (fieldSelect) {
    var self = this;
    var selectedOption = fieldSelect.find('option:selected');
    var row = fieldSelect.closest('div.conditionRow');
    var fieldUiHolder = row.find('.fieldUiHolder');
    var fieldInfo = selectedOption.data('fieldinfo');
    var fieldValueMapping = this.getFieldValueMapping();
    var fieldValueMappingKey = fieldInfo.name;
    var taskType = jQuery('#taskType').val();
    if (taskType == "VTEWEBHOOKSTask") {
        fieldValueMappingKey = fieldInfo.workflow_columnname;
        if (fieldValueMappingKey === undefined || fieldValueMappingKey === null){
            fieldValueMappingKey = selectedOption.val();
        }
    }
    if (fieldValueMapping != '' && typeof fieldValueMapping[fieldValueMappingKey] != 'undefined') {
        fieldInfo.value = fieldValueMapping[fieldValueMappingKey]['value'];
        fieldInfo.workflow_valuetype = fieldValueMapping[fieldValueMappingKey]['valuetype'];
    } else {
        fieldInfo.workflow_valuetype = 'rawtext';
    }

    if(fieldInfo.type == 'reference' || fieldInfo.type == 'multireference') {
        fieldInfo.referenceLabel = fieldUiHolder.find('[name="referenceValueLabel"]').val();
        fieldInfo.type = 'string';
    }

    var moduleName = this.getModuleName();

    var fieldModel = Vtiger_Field_Js.getInstance(fieldInfo, moduleName);
    this.fieldModelInstance = fieldModel;
    var fieldSpecificUi = this.getFieldSpecificUi(fieldSelect);
    $(fieldSpecificUi[0]).val($(fieldSelect).data('field-value'));
    $(fieldSpecificUi[1]).val($(fieldSelect).data('field-type'));

    //remove validation since we dont need validations for all eleements
    // Both filter and find is used since we dont know whether the element is enclosed in some conainer like currency
    var fieldName = fieldModel.getName();
    if (fieldModel.getType() == 'multipicklist') {
        fieldName = fieldName + "[]";
    }
    fieldSpecificUi.filter('[name="' + fieldName + '"]').attr('data-value', 'value').attr('data-workflow_columnname', fieldInfo.workflow_columnname);
    fieldSpecificUi.find('[name="' + fieldName + '"]').attr('data-value', 'value').attr('data-workflow_columnname', fieldInfo.workflow_columnname);
    fieldSpecificUi.filter('[name="valuetype"]').addClass('ignore-validation');
    fieldSpecificUi.find('[name="valuetype"]').addClass('ignore-validation');

    //If the workflowValueType is rawtext then only validation should happen
    var workflowValueType = fieldSpecificUi.filter('[name="valuetype"]').val();
    if (workflowValueType != 'rawtext' && typeof workflowValueType != 'undefined') {
        fieldSpecificUi.filter('[name="' + fieldName + '"]').addClass('ignore-validation');
        fieldSpecificUi.find('[name="' + fieldName + '"]').addClass('ignore-validation');
    }


    fieldUiHolder.html(fieldSpecificUi);

    if (fieldSpecificUi.is('input.select2')) {
        var tagElements = fieldSpecificUi.data('tags');
        var params = {tags: tagElements, tokenSeparators: [","]}
        //vtUtils.showSelect2ElementView(fieldSpecificUi, params)
    } else if (fieldSpecificUi.is('select')) {
        if (fieldSpecificUi.hasClass('select2')) {
            //vtUtils.showSelect2ElementView(fieldSpecificUi)
        } else {
            //vtUtils.showSelect2ElementView(fieldSpecificUi);
        }
    } else if (fieldSpecificUi.is('input.dateField')) {
        var calendarType = fieldSpecificUi.data('calendarType');
        if (calendarType == 'range') {
            var customParams = {
                calendars: 3,
                mode: 'range',
                className: 'rangeCalendar',
                onChange: function (formated) {
                    fieldSpecificUi.val(formated.join(','));
                }
            }
            app.registerEventForDatePickerFields(fieldSpecificUi, false, customParams);
        } else {
            app.registerEventForDatePickerFields(fieldSpecificUi);
        }
    }
    self.registerDisableParentsOfGroup();
    return this;
};

Settings_Workflows_Edit_Js.prototype.getValuesVTEWEBHOOKSTask = function (tasktype) {
    var thisInstance = this;
    var conditionsContainer = jQuery('#save_fieldvaluemapping');
    var fieldListFunctionName = 'get' + tasktype + 'FieldList';
    if (typeof thisInstance[fieldListFunctionName] != 'undefined') {
        var fieldList = thisInstance[fieldListFunctionName].apply()
    }

    var values = [];
    var webhook_description = $('[name="webhook_description"]').val();
    var webhook_method = $('[name="webhook_method"]').val();
    var webhook_content_type = $('[name="webhook_content_type"]').val();
    var webhook_url = $('[name="webhook_url"]').val();
    var webhook_authorization = $('[name="webhook_authorization"]:checked').val();
    var webhook_authorization_username = $('[name="webhook_authorization_username"]').val();
    var webhook_authorization_password = $('[name="webhook_authorization_password"]').val();
    var webhook_task_id = $('[name="webhook_task_id"]').val();
    var task_info = {'webhook_description':webhook_description,
        'webhook_method':webhook_method,
        'webhook_content_type':webhook_content_type,
        'webhook_url':webhook_url,
        'webhook_authorization':webhook_authorization,
        'webhook_authorization_username':webhook_authorization_username,
        'webhook_authorization_password':webhook_authorization_password
    };
    values.push(task_info);
    var conditions = jQuery('.conditionRow', conditionsContainer);
    conditions.each(function (i, conditionDomElement) {
        var rowElement = jQuery(conditionDomElement);
        var fieldSelectElement = jQuery('[name="fieldname"]', rowElement);
        var valueSelectElement = jQuery('[data-value="value"]', rowElement);
        //To not send empty fields to server
        if (thisInstance.isEmptyFieldSelected(fieldSelectElement)) {
            return true;
        }
        var fieldDataInfo = fieldSelectElement.find('option:selected').data('fieldinfo');
        var fieldType = fieldDataInfo.type;
        var rowValues = {};
        if (fieldType == 'owner') {
            for (var key in fieldList) {
                var field = fieldList[key];
                if (field == 'value' && valueSelectElement.is('select')) {
                    rowValues[field] = valueSelectElement.find('option:selected').val();
                } else {
                    rowValues[field] = jQuery('[name="' + field + '"]', rowElement).val();
                }
            }
        }
        else if (fieldType == 'picklist' || fieldType == 'multipicklist') {
            for (var key in fieldList) {
                var field = fieldList[key];
                if (field == 'value' && valueSelectElement.is('input')) {
                    var commaSeperatedValues = valueSelectElement.val();
                    var pickListValues = valueSelectElement.data('picklistvalues');
                    var valuesArr = commaSeperatedValues.split(',');
                    var newvaluesArr = [];
                    for (i = 0; i < valuesArr.length; i++) {
                        if (typeof pickListValues[valuesArr[i]] != 'undefined') {
                            newvaluesArr.push(pickListValues[valuesArr[i]]);
                        } else {
                            newvaluesArr.push(valuesArr[i]);
                        }
                    }
                    var reconstructedCommaSeperatedValues = newvaluesArr.join(',');
                    rowValues[field] = reconstructedCommaSeperatedValues;
                } else if (field == 'value' && valueSelectElement.is('select') && fieldType == 'picklist') {
                    rowValues[field] = valueSelectElement.val();
                } else if (field == 'value' && valueSelectElement.is('select') && fieldType == 'multipicklist') {
                    var value = valueSelectElement.val();
                    if (value == null) {
                        rowValues[field] = value;
                    } else {
                        rowValues[field] = value.join(',');
                    }
                } else {
                    rowValues[field] = jQuery('[name="' + field + '"]', rowElement).val();
                }
            }

        }
        else if (fieldType == 'text') {
            for (var key in fieldList) {
                var field = fieldList[key];
                if (field == 'value') {
                    rowValues[field] = rowElement.find('textarea').val();
                } else {
                    rowValues[field] = jQuery('[name="' + field + '"]', rowElement).val();
                }
            }
        }
        else {
            for (var key in fieldList) {
                var field = fieldList[key];
                if (field == 'value') {
                    rowValues[field] = valueSelectElement.val();
                } else {
                    rowValues[field] = jQuery('[name="' + field + '"]', rowElement).val();
                }
            }
        }
        if (jQuery('[name="valuetype"]', rowElement).val() == 'false' || (jQuery('[name="valuetype"]', rowElement).length == 0)) {
            rowValues['valuetype'] = 'rawtext';
        }
        //var button = jQuery('button.add-group', rowElement);
        var button = rowElement.find('button.add-group');
        var index = button[0].dataset['index'];
        var parent = button[0].dataset['parent'];
        var group = button[0].dataset['group'];
        rowValues['webhook_description'] = webhook_description;
        rowValues['webhook_method'] = webhook_method;
        rowValues['webhook_content_type'] = webhook_content_type;
        rowValues['webhook_url'] = webhook_url;
        rowValues['webhook_authorization'] = webhook_authorization;
        rowValues['webhook_authorization_username'] = webhook_authorization_username;
        rowValues['webhook_authorization_password'] = webhook_authorization_password;
        rowValues['webhook_task_id'] = webhook_task_id;
        rowValues['index'] = index;
        rowValues['parent'] = parent;
        rowValues['group'] = group;
        values.push(rowValues);
    });
    var responseConditionsContainer = jQuery('#save_response_fieldvaluemapping');
    var responseConditions = jQuery('.conditionRow', responseConditionsContainer);
    responseConditions.each(function (i, responseConditionDomElement) {
        var row = $(responseConditionDomElement);
        var field = row.find('select[name="module_field_name_response_map"]').val();
        var map_field = row.find('input[name="api_response_field_name"]').val();
        var rowMap = {'vt_map_field':field,'api_map_field':map_field};
            values.push(rowMap);
    });
    return values;
};