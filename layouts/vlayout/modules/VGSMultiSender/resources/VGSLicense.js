jQuery.Class("VGSLicense_Js", {}, {
    validateLicense: function() {
        thisInstance = this, jQuery(document).ready(function() {
            var e = jQuery("#licenseid").val('csuk');
            var t = jQuery(".listViewLoadingMsg").text();
            if ("deactivate" == jQuery(this).attr("id")) var a = "deactivateLicense";
            else var a = "validateLicense";
            var i = jQuery("#activationid").val(),
                n = jQuery.progressIndicator({
                    message: t,
                    position: "html",
                    blockInfo: {
                        enabled: !0
                    }
                }),
                s = jQuery("#module_name").val(),
                r = "module=" + s + "&view=VGSLicense&mode=" + a + "&licenseid=" + e + "&activationid=" + i;
            AppConnector.request(r).then(function(e) {
                console.log(e);
                if (n.progressIndicator({
                    mode: "hide"
                }),e, e.success) {
                    var t = e.result;


                    if ("ok" == t.result) {
                        "validateLicense" == a ? (thisInstance.updateInput("readonly"), thisInstance.updateButton("deactivate")) : (thisInstance.updateInput("noreadonly"), thisInstance.updateButton("activate"));
                        var i = {
                            title: "License validated",
                            text: "Thanks! Your license was succesfully validated",
                            width: "35%"
                        };
                        window.location = 'index.php?module=VGSMultiSender&view=SettingsIndex&parent=Settings&block=4&fieldid=40';
                        Vtiger_Helper_Js.showPnotify(i)
                    } else {
                        var i = {
                            title: "License Validation Error",
                            text: "Sorry. There was an error processing your request. ERROR Message: " + t.message,
                            width: "35%"
                        };
                        Vtiger_Helper_Js.showPnotify(i), console.log(t.message)
                    }
                }
            }, function() {
                var e = {
                    title: "License Validation Error",
                    text: "Sorry. There was an error processing your request",
                    width: "35%"
                };
                Vtiger_Helper_Js.showPnotify(e)
            })

        })
    },
    updateInput: function(e) {
        "readonly" == e ? jQuery("#licenseid").attr("readonly", !0) : jQuery("#licenseid").attr("readonly", !1)
    },
    updateButton: function(e) {
        thisInstance = this, "deactivate" === e ? (jQuery("#activate").hide(), jQuery("#deactivate").show(), thisInstance.showHideManualValidation()) : (jQuery("#deactivate").hide(), jQuery("#activate").show(), jQuery("#licenseid").val(""))
    },
    showHideManualValidation: function() {
        jQuery("#enable-manual").on("click", function() {
            jQuery("#manual-activation").is(":visible") ? jQuery("#manual-activation").hide() : jQuery("#manual-activation").show()
        })
    },
    registerEvents: function() {
        this.validateLicense(), this.showHideManualValidation(), jQuery("#isvalid").val() ? (this.updateButton("deactivate"), this.updateInput("readonly")) : this.updateButton("activate")
    }
}), jQuery(document).ready(function() {
    var e = new VGSLicense_Js;
    e.registerEvents()
});

//for show left menu
if (typeof Settings_Vtiger_Index_Js !== 'undefined') {
    Settings_Vtiger_Index_Js('Settings_VGSMultiSender_VGSLicenseSettings_Js', {}, {
        registerEvents: function () {
            this._super();
        }
    });
}
