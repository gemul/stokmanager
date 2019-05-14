<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Master Harga</h1>
    <button onclick="tambahHarga()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Harga</button>
</div>

<!-- Content Row -->
<div class="row">

    <!-- DataTales Example -->
    <div class="card shadow mb-4 datatable-card">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Harga</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style='width:10px;'>ID</th>
                            <th>Barang</th>
                            <th>Supplier</th>
                            <th>Harga</th>
                            <th style='width:110px;'></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Barang</th>
                            <th>Supplier</th>
                            <th>Harga</th>
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
                $(this).html('<input type="text" style="width:100%" placeholder="' + title + '" />');
            }
        });
        dataTabel = $('#dataTable').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "sDom": "lrtip",
            "ajax": "api/api.php?mod=harga.list",
            'columns': [{
                    'orderable': true
                }, //id
                {
                    'orderable': true
                }, //barang
                {
                    'orderable': true
                }, //supplier
                {
                    'orderable': true
                }, //harga
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
        $('.select2-idbarang').select2({
            ajax: {
                delay: 250, // wait 250 milliseconds before triggering the request
                quietMillis: 200,
                url: "api/api.php?mod=harga.select2-barang",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                },
                cache: true
            },
            language: {
                noResults: function(params) {
                    return "Tidak ditemukan.";
                }
            }
        });
        $('.select2-idsupplier').select2({
            ajax: {
                delay: 250, // wait 250 milliseconds before triggering the request
                quietMillis: 200,
                url: "api/api.php?mod=harga.select2-supplier",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                },
                cache: true
            },
            language: {
                noResults: function(params) {
                    return "Tidak ditemukan.";
                }
            }
        });
    });

    function tambahHarga() {
        $("#modalHarga").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modalHarga").modal("show");
    }

    function simpanHarga(form) {
        var form = $('#formHarga');
        event.preventDefault();
        $.ajax({
            url: "api/api.php?mod=harga.add",
            data: form.serialize(),
            type: "POST",
            beforeSend: function() {
                $('#modalHarga .modal-footer button').prop('disabled', true);
                $('#modalHarga .modal-footer button:nth-child(1)').html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#modalHarga").modal("hide");
                    $("#formHarga [name='idharga']").val("");
                    $("#formHarga [name='idbarang']").val("");
                    $("#formHarga [name='idsupplier']").val("");
                    $("#formHarga [name='nominalharga']").val("");
                    $("#formHarga [name='urlharga']").val("");
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
                $('#modalHarga .modal-footer button').prop('disabled', false);
                $('#modalHarga .modal-footer button:nth-child(1)').html("Add");
            }
        });

    }

    function hargaEdit(id) {

        $("#modalHargaEdit").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modalHargaEdit").modal("show");

        $.ajax({
            url: "api/api.php?mod=harga.get&id=" + id,
            type: "GET",
            beforeSend: function() {
                $('#modalHargaEdit .modal-footer button').prop('disabled', true);
                $('#modalHargaEdit .modal-body div:nth-child(1)').html("<i class='fa fa-spinner fa-spin'></i> Memuat");
                $('#modalHargaEdit #formHargaEdit').hide();
            },
            success: function(result) {
                if (result.status == 1) {
                    $('#modalHargaEdit #formHargaEdit').show();
                    $('#modalHargaEdit .modal-footer button').prop('disabled', false);
                    $("#formHargaEdit [name='idharga']").val(result.data[0].idharga);
                    $("#formHargaEdit [name='idbarang']").html("<option value='" + result.data[0].idbarang + "'>" + result.data[0].namabarang + "</option>");
                    $("#formHargaEdit [name='idbarang']").val(result.data[0].idbarang);
                    $("#formHargaEdit [name='idsupplier']").html("<option value='" + result.data[0].idsupplier + "'>" + result.data[0].namasupplier + "</option>");
                    $("#formHargaEdit [name='idsupplier']").val(result.data[0].idsupplier);
                    $("#formHargaEdit [name='nominalharga']").val(result.data[0].nominalharga);
                    $("#formHargaEdit [name='urlharga']").val(result.data[0].urlharga);
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $('#modalHargaEdit .modal-body div:nth-child(1)').html("");
            }
        });

    }

    function updateHarga() {
        var form = $('#formHargaEdit');
        event.preventDefault();
        $.ajax({
            url: "api/api.php?mod=harga.update",
            data: form.serialize(),
            type: "POST",
            beforeSend: function() {
                $('#modalHargaEdit .modal-footer button').prop('disabled', true);
                $('#modalHargaEdit .modal-footer button:nth-child(1)').html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#modalHargaEdit").modal("hide");
                    $("#formHargaEdit [name='idharga']").val("");
                    $("#formHargaEdit [name='idbarang']").val("");
                    $("#formHargaEdit [name='idsupplier']").val("");
                    $("#formHargaEdit [name='nominalharga']").val("");
                    $("#formHargaEdit [name='urlharga']").val("");
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
                $('#modalHargaEdit .modal-footer button').prop('disabled', false);
                $('#modalHargaEdit .modal-footer button:nth-child(1)').html("Update");
            }
        });
    }

    function hargaDelete(id) {

        $.ajax({
            url: "api/api.php?mod=harga.delete&id=" + id,
            type: "GET",
            beforeSend: function() {
                $('#act-harga-' + id + ' .iface-delete').prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i>");
            },
            success: function(result) {
                if (result.status == 1) {
                    notifikasi("Hapus: Data berhasil dihapus");
                    dataTabel.ajax.reload();
                } else {
                    $('#act-harga-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
                $('#act-harga-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
            },
            complete: function() {
                $('#act-harga-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
            }
        });

    }
</script>
<!-- Modals -->

<!-- The Modal -->
<div class="modal" id="modalHarga">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Harga</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form onsubmit="simpanHarga()" id="formHarga">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Barang</label>
                        <div class="col-sm-10">
                            <select class="form-control select2-idbarang" name="idbarang" placeholder="Barang" style="width:100%"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Supplier</label>
                        <div class="col-sm-10">
                            <select class="form-control select2-idsupplier" name="idsupplier" placeholder="Supplier" style="width:100%"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nominalharga" placeholder="Harga">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Url</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="urlharga" placeholder="Url">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpanHarga()">Add</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<div class="modal" id="modalHargaEdit">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Harga</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div></div>
                <form onsubmit="updateHarga()" id="formHargaEdit">
                    <input type='hidden' name='idharga' value=''>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Barang</label>
                        <div class="col-sm-10">
                            <select class="form-control select2-idbarang" name="idbarang" placeholder="Barang" style="width:100%"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Supplier</label>
                        <div class="col-sm-10">
                            <select class="form-control select2-idsupplier" name="idsupplier" placeholder="Supplier" style="width:100%"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nominalharga" placeholder="Harga">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Url</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="urlharga" placeholder="Url">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateHarga()">Update</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>