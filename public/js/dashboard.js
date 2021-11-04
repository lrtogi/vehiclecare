(function($) {
    $(document).ready(function() {
        var today = new Date();
        var date =
            today.getFullYear() +
            "-" +
            (today.getMonth() + 1) +
            "-" +
            today.getDate();
        var dateTime = date;
        retrieveTableData();
        setInterval(refreshPage, 5000);
        setInterval(retrieveTableData, 5000);

        $("#m_payment").on("show.bs.modal", function(event) {
            $("#data-table-achievement")
                .dataTable()
                .fnDestroy();
            var table = $("#data-table-achievement").DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: paymentList + "/0/1/all/all",
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
                        data: "customer_name",
                        name: "m_customer_vehicle.customer_name",
                        width: "6%"
                    },
                    {
                        data: "payment_date",
                        name: "payments.payment_date",
                        width: "6%"
                    },
                    {
                        data: "package_name",
                        name: "m_package.package_name",
                        width: "6%"
                    },
                    {
                        data: "total_price",
                        name: "transactions.total_price",
                        width: "6%"
                    },
                    {
                        data: "total_payment",
                        name: "payments.total_payment",
                        width: "6%"
                    },
                    {
                        data: "payment_id",
                        width: "6%",
                        orderable: false,
                        render: function(data, type, row) {
                            var action_view =
                                '<button onclick="showDetailModal(this)" class="btn btn-secondary btn-xs margr5 detail" data-file="' +
                                row["file"] +
                                '" data-name="' +
                                row["customer_name"] +
                                '" data-date = "' +
                                row["payment_date"] +
                                '" data-total_price = "' +
                                row["total_price"] +
                                '" data-total_payment = "' +
                                row["total_payment"] +
                                '"><i class="fa fa-search" title="Detail"></i></button>' +
                                '<button onclick="approveModal(this)" class="btn btn-primary btn-xs margr5 approve" data-id="' +
                                data +
                                '" data-name="' +
                                row["customer_name"] +
                                '" data-date = "' +
                                row["payment_date"] +
                                '"><i class="fa fa-thumbs-up" title="Approve"></i></button>' +
                                '<button onclick="rejectModal(this)" class="btn btn-danger btn-xs margr5 reject" data-id="' +
                                data +
                                '" data-name="' +
                                row["customer_name"] +
                                '" data-date = "' +
                                row["payment_date"] +
                                '"><i class="fa fa-times" title="Reject"></i></button>';
                            return action_view;
                        }
                    }
                ],
                order: [[0, "desc"]]
            });
        });

        window.showDetailModal = function(element) {
            var file = $(element).data("file");
            var name = $(element).data("name");
            var date = $(element).data("date");
            var total_price = $(element).data("total_price");
            var total_payment = $(element).data("total_payment");
            $("#titleReport").html("Detail Payment");
            var html =
                '<div class="form-group row">' +
                '<label for="order_date" class="col-md-4 col-form-label">Payment Date : </label>' +
                '<div class="col-md-6">' +
                '<label for="order_date" class="col-form-label">' +
                date +
                "</label>" +
                "</div>" +
                "</div>";
            html +=
                '<div class="form-group row">' +
                '<label for="Customer Name" class="col-md-4 col-form-label">Customer Name : </label>' +
                '<div class="col-md-6">' +
                '<label for="order_date" class="col-form-label">' +
                name +
                "</label>" +
                "</div>" +
                "</div>";
            html +=
                '<div class="form-group row">' +
                '<label for="Total Price" class="col-md-4 col-form-label">Customer Name : </label>' +
                '<div class="col-md-6">' +
                '<label for="order_date" class="col-form-label">' +
                total_price +
                "</label>" +
                "</div>" +
                "</div>";
            html +=
                '<div class="form-group row">' +
                '<label for="Total Payment" class="col-md-4 col-form-label">Customer Name : </label>' +
                '<div class="col-md-6">' +
                '<label for="order_date" class="col-form-label">' +
                total_payment +
                "</label>" +
                "</div>" +
                "</div>";
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
                '<button type="button" id="downloadImage" onclick="downloadImage()" class="btn btn-success btn-xs margr5"><i class="fa fa-download" title="download"></i> Download Image</button>' +
                "</div>" +
                "</div>";

            $(".modalsContent").html(html);
            $("#showDetailModal").modal("show");
        };
        window.approveModal = function(element) {
            var data = $(element).data("id");
            var name = $(element).data("name");
            var date = $(element).data("date");
            $("#titleReport").html("Approve Payment");
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
                "<div class='row input-wrapper'><div class='col-sm-6'><label class='col-form-label'>Transaction Approval<span class='text-danger'></span></label></div><div class='col-sm-6'><select class='form-control' name='approval_type'><option value='1' selected>Completed</option><option value='4'>Half Approved</option></select></div></div>";
            $(".modalsContent").html(html);
            $("#approveModal").modal("show");
        };
        window.rejectModal = function(element) {
            var data = $(element).data("id");
            var name = $(element).data("name");
            var date = $(element).data("date");
            $("#titleReport").html("Reject Payment");
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
                "</strong> ? <br>";
            $(".modalsContent").html(html);
            $("#rejectModal").modal("show");
        };
        window.downloadImage = function() {
            let img = document.querySelector("#imageFile");

            let imagePath = img.getAttribute("src");
            let fileName = getFileName(imagePath);
            saveAs(imagePath, fileName);
        };

        function retrieveTableData() {
            var today = new Date();
            var date =
                today.getFullYear() +
                "-" +
                (today.getMonth() + 1) +
                "-" +
                today.getDate();
            var dateTime = date;
            vehicleType.forEach(element => {
                var vehicle_id = element.vehicle_id;
                var tableID =
                    "#data-table-achievement-" + element.vehicle_id + "";
                $(tableID)
                    .dataTable()
                    .fnDestroy();
                var table = $(tableID).DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    ajax: {
                        url:
                            jobUrl +
                            "/home/" +
                            vehicle_id +
                            "/" +
                            dateTime +
                            "/" +
                            dateTime,
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
                            data: "customer_name",
                            name: "m_customer_vehicle.customer_name",
                            width: "6%"
                        },
                        {
                            data: "vehicle_name",
                            name: "m_customer_vehicle.vehicle_name",
                            width: "6%"
                        },
                        {
                            data: "package_name",
                            name: "m_package.package_name",
                            width: "6%"
                        },
                        {
                            data: "status",
                            name: "jobs.status",
                            width: "6%",
                            render: function(data, type, row) {
                                if (data == 0) {
                                    return "Waiting";
                                } else if (data == 1) {
                                    return "On Process";
                                } else if (data == 2) {
                                    return "Finished";
                                } else return "Taken";
                            }
                        },
                        {
                            data: "worker_name",
                            name: "m_worker.worker_name",
                            width: "6%"
                        }
                    ],
                    order: [[0, "desc"]]
                });
            });
        }
    });
})(jQuery);
