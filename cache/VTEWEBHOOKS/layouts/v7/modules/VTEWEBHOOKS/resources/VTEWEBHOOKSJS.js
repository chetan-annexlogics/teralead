jQuery(document).ready(function () {
    var VTEWEBHOOKS = {
        registerEventForSettingTaskAuthen:function(){
            if(app.getModuleName() == 'Workflows' && app.getViewName() == 'Edit'){
                $('body').delegate('.authorization-input','click',function(){
                    var val = this.value;
                    if(val == 'off'){
                        $('.authorization-info').hide();
                        $('[name="webhook_authorization_username"]').val('');
                        $('[name="webhook_authorization_password"]').val('');
                    }else{
                        $('.authorization-info').show();
                    }
                });
            }
        },
        registerEventForFormatDisplayJson:function(){
            if(app.getModuleName() == 'VTEWebhookRequests' && app.getViewName() == 'Detail'){
                var span = $('#VTEWebhookRequests_detailView_fieldValue_mapped_response span.value');
                var html = span.html();
                html = html.replace(/","/g,"\",<br>\"");
                span.html(html);
                var span = $('#VTEWebhookRequests_detailView_fieldValue_update_to_vtiger span.value');
                var html = span.html();
                html = html.replace(/","/g,"\",<br>\"");
                span.html(html);
                var span = $('#VTEWebhookRequests_detailView_fieldValue_request_response span.value');
                var html = span.html();
                html = html.replace(/\,/g,",<br>");
                span.html(html);
                var span = $('#VTEWebhookRequests_detailView_fieldValue_request span.value');
                var html = span.html();
                html = html.replace(/\,/g,",<br>");
                span.html(html);
            }
        },
        registerAddGroup:function (){
            var self = this;
          $('body').delegate('button.add-group','click',function(){
              var conditionRow = $(this).closest('div.conditionRow');
              var input = conditionRow.find('.getPopupUi');
              var input_new = conditionRow.find('[name="fieldValue"]');
              var group = $(this).data('group');
              var parent = $(this).data('parent');
              var index = $(this).data('index');
              var sub_group = group + 1;
              var sub_parent = parent;
              input.val('');
              input_new.val('');
              input.attr('disabled','disabled');
              input_new.attr('disabled','disabled');
              var newAddFieldContainer = jQuery('.basicAddFieldContainer').clone(true, true).removeClass('basicAddFieldContainer hide').addClass('conditionRow');
              jQuery('select', newAddFieldContainer).addClass('select2');
              jQuery('button.add-group', newAddFieldContainer).attr('data-group',sub_group);
              jQuery('button.add-group', newAddFieldContainer).attr('data-parent',index);
              var max_sub_index = self.getMaxIndex(index);
              jQuery('button.add-group', newAddFieldContainer).attr('data-index',max_sub_index + 1);
              var mr_left = group * 15;
              newAddFieldContainer.css({'margin-left':mr_left+'px','margin-top':'7px','margin-bottom':'7px'})
              conditionRow.after(newAddFieldContainer);
          });
        },
        getMaxIndex:function(parent){
            var buttons = $('button.add-group[data-parent="'+parent+'"]');
            var val = parent * 10;
            var max = 0;
            if(buttons.length > 0){
                buttons.each(function(k,item){
                    var sub_index = $(item).data('index');
                    if(sub_index > max){
                        max = sub_index;
                    }
                });
            }
            if(max > 0)val = max;
            return val;
        },
        registerEvents : function () {
            this.registerEventForSettingTaskAuthen();
            this.registerEventForFormatDisplayJson();
            this.registerAddGroup();
        }
    };VTEWEBHOOKS.registerEvents();
    $('.modal').on('hidden.bs.modal', function () {
        //delete Settings_Workflows_Edit_Js.prototype.registerVTEWEBHOOKSTaskEvents;
        //delete Settings_Workflows_Edit_Js.prototype.registerFieldChangeVTEWEBHOOKS;
        Settings_Workflows_Edit_Js.prototype.registerVTUpdateFieldsTaskEvents = function () {
            var thisInstance = this;
            this.registerAddFieldEvent();
            this.registerDeleteConditionEvent();
            this.registerFieldChange();
            this.fieldValueMap = false;
            if (jQuery('#fieldValueMapping').val() != '') {
                this.fieldValueReMapping();
            }
            var fields = jQuery('#save_fieldvaluemapping').find('select[name="fieldname"]');
            jQuery.each(fields, function (i, field) {
                thisInstance.loadFieldSpecificUi(jQuery(field));
            });
            this.getPopUp(jQuery('#saveTask'));
        },
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
                    vtUtils.showSelect2ElementView(createEntityContainer.find('.select2'));
                    thisInstance.registerAddFieldEvent();
                    thisInstance.fieldValueMap = false;
                    if (jQuery('#fieldValueMapping').val() != '') {
                        thisInstance.fieldValueReMapping();
                    }
                    var fields = jQuery('#save_fieldvaluemapping').find('select[name="fieldname"]');
                    jQuery.each(fields, function (i, field) {
                        thisInstance.loadFieldSpecificUi(jQuery(field));
                    });
                });
            });
        },
        Settings_Workflows_Edit_Js.prototype.registerAddFieldEvent = function () {
            jQuery('#addFieldBtn').on('click', function (e) {
                var newAddFieldContainer = jQuery('.basicAddFieldContainer').clone(true, true).removeClass('basicAddFieldContainer hide').addClass('conditionRow');
                jQuery('select', newAddFieldContainer).addClass('select2');
                jQuery('#save_fieldvaluemapping').append(newAddFieldContainer);
                vtUtils.showSelect2ElementView(newAddFieldContainer.find('.select2'));
            });
        }
        //delete Settings_Workflows_Edit_Js.prototype.checkHaveChilds;
        //delete Settings_Workflows_Edit_Js.prototype.registerDisableParentsOfGroup;
        Settings_Workflows_Edit_Js.prototype.registerDeleteConditionEvent = function () {
            jQuery('#saveTask').on('click', '.deleteCondition', function (e) {
                jQuery(e.currentTarget).closest('.conditionRow').remove();
            })
        }
        //delete Settings_Workflows_Edit_Js.prototype.getVTEWEBHOOKSTaskFieldList;
        //delete Settings_Workflows_Edit_Js.prototype.preSaveVTEWEBHOOKSTask;
        //delete Settings_Workflows_Edit_Js.prototype.VTEWEBHOOKSTaskCustomValidation;
        //delete Settings_Workflows_Edit_Js.prototype.loadFieldSpecificUiVTEWEBHOOKSTask;
        //delete Settings_Workflows_Edit_Js.prototype.getValuesVTEWEBHOOKSTask;
    })
});