!function (e) {
    "use strict";
    jQuery(document).on("click", ".roleLabel", function (e) {
        jQuery(this).closest("[type=checkbox]").prop("checked", !0)
    }), jQuery(document).on("click", "#submit_roles", function (r) {
        var c = [];
        jQuery("input:checked").each(function () {
            c.push(jQuery(this).val())
        });
        var s = jQuery("#hab_capabilties").val(),
            a = [];
        if (jQuery(".icheck-square .tag").each(function (e) {
            var r = jQuery.trim(jQuery(this).children("span").html());
            r.replace(/\s/g, ""), a.push(r)
        }), s = a.join(","), jQuery("#hide_for_all").is(":checked")) var i = "yes";
        else i = "no";
        if (jQuery("#hide_for_all_guests").is(":checked")) var o = "yes";
        else o = "no";

        jQuery.ajax({
            type: "POST",		// use $_POST request to submit data
            url: ajaxVar.url,	// URL to "wp-admin/admin-ajax.php"
            data: {
                action: "save_user_roles",
                UserRoles: c,
                caps: s,
                disableForAll: i,
                forGuests: o,
                hbaNonce: ajaxVar.hba_nonce,
            },
            success: function (data) {
                if ("Success" === data) {
                    window.location.reload();
                } else {
                    alert("");
                }
            }
        });

    })
}(jQuery);