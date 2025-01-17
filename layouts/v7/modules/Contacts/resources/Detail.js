/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Detail_Js("Contacts_Detail_Js", {}, {
	registerAjaxPreSaveEvents: function (container) {
		var thisInstance = this;
		app.event.on(Vtiger_Detail_Js.PreAjaxSaveEvent, function (e) {
			if (!thisInstance.checkForPortalUser(container)) {
				e.preventDefault();
			}
		});
	},
	/**
	 * Function to send Email when click to email link
	 */
	registerEmailLinkClick: function () {
		$(document).on("click","a.emailField",function(event){
			event.preventDefault();
			var targetEmail = $(this).attr("href");
			targetEmail = targetEmail.replace(/mailto:/, '');
			var params = {
				to:targetEmail,
			}
			Vtiger_Detail_Js.triggerSendEmail('index.php?module=Contacts&view=MassActionAjax&mode=showComposeEmailForm&step=step1&relatedLoad=true','Emails',params);
		});
	},
	/**
	 * Function to check for Portal User
	 */
	checkForPortalUser: function (form) {
		var element = jQuery('[name="portal"]', form);
		var response = element.is(':checked');
		
		if (response) {
			var primaryEmailField = jQuery('[data-name="email"]');

			if (primaryEmailField.length == 0) {
				app.helper.showErrorNotification({message: app.vtranslate('JS_PRIMARY_EMAIL_FIELD_DOES_NOT_EXISTS')});
				return false;
			}

			var primaryEmailValue = primaryEmailField["0"].data("value");
			if (primaryEmailValue == "") {
				app.helper.showErrorNotification({message: app.vtranslate('JS_PLEASE_ENTER_PRIMARY_EMAIL_VALUE_TO_ENABLE_PORTAL_USER')});
				return false;
			}
		}
		return true;
	},
	/**
	 * Function which will register all the events
	 */
	
	registerShowCustomPopupEvents: function() {
		var thisInstance = this;
		var params = {};
		params.module = app.getModuleName();
		params.action = 'ActionAjax';
		params.record = app.getRecordId();
		params.mode = 'GetColor';
		//app.helper.showProgress();
		app.request.get({ data: params }).then(
			function (error, data) {
				//app.helper.hideProgress();
				if(!error && data) {
					thisInstance.showCustomPopupAjaxRequest(data);
				}
			}
		);
	},
	showCustomPopupAjaxRequest: function (color) {
		var params = {};
		params.module = app.getModuleName();
		params.view = 'ShowCustomPopup';
		params.record = app.getRecordId();
		params.color = color;
		if (color === 'Appointment Booked' || color === 'Closed Leads') {
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
											if (color === 'Appointment Booked') {
												var appointmentDate = $('[name="cf_919"]').val();
												var appointmentTime = $('[name="cf_921"]').val();
												//var appointmentStatus = $('[name="cf_909"]').val();

												$('#Contacts_detailView_fieldValue_cf_919 span.value').html(appointmentDate);
												$('[data-name="cf_919"]').attr('data-displayvalue', appointmentDate);
												$('[data-name="cf_919"]').attr('data-value', appointmentDate);

												$('#Contacts_detailView_fieldValue_cf_cf_921 span.value').html(appointmentTime);
												$('[data-name="cf_921"]').attr('data-displayvalue', appointmentTime);
												$('[data-name="cf_921"]').attr('data-value', appointmentTime);

												// $('#Contacts_detailView_fieldValue_cf_909 span.value').find('span').html(appointmentStatus);
												// $('[data-name="cf_909"]').attr('data-displayvalue', appointmentStatus);
												// $('[data-name="cf_909"]').attr('data-value', appointmentStatus);
											} else if (color === 'Closed Leads') {
												var paymentRecieved  = $('[name="cf_929"]').val();

												$('#Contacts_detailView_fieldValue_cf_929 span.value').html(paymentRecieved);
												$('[data-name="cf_929"]').attr('data-displayvalue', paymentRecieved);
												$('[data-name="cf_929"]').attr('data-value', paymentRecieved);
											}
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
			)
		}
	},
	addKanbanLink: function () {
		var kanbanLink = '<a  style="border: 1px solid;height: 31px;padding: 5px;float: left;margin-right: 3px;" title="KanBan View" href="index.php?module=VTDevKBView&view=Index&source_module=Contacts&app=MARKETING">' +
			'<img style="width: 25px;height: 20px;margin-top: -6px;" src ="layouts/v7/modules/VTDevKBView/images/kanban_ico.png" />' +
			'</a>';
		$("div.detailViewButtoncontainer .btn-group:first-child").prepend(kanbanLink);
	},

	registerEvents: function () {
		var thisInstance = this
		var form = this.getForm();
		this._super();
		this.registerAjaxPreSaveEvents(form);
		// this.registerShowCustomPopupEvents();
		app.event.on(Vtiger_Detail_Js.PostAjaxSaveEvent, function (e, fieldBasicData, postSaveRecordDetails,contentHolder) {
			if(fieldBasicData.attr('data-name') === 'cf_913') {
				thisInstance.showCustomPopupAjaxRequest(postSaveRecordDetails.cf_889.value);
			}
		});
		this.registerEmailLinkClick();
		this.addKanbanLink();
	}
});

jQuery(document).ajaxComplete( function (event, request, settings) {
	var url = settings.url;
	if(url == undefined) return;
	if (url.indexOf('module=Contacts') > -1 && url.indexOf('view=Detail') > -1 && url.indexOf('mode=showDetailViewByMode') > -1) {
		var instance = new Contacts_Detail_Js();
		if($('#ContactCustomPopup').length <= 0) {
			setTimeout(function() {
				instance.registerShowCustomPopupEvents();
			}, 100)
		}
	}
});