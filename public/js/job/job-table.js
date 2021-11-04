$(document).ready(function() {
    $("#startdate").on("change", function() {
        dateTime = $("#startdate").val();
    });
    dateTime = $("#startdate").val();
    window.searchData = function(element) {
        dateTime = $("#startdate").val();
        vehicleType.forEach(element => {
            var vehicle_id = element.vehicle_id;
            var tableID = "#data-table-achievement-" + element.vehicle_id + "";
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
    };
    searchData();
    setInterval(searchData, 5000);
});
