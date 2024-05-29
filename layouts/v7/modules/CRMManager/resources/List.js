/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_List_Js("CRMManager_List_Js", {
}, {
	registerManagerChildCrmClickEvent :function() {
		jQuery('#page').on('click', '.managerChildCrm', function(e){
			var elem = jQuery(e.currentTarget);
			app.helper.showProgress("Uploading source code to child crm, pls wait");
			var recordId = elem.data('id');
				var params = {
					module : 'CRMManager',
					action : 'ActionAjax',
					mode : 'managerChildCrm',
					recordId : recordId
				};
				app.request.post({'data': params}).then(
					function(err,data) {
						if (err === null) {
							app.helper.hideProgress();
							console.log("data:",data);
							if(data.success){
								app.helper.showSuccessNotification({message : app.vtranslate(data.message)});
								var setup_crm_path = data.setup_crm_path;
								window.location.href = setup_crm_path;
							}
							else{
								app.helper.showErrorNotification({message : data.message});
							}
						}
						else {
							app.helper.hideProgress();
							app.helper.showErrorMessage(err.message);
						}
					}
				);
		});
	},
	registerUpdateChildCrmClickEvent :function() {
		jQuery('#page').on('click', '.updateChildCrm', function(e){
			var elem = jQuery(e.currentTarget);
			app.helper.showProgress("Uploading source code to child crm, pls wait");
			var recordId = elem.data('id');
			var params = {
				module : 'CRMManager',
				action : 'ActionAjax',
				mode : 'updateChildCrm',
				type : 'One',
				recordId : recordId
			};
			app.request.post({'data': params}).then(
				function(err,data) {
					if (err === null) {
						app.helper.hideProgress();
						console.log("data:",data);
						if(data.success){
							app.helper.showSuccessNotification({message : app.vtranslate(data.message)});
						}
						else{
							app.helper.showErrorNotification({message : data.message});
						}
					}
					else {
						app.helper.hideProgress();
						app.helper.showErrorMessage(err.message);
					}
				}
			);
		});
	},
	registerAddUpdateAllBtn: function(){
	 var btn = '<li><button id="CRMManager_listView_basicAction_LBL_UPDATEALL" type="button" class="btn  btn-default module-buttons"><div class="fa fa-upload" aria-hidden="true"></div>&nbsp;&nbsp;Update all childs</button></li>';
	 jQuery("ul.navbar-nav").prepend(btn);
	},
	registerUpdateAllChildCrmClickEvent :function() {
		jQuery('#page').on('click', '#CRMManager_listView_basicAction_LBL_UPDATEALL', function(e){
			app.helper.showProgress("Uploading source code to all child crm, pls wait");
			var params = {
				module : 'CRMManager',
				action : 'ActionAjax',
				mode : 'updateChildCrm',
				type : 'All',
				recordId : 0
			};
			app.request.post({'data': params}).then(
				function(err,data) {
					if (err === null) {
						app.helper.hideProgress();
						if(data.success){
							app.helper.showSuccessNotification({message : app.vtranslate(data.message)});
						}
						else{
							app.helper.showErrorNotification({message : data.message});
						}
					}
					else {
						app.helper.hideProgress();
						app.helper.showErrorMessage(err.message);
					}
				}
			);
		});
	},
	registerPushDataToChildCrmClickEvent :function() {
		jQuery('#page').on('click', '.pushDataToChildCrm', function(e){
			var elem = jQuery(e.currentTarget);
			var recordId = elem.data('id');
			app.helper.showProgress("Pushing data to child crm, pls wait");
			var params = {
				module : 'CRMManager',
				action : 'ActionAjax',
				mode : 'pushDataToChildCrm',
				recordId : recordId
			};
			app.request.post({'data': params}).then(
				function(err,data) {
					if (err === null) {
						app.helper.hideProgress();
						if(data.success){
							app.helper.showSuccessNotification({message : app.vtranslate(data.message)});
						}
						else{
							app.helper.showErrorNotification({message : data.message});
						}
					}
					else {
						app.helper.hideProgress();
						app.helper.showErrorMessage(err.message);
					}
				}
			);
		});
	},
	registerEvents: function() {
		this._super();
		this.registerManagerChildCrmClickEvent();
		this.registerUpdateChildCrmClickEvent();
		this.registerAddUpdateAllBtn();
		this.registerUpdateAllChildCrmClickEvent();
		this.registerPushDataToChildCrmClickEvent();
	}
});