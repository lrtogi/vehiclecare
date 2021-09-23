$(document).ready(function() {
    var table = $("#data-table-achievement").DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        createdRow: function(row, data, index) {
            if (data["active"] == 0 && data["approved"] == 1) {
                $("td", row).addClass("bg-danger");
                $("td", row).addClass("text-white");
            } else if (data["approved"] == 0) {
                $("td", row).addClass("bg-warning");
            }
        },
        ajax: {
            url: companyList + "/all/all",
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
                data: "company_id",
                name: "company_id",
                width: "6%"
            },
            {
                data: "company_name",
                name: "company_name",
                width: "6%"
            },
            {
                data: "pic_email",
                name: "pic_email",
                width: "6%"
            },
            {
                data: "alamat_perusahaan",
                name: "alamat_perusahaan",
                width: "6%"
            },
            {
                data: "no_telp",
                name: "no_telp",
                width: "6%"
            },
            {
                data: "active",
                name: "active",
                width: "6%",
                render: function(data, type, row) {
                    if (data == 1) {
                        return "Active";
                    } else {
                        return "Not Active";
                    }
                }
            },
            {
                data: "approved",
                name: "approved",
                width: "6%",
                render: function(data, type, row) {
                    if (data == 1) {
                        return "Approved";
                    }
                    if (data == 2) {
                        return "Rejected";
                    }
                    if (data == 0) {
                        return "Waiting";
                    }
                }
            },
            {
                data: "company_id",
                width: "6%",
                orderable: false,
                render: function(data, type, row) {
                    var action_view = "";
                    if (row["approved"] == 0) {
                        action_view =
                            action_view +
                            '<button onclick="approveModal(this)" class="btn btn-primary btn-xs margr5 approve" data-id="' +
                            data +
                            '" data-name="' +
                            row["company_name"] +
                            '"><i class="fa fa-thumbs-up" title="Approve"></i></button>' +
                            '<button onclick="rejectModal(this)" class="btn btn-danger btn-xs margr5 void" data-id="' +
                            data +
                            '" data-name="' +
                            row["company_name"] +
                            '"><i class="fa fa-times" title="Reject"></i></button>';
                    } else if (row["active"] == 0 && row["approved"] == 1) {
                        action_view =
                            action_view +
                            '<button onclick="unvoidModal(this)" class="btn btn-success btn-xs margr5 void" data-id="' +
                            data +
                            '" data-name="' +
                            row["company_name"] +
                            '"><i class="fa fa-check" title="Activate"></i></button>';
                    } else if (row["active"] == 1 && row["approved"] == 1) {
                        action_view =
                            action_view +
                            '<button onclick="voidModal(this)" class="btn btn-danger btn-xs margr5 void" data-id="' +
                            data +
                            '" data-name="' +
                            row["company_name"] +
                            '"><i class="fa fa-trash" title="Deactivate"></i></button>';
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
        $("#titleReportApprove").html("Approve Company");
        var html = "<input type='hidden' name='idAccess' value='locked' />";
        html +=
            "<input type='hidden' name= 'company_id' value='" + data + "' />";
        html += "Approve Company <strong>" + name + "</strong> ?";
        $(".modalDelete").html(html);
        $("#approveModal").modal("show");
    };
    window.rejectModal = function(element) {
        var data = $(element).data("id");
        var name = $(element).data("name");
        $("#titleReportReject").html("Reject Company");
        var html = "<input type='hidden' name='idAccess' value='locked' />";
        html +=
            "<input type='hidden' name= 'company_id' value='" + data + "' />";
        html += "Reject Company <strong>" + name + "</strong> ?";
        $(".modalDelete").html(html);
        $("#rejectModal").modal("show");
    };

    window.voidModal = function(element) {
        var data = $(element).data("id");
        var name = $(element).data("name");
        $("#titleReportVoid").html("Deactivate Company");
        var html = "<input type='hidden' name='idAccess' value='locked' />";
        html +=
            "<input type='hidden' name= 'company_id' value='" + data + "' />";
        html += "Deactivate Company <strong>" + name + "</strong> ?";
        $(".modalDelete").html(html);
        $("#voidModal").modal("show");
    };
    window.unvoidModal = function(element) {
        var data = $(element).data("id");
        var name = $(element).data("name");
        $("#titleReportUnvoid").html("Activate Company");
        var html = "<input type='hidden' name='idAccess' value='locked' />";
        html +=
            "<input type='hidden' name= 'company_id' value='" + data + "' />";
        html += "Activate Company <strong>" + name + "</strong> ?";
        $(".modalDelete").html(html);
        $("#unvoidModal").modal("show");
    };
});
