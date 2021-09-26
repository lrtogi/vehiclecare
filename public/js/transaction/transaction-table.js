(function($) {
    $(document).ready(function() {
        $("#m_company").modal("show");
        var startdate = $("#startdate").val();
        var enddate = $("#enddate").val();
        var vehicleType = $("#select-vehicleType").val();
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
                    vehicleType +
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
                    data: "transaction_date",
                    name: "transactions.transaction_date"
                },
                {
                    data: "customer_name",
                    name: "m_customer_vehicle.customer_name",
                    width: "6%"
                },
                {
                    data: "order_date",
                    name: "transactions.order_date",
                    width: "6%"
                },
                {
                    data: "package_name",
                    name: "m_package.package_name",
                    width: "6%"
                },
                {
                    data: "vehicle_type",
                    name: "m_vehicle.vehicle_type",
                    width: "6%"
                },
                {
                    data: "total_price",
                    name: "transactions.total_price",
                    width: "6%"
                },
                {
                    data: "status",
                    name: "transactions.status",
                    width: "6%",
                    render: function(data, type, row) {
                        var result = "";
                        if (data == 0) {
                            result = "Pending Approval";
                        } else if (data == 1) {
                            result = "Approved";
                        } else {
                            result = "Canceled";
                        }
                        return result;
                    }
                },
                {
                    data: "transaction_id",
                    name: "transactions.transaction_id",
                    width: "6%",
                    orderable: false,
                    render: function(data, type, row) {
                        var action_view = "";
                        if (row["transactions.status"] == 0) {
                            action_view =
                                action_view +
                                '<button onclick="approveModal(this)" class="btn btn-primary btn-xs margr5 approve" data-id="' +
                                data +
                                '" data-customer_name="' +
                                row["m_customer.customer_name"] +
                                '" data-date ="' +
                                row["transactions.order_date"] +
                                '" ><i class="fa fa-thumbs-up" title="Approve"></i></button>' +
                                '<button onclick="rejectModal(this)" class="btn btn-danger btn-xs margr5 void" data-id="' +
                                data +
                                '" data-customer_name="' +
                                row["m_customer.customer_name"] +
                                '" data-date ="' +
                                row["transactions.order_date"] +
                                '" ><i class="fa fa-times" title="Reject"></i></button>';
                        }
                        return action_view;
                    }
                }
            ],
            order: [[0, "desc"]]
        });

        window.approveModal = function(element) {
            var data = $(element).data("id");
            var name = $(element).data("name");
            $("#titleReport").html("Approve Company");
            var html = "<input type='hidden' name='idAccess' value='locked' />";
            html +=
                "<input type='hidden' name= 'transaction_id' value='" +
                data +
                "' />";
            html += "Approve Transaction <strong>" + name + "</strong> ?";
            $(".modalsContent").html(html);
            $("#m_company").modal("hide");
            $("#approveModal").modal("show");
        };
        window.rejectModal = function(element) {
            var data = $(element).data("id");
            var name = $(element).data("name");
            $("#titleReport").html("Reject Company");
            var html = "<input type='hidden' name='idAccess' value='locked' />";
            html +=
                "<input type='hidden' name= 'transaction_id' value='" +
                data +
                "' />";
            html += "Reject Company <strong>" + name + "</strong> ?";
            $(".modalsContent").html(html);
            $("#m_company").modal("hide");
            $("#rejectModal").modal("show");
        };
    });
    window.searchData = function(element) {
        $("#data-table-achievement")
            .dataTable()
            .fnDestroy();
        var startdate = $("#startdate").val();
        var enddate = $("#enddate").val();
        var vehicleType = $("#select-vehicleType").val();
        var status = $("#select-status").val();
        var link =
            searchUrl +
            "/" +
            status +
            "/" +
            vehicleType +
            "/" +
            startdate +
            "/" +
            enddate;
        table = $("#data-table-achievement").DataTable({
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
                    vehicleType +
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
                    data: "transaction_date",
                    name: "transactions.transaction_date"
                },
                {
                    data: "customer_name",
                    name: "m_customer.customer_name",
                    width: "6%"
                },
                {
                    data: "order_date",
                    name: "transactions.order_date",
                    width: "6%"
                },
                {
                    data: "package_name",
                    name: "m_package.package_name",
                    width: "6%"
                },
                {
                    data: "vehicle_type",
                    name: "m_vehicle.vehicle_type",
                    width: "6%"
                },
                {
                    data: "total_price",
                    name: "transactions.total_price",
                    width: "6%"
                },
                {
                    data: "status",
                    name: "transactions.status",
                    width: "6%",
                    render: function(data, type, row) {
                        var result = "";
                        if (data == 0) {
                            result = "Pending Approval";
                        } else if (data == 1) {
                            result = "Approved";
                        } else {
                            result = "Canceled";
                        }
                        return result;
                    }
                },
                {
                    data: "transaction_id",
                    name: "transactions.transaction_id",
                    width: "6%",
                    orderable: false,
                    render: function(data, type, row) {
                        var action_view = "";
                        if (row["transactions.status"] == 0) {
                            action_view =
                                action_view +
                                '<button onclick="approveModal(this)" class="btn btn-primary btn-xs margr5 approve" data-id="' +
                                data +
                                '" data-customer_name="' +
                                row["m_customer.customer_name"] +
                                '" data-date ="' +
                                row["transactions.order_date"] +
                                '" ><i class="fa fa-thumbs-up" title="Approve"></i></button>' +
                                '<button onclick="rejectModal(this)" class="btn btn-danger btn-xs margr5 void" data-id="' +
                                data +
                                '" data-customer_name="' +
                                row["m_customer.customer_name"] +
                                '" data-date ="' +
                                row["transactions.order_date"] +
                                '" ><i class="fa fa-times" title="Reject"></i></button>';
                        }
                        return action_view;
                    }
                }
            ],
            order: [[2, "asc"]]
        });
    };
})(jQuery);
