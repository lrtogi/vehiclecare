$(document).ready(function() {
    $(".current-user").hide();
    $("input:checkbox[name='useExistingEmail1']").on("click", function() {
        if ($("input:checkbox[name='useExistingEmail1']").is(":checked")) {
            $("input:checkbox[name='useExistingEmail2']").prop("checked", true);
            $(".current-user").show();
            $(".new-user").hide();
            $.getJSON(getUserUrl + "/" + 0, function(data) {
                Vue.set(app, "user_email_select", data);
                if (data.result) {
                    Vue.set(app, "user_email", "");
                    Vue.nextTick(function() {
                        $("#user_email_selector").select2();
                    });
                }
            });
        } else {
            $("input:checkbox[name='useExistingEmail2']").prop(
                "checked",
                false
            );
            $(".current-user").hide();
            $(".new-user").show();
        }
    });

    $("input:checkbox[name='useExistingEmail2']").on("click", function() {
        if ($("input:checkbox[name='useExistingEmail2']").is(":checked")) {
            $("input:checkbox[name='useExistingEmail1']").prop("checked", true);
            $(".current-user").show();
            $(".new-user").hide();
            $.getJSON(getUserUrl + "/" + 0, function(data) {
                Vue.set(app, "user_email_select", data);
                if (data.result) {
                    Vue.set(app, "user_email", "");
                    Vue.nextTick(function() {
                        $("#user_email_selector").select2();
                    });
                }
            });
        } else {
            $("input:checkbox[name='useExistingEmail1']").prop(
                "checked",
                false
            );
            $(".current-user").hide();
            $(".new-user").show();
        }
    });

    $(".is_numeric").keyup(function(e) {
        if (/\D/g.test(this.value)) {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, "");
        }
    });
});
