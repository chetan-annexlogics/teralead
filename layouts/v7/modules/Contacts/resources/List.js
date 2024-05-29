/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_List_Js("Contacts_List_Js", {
}, {
	registerAddAppointmentInfoClickEvent :function() {
		jQuery('#page').on('click', '.addAppointmentInfo', function(e){
			var params = {};
			var recordId = $(this).data('id');
			params.module = app.getModuleName();
			params.view = 'ShowCustomPopup';
			params.record = recordId;
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
									var formData = jQuery(frm).serialize();
									app.helper.showProgress();
									app.request.post({data: formData}).then(function (err, data) {
										app.helper.hideProgress();
										if (!err) {
											var appointmentDate = $('[name="cf_919"]').val();
											var appointmentTime = $('[name="cf_921"]').val();

											$('#Contacts_detailView_fieldValue_cf_919 span.value').html(appointmentDate);
											$('[data-name="cf_919"]').attr('data-displayvalue', appointmentDate);
											$('[data-name="cf_919"]').attr('data-value', appointmentDate);

											$('#Contacts_detailView_fieldValue_cf_cf_921 span.value').html(appointmentTime);
											$('[data-name="cf_921"]').attr('data-displayvalue', appointmentTime);
											$('[data-name="cf_921"]').attr('data-value', appointmentTime);

											app.helper.showSuccessNotification({"message": "Update fields " + data});
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
			);
		});
	},
	/**
	 * Function to send Email when click to email link
	 */
	registerEmailLinkClick: function () {
		var thisIntance = this;
		$("#listedit").on("click","a.emailField",function(event){
			event.preventDefault();
			var recordId = $(this).closest("tr").find('input.listViewEntriesCheckBox').val();
			thisIntance.triggerSendOneEmail('index.php?module=Contacts&view=MassActionAjax&mode=showComposeEmailForm&step=step1&relatedLoad=true','Emails',recordId);
		});
	},
	/**
	 * Function to send Email when click to email link
	 */
	registerCheckDefaultList: function () {
		var currentUrl = $(location).attr('href');
		if(!currentUrl.includes("goback")){
			var params = {};
			params.module = app.getModuleName();
			params.action = 'ActionAjax';
			params.mode = 'checkDefaultListView';
			app.request.get({ data: params }).then(
				function (error, data) {
					if(error=== null){
						if(data.default){
							VTDevKBView_Js.initData_VTDevKBView();
						}
					}
				}
			);
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
	addKanbanLink: function () {
		var kanbanLink = 	'<li>' +
			'<a  style="border: 1px solid;height: 31px;margin: 5px;" title="KanBan View" onclick="javascript:VTDevKBView_Js.initData_VTDevKBView()">' +
			'<img style="width: 25px;height: 20px;margin-top: -6px;" src ="layouts/v7/modules/VTDevKBView/images/kanban_ico.png" />' +
			'</a>' +
			'<li>';
		$("#appnav ul.navbar-nav").prepend(kanbanLink);
	},
	registerEvents: function() {
		this._super();
		this.registerAddAppointmentInfoClickEvent();
		this.registerEmailLinkClick();
		this.registerCheckDefaultList();
		this.addKanbanLink();
	}
});