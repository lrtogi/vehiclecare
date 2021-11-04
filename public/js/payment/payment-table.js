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
                            '" data-file="' +
                            row["file"] +
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
                            '" data-file="' +
                            row["file"] +
                            '"><i class="fa fa-times" title="Reject"></i></button>';
                    }
                    return action_view;
                }
            }
        ],
        order: [[0, "desc"]]
    });
};

(function($) {
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
                                '" data-file="' +
                                row["file"] +
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
                                '" data-file="' +
                                row["file"] +
                                '"><i class="fa fa-times" title="Reject"></i></button>';
                        }
                        return action_view;
                    }
                }
            ],
            order: [[0, "desc"]]
        });

        window.approveModal = function(element) {
            var file = $(element).data("file");
            var data = $(element).data("id");
            var name = $(element).data("customer_name");
            var date = $(element).data("payment_date");
            $("#titleReportApprove").html("Approve Payment");
            var html = "<input type='hidden' name='idAccess' value='locked' />";
            html +=
                "<input type='hidden' name= 'payment_id' value='" +
                data +
                "' />";
            html +=
                "Approve Payment <strong>" +
                name +
                "</strong> on <strong>" +
                date +
                "</strong> ? <br>";
            html +=
                '<div class="form-group row">' +
                '<label for="Customer Name" class="col-md-4 col-form-label">Image File : </label>' +
                '<div class="col-md-6">' +
                '<img id="imageFile" src="' +
                image_url +
                "/" +
                file +
                '" class=""/>' +
                "</div>" +
                "</div>";
            html +=
                '<div class="form-group row">' +
                '<label for="Download Button" class="col-md-4 col-form-label"></label>' +
                '<div class="col-md-6">' +
                '<button type="button" id="downloadImage" class="btn btn-success btn-xs margr5"><i class="fa fa-download" title="download"></i> Download Image</button>' +
                "</div>" +
                "</div>";
            html +=
                "<div class='row input-wrapper'><div class='col-sm-6'><label class='col-form-label'>Transaction Approval<span class='text-danger'></span></label></div><div class='col-sm-6'><select class='form-control' name='approval_type'><option value='1' selected>Completed</option><option value='4'>Half Approved</option></select></div></div>";
            $(".modalsContent").html(html);
            $("#approveModal").modal("show");

            let btnDownload = document.querySelector("#downloadImage");
            btnDownload.addEventListener("click", () => {
                alert("bos");
                let img = document.querySelector("#imageFile");

                let imagePath = img.getAttribute("src");
                let fileName = getFileName(imagePath);
                saveAs(imagePath, fileName);
            });
        };
        window.rejectModal = function(element) {
            var file = $(element).data("file");
            var data = $(element).data("id");
            var name = $(element).data("customer_name");
            var date = $(element).data("payment_date");
            $("#titleReportReject").html("Reject Payment");
            var html = "<input type='hidden' name='idAccess' value='locked' />";
            html +=
                "<input type='hidden' name= 'payment_id' value='" +
                data +
                "' />";
            html +=
                "Reject Payment <strong>" +
                name +
                "</strong> on <strong>" +
                date +
                "</strong> ?";
            html +=
                '<div class="form-group row">' +
                '<label for="Customer Name" class="col-md-4 col-form-label">Image File : </label>' +
                '<div class="col-md-6">' +
                '<img id="imageFile" src="' +
                image_url +
                "/" +
                file +
                '" class=""/>' +
                "</div>" +
                "</div>";
            html +=
                '<div class="form-group row">' +
                '<label for="Download Button" class="col-md-4 col-form-label"></label>' +
                '<div class="col-md-6">' +
                '<button type="button" id="downloadImage" class="btn btn-success btn-xs margr5"><i class="fa fa-download" title="download"></i> Download Image</button>' +
                "</div>" +
                "</div>";
            $(".modalsContent").html(html);
            $("#rejectModal").modal("show");

            let btnDownload = document.querySelector("#downloadImage");
            btnDownload.addEventListener("click", () => {
                alert("bos");
                let img = document.querySelector("#imageFile");

                let imagePath = img.getAttribute("src");
                let fileName = getFileName(imagePath);
                saveAs(imagePath, fileName);
            });
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
})(jQuery);
