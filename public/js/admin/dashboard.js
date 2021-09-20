(function ($) {
    $(document).ready(function () {
        $('#m_company').modal('show');
        setTimeout(function () {
            $('#data-table-achievement').dataTable().fnDestroy();
            var table = $('#data-table-achievement').DataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
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
                    'url': companyList + '/0/0',
                    'type': 'GET',
                    'dataType': 'JSON',
                    'error': function (xhr, textStatus, ThrownException) {
                        alert('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
                    }
                },
                columns: [
                    {
                        data: 'company_name',
                        name: 'company_name',
                        width: '6%'
                    },
                    {
                        data: 'pic_email',
                        name: 'pic_email',
                        width: '6%'
                    },
                    {
                        data: 'no_telp',
                        name: 'no_telp',
                        width: '6%'
                    },
                    {
                        data: 'company_id',
                        width: '6%',
                        orderable: false,
                        render: function (data, type, row) {
                            var action_view = '<button onclick="approveModal(this)" class="btn btn-primary btn-xs margr5 approve" data-id="'+data+'" data-name="'+row['company_name']+'"><i class="fa fa-thumbs-up" title="Approve"></i></button>'+
                            '<button onclick="rejectModal(this)" class="btn btn-danger btn-xs margr5 reject" data-id="'+data+'" data-name="'+row['company_name']+'"><i class="fa fa-times" title="Reject"></i></button>';
                            return action_view;
                        }
                    }
                ],
                order: [[0, 'desc']]
            });
        }, 500);

        setInterval(refreshPage, 1000);

        $('#m_company').on('show.bs.modal', function (event) {
            $('#data-table-achievement').dataTable().fnDestroy();
            var table = $('#data-table-achievement').DataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
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
                    'url': companyList + '/0/0',
                    'type': 'GET',
                    'dataType': 'JSON',
                    'error': function (xhr, textStatus, ThrownException) {
                        alert('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
                    }
                },
                columns: [
                    {
                        data: 'company_name',
                        name: 'company_name',
                        width: '6%'
                    },
                    {
                        data: 'pic_email',
                        name: 'pic_email',
                        width: '6%'
                    },
                    {
                        data: 'no_telp',
                        name: 'no_telp',
                        width: '6%'
                    },
                    {
                        data: 'company_id',
                        width: '6%',
                        orderable: false,
                        render: function (data, type, row) {
                            var action_view = '<button onclick="approveModal(this)" class="btn btn-primary btn-xs margr5 approve" data-id="'+data+'" data-name="'+row['company_name']+'"><i class="fa fa-thumbs-up" title="Approve"></i></button>'+
                            '<button onclick="rejectModal(this)" class="btn btn-danger btn-xs margr5 void" data-id="'+data+'" data-name="'+row['company_name']+'"><i class="fa fa-times" title="Reject"></i></button>';
                            return action_view;
                        }
                    }
                ],
                order: [[0, 'desc']]
            });
        });

        window.approveModal = function (element) {
            var data = $(element).data('id');
            var name = $(element).data('name');
            $('#titleReport').html("Void Visit");
            var html = "<input type='hidden' name='idAccess' value='locked' />";
            html += "<input type='hidden' name= 'company_id' value='" + data + "' />";
            html += "Approve Company <strong>" + name + "</strong> ?";
            $('.modalsContent').html(html);
            $('#m_company').modal('hide');
            $('#approveModal').modal('show');
        };
        window.rejectModal = function (element) {
            var data = $(element).data('id');
            var name = $(element).data('name');
            $('#titleReport').html("Unvoid Visit");
            var html = "<input type='hidden' name='idAccess' value='locked' />";
            html += "<input type='hidden' name= 'company_id' value='" + data + "' />";
            html += "Reject Company <strong>" + name + "</strong> ?";
            $('.modalsContent').html(html);
            $('#m_company').modal('hide');
            $('#rejectModal').modal('show');
        };
    });
})(jQuery);