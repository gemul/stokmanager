<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Master Supplier</h1>
    <button onclick="tambahSupplier()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Supplier</button>
</div>

<!-- Content Row -->
<div class="row">

    <!-- DataTales Example -->
    <div class="card shadow mb-4 datatable-card">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Supplier</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Supplier</th>
                            <th>URL</th>
                            <th>Source</th>
                            <th style='width:70px;'></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Nama Supplier</th>
                            <th>URL</th>
                            <th>Source</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- Content Row -->
<script>
    var dataTabel;
    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#dataTable tfoot th').each(function() {
            var title = $(this).text();
            if (title != "") {
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            }
        });
        dataTabel = $('#dataTable').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "sDom": "lrtip",
            "ajax": "api/api.php?mod=supplier.list",
            'columns': [{
                    'orderable': true
                }, //id
                {
                    'orderable': true
                }, //nama
                {
                    'orderable': true
                }, //url
                {
                    'orderable': true
                }, //source
                {
                    'orderable': false
                }, //act
            ]
        });
        // apply search
        dataTabel.columns().every(function() {
            var that = this;

            $('input', this.footer()).on('keyup change', function() {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
        });
    });

    function tambahSupplier() {
        $("#modalSupplier").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modalSupplier").modal("show");
    }

    function simpanSupplier(form) {
        var form = $('#formSupplier');
        event.preventDefault();
        $.ajax({
            url: "api/api.php?mod=supplier.add",
            data: form.serialize(),
            type: "POST",
            beforeSend: function() {
                $('#modalSupplier .modal-footer button').prop('disabled', true);
                $('#modalSupplier .modal-footer button:nth-child(1)').html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#modalSupplier").modal("hide");
                    $("#formSupplier [name='namasupplier']").val("");
                    $("#formSupplier [name='url']").val("");
                    $("#formSupplier [name='source']").val("");
                    notifikasi(result.message, "success");
                    dataTabel.ajax.reload();
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $('#modalSupplier .modal-footer button').prop('disabled', false);
                $('#modalSupplier .modal-footer button:nth-child(1)').html("Add");
            }
        });
    }

    function supplierEdit(id) {

        $("#modalSupplierEdit").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modalSupplierEdit").modal("show");

        $.ajax({
            url: "api/api.php?mod=supplier.get&id=" + id,
            type: "GET",
            beforeSend: function() {
                $('#modalSupplierEdit .modal-footer button').prop('disabled', true);
                $('#modalSupplierEdit .modal-body div:nth-child(1)').html("<i class='fa fa-spinner fa-spin'></i> Memuat");
                $('#modalSupplierEdit #formSupplierEdit').hide();
            },
            success: function(result) {
                if (result.status == 1) {
                    $('#modalSupplierEdit #formSupplierEdit').show();
                    $('#modalSupplierEdit .modal-footer button').prop('disabled', false);
                    $("#formSupplierEdit [name='idsupplier']").val(result.data[0].idsupplier);
                    $("#formSupplierEdit [name='namasupplier']").val(result.data[0].namasupplier);
                    $("#formSupplierEdit [name='url']").val(result.data[0].url);
                    $("#formSupplierEdit [name='source']").val(result.data[0].source);
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $('#modalSupplierEdit .modal-body div:nth-child(1)').html("");
            }
        });

    }

    function updateSupplier() {
        var form = $('#formSupplierEdit');
        event.preventDefault();
        $.ajax({
            url: "api/api.php?mod=supplier.update",
            data: form.serialize(),
            type: "POST",
            beforeSend: function() {
                $('#modalSupplierEdit .modal-footer button').prop('disabled', true);
                $('#modalSupplierEdit .modal-footer button:nth-child(1)').html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#modalSupplierEdit").modal("hide");
                    $("#formSupplierEdit [name='namasupplier']").val("");
                    $("#formSupplierEdit [name='url']").val("");
                    $("#formSupplierEdit [name='source']").val("");
                    notifikasi(result.message, "success");
                    dataTabel.ajax.reload();
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $('#modalSupplierEdit .modal-footer button').prop('disabled', false);
                $('#modalSupplierEdit .modal-footer button:nth-child(1)').html("Update");
            }
        });
    }

    function supplierDelete(id) {

        $.ajax({
            url: "api/api.php?mod=supplier.delete&id=" + id,
            type: "GET",
            beforeSend: function() {
                $('#act-supplier-' + id + ' .iface-delete').prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i>");
            },
            success: function(result) {
                if (result.status == 1) {
                    notifikasi("Hapus: Data berhasil dihapus");
                    dataTabel.ajax.reload();
                } else {
                    $('#act-supplier-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
                $('#act-supplier-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
            },
            complete: function() {
                $('#act-supplier-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
            }
        });

    }
</script>
<!-- Modals -->

<!-- The Modal -->
<div class="modal" id="modalSupplier">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Supplier</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form onsubmit="simpanSupplier()" id="formSupplier">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="namasupplier" placeholder="Nama Supplier">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Url</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="url" placeholder="Url Supplier">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Source</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="source" placeholder="Source">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpanSupplier()">Add</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<div class="modal" id="modalSupplierEdit">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Supplier</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div></div>
                <form onsubmit="updateSupplier()" id="formSupplierEdit">
                    <input type='hidden' name='idsupplier' value=''>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="namasupplier" placeholder="Nama Supplier">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Url</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="url" placeholder="Url Supplier">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Source</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="source" placeholder="Source">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateSupplier()">Update</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>