$(document).ready(function() {
    $(".current-user").hide();
    $("#useExistingEmail").on("click", function() {
        if ($("#useExistingEmail").is(":checked")) {
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
            $(".current-user").hide();
            $(".new-user").show();
        }
    });
});
