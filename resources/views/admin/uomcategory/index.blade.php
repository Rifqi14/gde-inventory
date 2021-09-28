@extends('admin.layouts.app')
@section('title',$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            {{ $menu_name }}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ $parent_name }}</li>
            <li class="breadcrumb-item">{{ $menu_name }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="conteiner-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if(in_array('read',$actionmenu))
                        <button type="button" id="filter-product-category" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple float-right ml-1" onclick="filter()">
                            <b><i class="fas fa-search"></i></b> Search
                        </button>
                        @endif
                        @if(in_array('create',$actionmenu))
                        <button type="button" id="add-product-category" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple float-right ml-1" onclick="windowLocation('{{route('uomcategory.create')}}')">
                            <b><i class="fas fa-plus"></i></b> Create
                        </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class=" table-responsive">
                            <table id="table-uom-category" class="table table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="10%">No.</th>
                                        <th width="50">Code</th>
                                        <th width="200">Name</th>
                                        <th class="text-center" width="10%">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="add-filter" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="filter-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Category Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-search" method="POST">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="code">Code</label>
                                    <input class="form-control" type="text" name="code" id="code" placeholder="Code">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="category-name">Name</label>
                                    <input class="form-control" type="text" name="name" id="category-name" placeholder="Name">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-labeled  btn-danger btn-sm btn-sm btn-flat legitRipple" data-dismiss="modal" onclick="resetTable()"><b><i class="fas fa-times"></i></b> Cancel</button>
                <button type="submit" form="form-search" class="btn btn-labeled  btn-default  btn-sm btn-flat legitRipple"><b><i class="fas fa-search"></i></b> Search</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var actionmenu = JSON.parse('{!! json_encode($actionmenu) !!}');
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
    };
    $(function() {
        dataTable = $('#table-uom-category').DataTable({
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
            order: [
                [1, "asc"]
            ],
            ajax: {
                url: "{{route('uomcategory.read')}}",
                type: "GET",
                data: function(data) {
                    var name = $('#form-search').find('input[name=name]').val(),
                        code = $('#form-search').find('input[name=code]').val();
                    data.code = code;
                    data.name = name;
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 3]
                },
                {
                    className: "text-center",
                    targets: [0, 3]
                },
                {
                    render: function(data, type, row) {
                        var button = '';
                        // update
                        if (actionmenu.indexOf('update') > 0) {
                            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                                        <i class="far fa-edit"></i>Update Data
                                    </a>`;
                        }
                        // delete
                        if (actionmenu.indexOf('delete') > 0) {
                            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="destroy(${row.id})">
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
                    },
                    targets: [3]
                }
            ],
            columns: [{
                    data: "no"
                },
                {
                    data: "code"
                },
                {
                    data: "name"
                }
            ]
        });

        $('#form-search').submit(function(e) {
            e.preventDefault();
            dataTable.draw();
            $('#add-filter').modal('hide');
        });
    });

    function filter() {
        $('#form-search').find('input[name=name]').val('');
        $('#form-search').find('input[name=code]').val('');
        $('#add-filter').modal('show');
    }

    function edit(id) {
        if (!id) {
            toastr.warning('Data not found.');
            console.log({
                errorMessage: 'Data not found because id is empty.'
            });
            return false;
        }
        window.location.href = `{{url('admin/uomcategory/edit')}}/${id}`;
    }

    function destroy(id) {
        if (!id) {
            toastr.warning('Data not found.');
            console.log({
                errorMessage: 'Data not found because id is empty.'
            });
            return false;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "Erased data cannot be reserved",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3d9970',
            cancelButtonColor: '#d81b60',
            confirmButtonText: 'Yes, i am sure',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.value) {
                var data = {
                    _token: "{{ csrf_token() }}"
                };
                $.ajax({
                    url: `{{url('admin/uomcategory/delete')}}/${id}`,
                    dataType: 'json',
                    data: data,
                    type: 'GET',
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Data has been deleted.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                dataTable.row(id).remove().draw(false);
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to delete data.',
                                'error'
                            );
                        }
                    }
                });

            }
        });
    }

    function resetTable() {
        $('#form-search').find('input[name=name]').val('');
        $('#form-search').find('input[name=code]').val('');
        dataTable.draw();
    }
</script>
@endsection
