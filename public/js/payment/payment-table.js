$(document).ready(function() {
    // $(".datepicker-datelimit-init").datepicker();
    var startdate = $("#startdate").val();
    var enddate = $("#enddate").val();
    var created_by_customer = $("#created_by_customer").val();
    var status = $("#select-status").val();
    $("#data-table-achievement")
        .dataTable()
        .fnDestroy();
    var table = $("#data-table-achievement").DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        // "createdRow": function (row, data, index) {
        // 	if (data['flag_approve'] == 0) {
        // 		$('td', row).addClass('waiting');
        // 	}
        // 	else if (data['flag_approve'] == 1) {
        // 		$('td', row).addClass('approved');
        // 	}
        // 	else if (data['flag_approve'] == 2) {
        // 		$('td', row).addClass('rejected');
        // 	}
        // },
        ajax: {
            url:
                searchUrl +
                "/" +
                status +
                "/" +
                created_by_customer +
                "/" +
                startdate +
                "/" +
                enddate,
            type: "GET",
            dataType: "JSON",
            error: function(xhr, textStatus, ThrownException) {
                alert(
                    "Error loading data. Exception: " +
                        ThrownException +
                        "\n" +
                        textStatus
                );
            }
        },
        columns: [
            {
                width: "6%",
                data: "payment_date",
                name: "payments.payment_date"
            },
            {
                data: "customer_name",
                name: "m_customer_vehicle.customer_name",
                width: "6%"
            },
            {
                data: "total_payment",
                name: "payments.total_payment",
                width: "6%"
            },
            {
                data: "total_price",
                name: "transactions.total_price",
                width: "6%"
            },
            {
                data: "approved",
                name: "payments.approved",
                width: "6%",
                render: function(data, type, row) {
                    var result = "";
                    if (data == 0) {
                        result = "Pending";
                    } else if (data == 1) {
                        result = "Approved";
                    } else if (data == 2) {
                        result = "Reject";
                    }
                    return result;
                }
            },
            {
                data: "payment_id",
                name: "payments.payment_id",
                width: "6%",
                orderable: false,
                render: function(data, type, row) {
                    var action_view = "";
                    if (row["approved"] == 0) {
                        var action_view =
                            '<button onclick="approveModal(this)" class="btn btn-primary btn-xs margr5 approve" data-id="' +
                            data +
                            '" data-customer_name="' +
                            row["customer_name"] +
                            '" data-payment_date="' +
                            row["payment_date"] +
                            '"><i class="fa fa-thumbs-up" title="Approve"></i></button>' +
                            '<button onclick="rejectModal(this)" class="btn btn-danger btn-xs margr5 void" data-id="' +
                            data +
                            '" data-customer_name="' +
                            row["customer_name"] +
                            '" data-payment_date="' +
                            row["payment_date"] +
                            '"><i class="fa fa-times" title="Reject"></i></button>';
                    }
                    return action_view;
                }
            }
        ],
        order: [[0, "desc"]]
    });

    window.approveModal = function(element) {
        var data = $(element).data("id");
        var name = $(element).data("customer_name");
        var date = $(element).data("payment_date");
        $("#titleReportApprove").html("Approve Payment");
        var html = "<input type='hidden' name='idAccess' value='locked' />";
        html +=
            "<input type='hidden' name= 'payment_id' value='" + data + "' />";
        html +=
            "Approve Payment <strong>" +
            name +
            "</strong> on <strong>" +
            date +
            "</strong> ? <br>";
        html +=
            "<div class='row input-wrapper'><div class='col-sm-6'><label class='col-form-label'>Transaction Approval<span class='text-danger'></span></label></div><div class='col-sm-6'><select class='form-control' name='approval_type'><option value='1' selected>Completed</option><option value='4'>Half Approved</option></select></div></div>";
        $(".modalsContent").html(html);
        $("#m_company").modal("hide");
        $("#approveModal").modal("show");
    };
    window.rejectModal = function(element) {
        var data = $(element).data("id");
        var name = $(element).data("customer_name");
        var date = $(element).data("payment_date");
        $("#titleReportReject").html("Reject Payment");
        var html = "<input type='hidden' name='idAccess' value='locked' />";
        html +=
            "<input type='hidden' name= 'payment_id' value='" + data + "' />";
        html +=
            "Reject Payment <strong>" +
            name +
            "</strong> on <strong>" +
            date +
            "</strong> ?";
        $(".modalsContent").html(html);
        $("#m_company").modal("hide");
        $("#rejectModal").modal("show");
    };

    window.locked = function(element) {
        var data = $(element).data("transaction_id");
        var dataName = $(element).data("customer_name");
        var orderDate = $(element).data("order_date");
        $("#titleReport").html("Delete Payment");
        var html = "<input type='hidden' name='idAccess' value='locked' />";
        html +=
            "<input type='hidden' name= 'transaction_id' value='" +
            data +
            "' />";
        html +=
            "Delete Transaction <strong>" +
            dataName +
            "</strong>" +
            " on <strong>" +
            orderDate +
            "</strong> ?";
        $(".modalDelete").html(html);
        $("#deleteModal").modal("show");
    };
});
window.searchData = function(element) {
    var startdate = $("#startdate").val();
    var enddate = $("#enddate").val();
    var created_by_customer = $("#created_by_customer").val();
    var status = $("#select-status").val();
    $("#data-table-achievement")
        .dataTable()
        .fnDestroy();
    var table = $("#data-table-achievement").DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        // "createdRow": function (row, data, index) {
        // 	if (data['flag_approve'] == 0) {
        // 		$('td', row).addClass('waiting');
        // 	}
        // 	else if (data['flag_approve'] == 1) {
        // 		$('td', row).addClass('approved');
        // 	}
        // 	else if (data['flag_approve'] == 2) {
        // 		$('td', row).addClass('rejected');
        // 	}
        // },
        ajax: {
            url:
                searchUrl +
                "/" +
                status +
                "/" +
                created_by_customer +
                "/" +
                startdate +
                "/" +
                enddate,
            type: "GET",
            dataType: "JSON",
            error: function(xhr, textStatus, ThrownException) {
                alert(
                    "Error loading data. Exception: " +
                        ThrownException +
                        "\n" +
                        textStatus
                );
            }
        },
        columns: [
            {
                width: "6%",
                data: "payment_date",
                name: "payments.payment_date"
            },
            {
                data: "customer_name",
                name: "m_customer_vehicle.customer_name",
                width: "6%"
            },
            {
                data: "total_payment",
                name: "payments.total_payment",
                width: "6%"
            },
            {
                data: "total_price",
                name: "transactions.total_price",
                width: "6%"
            },
            {
                data: "approved",
                name: "payments.approved",
                width: "6%",
                render: function(data, type, row) {
                    var result = "";
                    if (data == 0) {
                        result = "Pending";
                    } else if (data == 1) {
                        result = "Approved";
                    } else if (data == 2) {
                        result = "Reject";
                    }
                    return result;
                }
            },
            {
                data: "payment_id",
                name: "payments.payment_id",
                width: "6%",
                orderable: false,
                render: function(data, type, row) {
                    var action_view = "";
                    if (row["approved"] == 0) {
                        var action_view =
                            '<button onclick="approveModal(this)" class="btn btn-primary btn-xs margr5 approve" data-id="' +
                            data +
                            '" data-customer_name="' +
                            row["customer_name"] +
                            '" data-payment_date="' +
                            row["payment_date"] +
                            '"><i class="fa fa-thumbs-up" title="Approve"></i></button>' +
                            '<button onclick="rejectModal(this)" class="btn btn-danger btn-xs margr5 void" data-id="' +
                            data +
                            '" data-customer_name="' +
                            row["customer_name"] +
                            '" data-payment_date="' +
                            row["payment_date"] +
                            '"><i class="fa fa-times" title="Reject"></i></button>';
                    }
                    return action_view;
                }
            }
        ],
        order: [[0, "desc"]]
    });
};
