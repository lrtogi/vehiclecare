var user_id;
$(document).ready(function() {
    if (
        $("#user_type").val() == "" ||
        $("#user_type").val() == "0" ||
        $("#user_type").val() == "3"
    ) {
        $(".need-company").hide();
    }
    $("#user_type").on("change", function() {
        if (this.value == 2 || this.value == 1) {
            $(".need-company").show();
        } else {
            $(".need-company").hide();
        }
    });
});
