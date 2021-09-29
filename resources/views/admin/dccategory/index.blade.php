@section('button')
@parent
<button type="button" id="add-dccategory" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple d-none" onclick="windowLocation('{{route('documentcategory.create')}}')">
    <b><i class="fas fa-plus"></i></b> Create
</button>
<button type="button" id="filter-dccategory" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple d-none" onclick="filterDcCategory()">
    <b><i class="fas fa-search"></i></b> Filter
</button>
@endsection

<div class="tab-pane fade show" id="dccategory" role="tabpanel" aria-labelledby="dccategory-tab">
    <div class="table-responsive">
        <table id="table-dccategory" class="table table-striped datatable" width="100%">
            <thead>
                <tr>
                    <th width="5">No.</th>
                    <th width="100">Sub Menu</th>
                    <th width="100">Document Type</th>
                    <th width="20" class="text-center">#</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@push('filter')
<div class="modal fade" id="form-filter-dccategory">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Filter Doc Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-search-dccategory" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="menu_id">Sub Menu</label>
                                <select name="menu_id" id="menu_id" class="select2 form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="document_type_id">Document Type</label>
                                <select name="document_type_id" id="document_type_id" class="select2 form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple">
                            <b><i class="fas fa-search"></i></b>
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endpush

@section('scripts')
@parent
<script>
    var actionmenu  = @json(json_encode($actionmenu));

    function filterDcCategory() {
        $('#form-filter-dccategory').modal('show');
    }
    function editDcCategory(id) {
        document.location = '{{route('documentcategory.index')}}/' + id + '/edit';
    }
    function viewDcCategory(id) {
        document.location = '{{route('documentcategory.index')}}/' + id;
    }
    $(function(){
        $(".select2").select2();
        dataTableDcCategory = $('#table-dccategory').DataTable( {
            processing: true,
            language: {
                processing: `<div class="p-2 text-center">
            <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
            </div>`
            },
            serverSide: true,
            filter: false,
            responsive: true,
            lengthChange: false,
            pageLength: 50,
            order: [[ 1, "asc" ]],
            ajax: {
                url: "{{route('documentcategory.read')}}",
                type: "GET",
                data:function(data){
                    var menu_id             = $('#form-search-dccategory').find('select[name=menu_id]').val();
                    var document_type_id    = $('#form-search-dccategory').find('select[name=document_type_id]').val();
                    data.menu_id            = menu_id;
                    data.document_type_id   = document_type_id;
                }
            },
            columnDefs:[
                {
                    orderable: false,targets:[0,3]
                },
                { className: "text-right", targets: [0] },
                { className: "text-center", targets: [3] },
                {
                    width: "5%",
                    render: function ( data, type, row ) {
                        return row.no;
                    },targets: [0]
                },
                {
                    render: function ( data, type, row ) {
                        return row.menu ? row.menu.menu_name : '';
                    },targets: [1]
                },
                {
                    render: function ( data, type, row ) {
                        return row.doctype ? row.doctype.code : '';
                    },targets: [2]
                },
                {
                    width: "10%",
                    render: function ( data, type, row ) {
                        var button  = '';
                        if (actionmenu.indexOf('update') > 0) {
                            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="editDcCategory(${row.id})">
                                        <i class="far fa-edit"></i>Update Data
                                       </a>`;
                        }
                        if (actionmenu.indexOf('delete') > 0) {
                            button += `<a class="dropdown-item delete" href="javascript:void(0);" data-id="${row.id}">
                                        <i class="fa fa-trash-alt"></i> Delete Data
                                       </a>`;
                        }
                    return `<div class="btn-group">
                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu">
                        ${button}
                    </div>
                    </div>`;
                    },targets: [3]
                }
            ],
            columns: [
                { data: "no" },
                { data: "menu_id" },
                { data: "document_type_id" },
                { data: "id" },
            ]
        });

        $('#form-search-dccategory').submit(function (e) {
            e.preventDefault();
            dataTableDcCategory.draw();
            $('#form-filter-dccategory').modal('hide');
        });

        $('#menu_id').select2({
            placeholder: "Choose Sub Menu ...",
            ajax: {
                url: "{{ route('menu.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        route: 'documentcenter',
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    var more    = (params.page * 30) < data.total;
                    var option  = [];
                    $.each(data.rows, function(index, item) {
                        if (item.child) {
                            $.each(item.child, function(indexChild, child) {
                                option.push({
                                    id: child.id,
                                    text: `${child.menu_name}`,
                                });
                            })
                        }
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        });

        $('#document_type_id').select2({
            placeholder: "Choose Document Type ...",
            ajax: {
                url: "{{ route('documenttype.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    var more    = (params.page * 30) < data.total;
                    var option  = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: `${item.code}`,
                            name: `${item.name}`,
                        });
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).data('id');
            bootbox.confirm({
                buttons: {
                    confirm: {
                        label: '<i class="fa fa-check"></i>',
                        className: 'btn-primary btn-sm'
                    },
                    cancel: {
                        label: '<i class="fa fa-undo"></i>',
                        className: 'btn-default btn-sm'
                    },
                },
                title: 'Delete data?',
                message: 'Are you sure want to delete this site?',
                callback: function (result) {
                    if (result) {
                        var data = {
                            _token: "{{ csrf_token() }}"
                        };
                        $.ajax({
                            url: `{{route('documentcategory.index')}}/${id}`,
                            dataType: 'json',
                            data: data,
                            type: 'DELETE',
                            beforeSend: function () {
                                blockMessage('#content', 'Loading', '#fff');
                            }
                        }).done(function (response) {
                            $('#content').unblock();
                            if (response.status) {
                                toastr.options = {
                                    "closeButton": false,
                                    "debug": false,
                                    "newestOnTop": false,
                                    "progressBar": false,
                                    "positionClass": "toast-top-right",
                                    "preventDuplicates": false,
                                    "onclick": null,
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                }
                                toastr.success(response.message);
                                dataTableDcCategory.ajax.reload(null, false);
                            }else {
                                toastr.options = {
                                    "closeButton": false,
                                    "debug": false,
                                    "newestOnTop": false,
                                    "progressBar": false,
                                    "positionClass": "toast-top-right",
                                    "preventDuplicates": false,
                                    "onclick": null,
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                }
                                toastr.warning(response.message);
                            }
                        }).fail(function (response) {
                            var response = response.responseJSON;
                            $('#content').unblock();
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                            toastr.warning(response.message);
                        })
                    }
                }
            });
        });
    });
</script>
@endsection
