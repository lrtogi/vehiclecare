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
                        // render: function (data, type, row) {
                        //     var action_view = row.proposed_date[0];
                        //     for(var i=1; i<row.proposed_date.length; i++)
                        //     {
                        //         action_view += "<BR>"+row.proposed_date[i];
                        //     }
                        //     return action_view;
                        // }
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
                        // render: function (data, type, row) {
                        //     var action_view = row.proposed_date[0];
                        //     for(var i=1; i<row.proposed_date.length; i++)
                        //     {
                        //         action_view += "<BR>"+row.proposed_date[i];
                        //     }
                        //     return action_view;
                        // }
                    }
                ],
                order: [[0, 'desc']]
            });
        });
    });
})(jQuery);