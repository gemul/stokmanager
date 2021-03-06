<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Master Kategori</h1>
    <button onclick="tambahKategori()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Kategori</button>
</div>

<!-- Content Row -->
<div class="row">

    <!-- DataTales Example -->
    <div class="card shadow mb-4 datatable-card">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Kategori</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th style='width:70px;'></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
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
            "ajax": "api/api.php?mod=kategori.list",
            'columns': [{
                    'orderable': true
                }, //id
                {
                    'orderable': true
                }, //nama
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

    function tambahKategori() {
        $("#modalKategori").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modalKategori").modal("show");
    }

    function simpanKategori(form) {
        var form = $('#formKategori');
        event.preventDefault();
        $.ajax({
            url: "api/api.php?mod=kategori.add",
            data: form.serialize(),
            type: "POST",
            beforeSend: function() {
                $('#modalKategori .modal-footer button').prop('disabled', true);
                $('#modalKategori .modal-footer button:nth-child(1)').html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#modalKategori").modal("hide");
                    $("#formKategori [name='namaetalase']").val("");
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
                $('#modalKategori .modal-footer button').prop('disabled', false);
                $('#modalKategori .modal-footer button:nth-child(1)').html("Add");
            }
        });
    }

    function kategoriEdit(id) {

        $("#modalKategoriEdit").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modalKategoriEdit").modal("show");

        $.ajax({
            url: "api/api.php?mod=kategori.get&id=" + id,
            type: "GET",
            beforeSend: function() {
                $('#modalKategoriEdit .modal-footer button').prop('disabled', true);
                $('#modalKategoriEdit .modal-body div:nth-child(1)').html("<i class='fa fa-spinner fa-spin'></i> Memuat");
                $('#modalKategoriEdit #formKategoriEdit').hide();
            },
            success: function(result) {
                if (result.status == 1) {
                    $('#modalKategoriEdit #formKategoriEdit').show();
                    $('#modalKategoriEdit .modal-footer button').prop('disabled', false);
                    $("#formKategoriEdit [name='idetalase']").val(result.data[0].idetalase);
                    $("#formKategoriEdit [name='namaetalase']").val(result.data[0].namaetalase);
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $('#modalKategoriEdit .modal-body div:nth-child(1)').html("");
            }
        });

    }

    function updateKategori() {
        var form = $('#formKategoriEdit');
        event.preventDefault();
        $.ajax({
            url: "api/api.php?mod=kategori.update",
            data: form.serialize(),
            type: "POST",
            beforeSend: function() {
                $('#modalKategoriEdit .modal-footer button').prop('disabled', true);
                $('#modalKategoriEdit .modal-footer button:nth-child(1)').html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#modalKategoriEdit").modal("hide");
                    $("#formKategoriEdit [name='namaetalase']").val("");
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
                $('#modalKategoriEdit .modal-footer button').prop('disabled', false);
                $('#modalKategoriEdit .modal-footer button:nth-child(1)').html("Update");
            }
        });
    }

    function kategoriDelete(id) {

        $.ajax({
            url: "api/api.php?mod=kategori.delete&id=" + id,
            type: "GET",
            beforeSend: function() {
                $('#act-kategori-' + id + ' .iface-delete').prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i>");
            },
            success: function(result) {
                if (result.status == 1) {
                    notifikasi("Hapus: Data berhasil dihapus");
                    dataTabel.ajax.reload();
                } else {
                    $('#act-kategori-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
                $('#act-kategori-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
            },
            complete: function() {
                $('#act-kategori-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
            }
        });

    }
</script>
<!-- Modals -->

<!-- The Modal -->
<div class="modal" id="modalKategori">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Kategori</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form onsubmit="simpanKategori()" id="formKategori">
                    <div class="form-group row">
                        <label for="inputNama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="namaetalase" placeholder="Nama Kategori">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpanKategori()">Add</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<div class="modal" id="modalKategoriEdit">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Kategori</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div></div>
                <form onsubmit="updateKategori()" id="formKategoriEdit">
                    <input type='hidden' name='idetalase' value=''>
                    <div class="form-group row">
                        <label for="inputNama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="namaetalase" placeholder="Nama Kategori">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateKategori()">Update</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>