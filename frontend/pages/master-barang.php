<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Master Barang</h1>
    <button onclick="tambahBarang()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang</button>
</div>

<!-- Content Row -->
<div class="row">

    <!-- DataTales Example -->
    <div class="card shadow mb-4 datatable-card">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style='width:10px;'>ID</th>
                            <th>Kategori</th>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Status</th>
                            <th style='width:110px;'></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Kategori</th>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Status</th>
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
            "ajax": "api/api.php?mod=barang.list",
            'columns': [{
                    'orderable': true
                }, //id
                {
                    'orderable': true
                }, //namakategori
                {
                    'orderable': true
                }, //nama
                {
                    'orderable': true
                }, //kode
                {
                    'orderable': true
                }, //status
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
        $('.select2-idetalase').select2({
            ajax: {
                delay: 250, // wait 250 milliseconds before triggering the request
                quietMillis: 200,
                url: "api/api.php?mod=barang.select2-kategori",
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

    function tambahBarang() {
        $("#modalBarang").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modalBarang").modal("show");
    }

    function simpanBarang(form) {
        var form = $('#formBarang');
        event.preventDefault();
        $.ajax({
            url: "api/api.php?mod=barang.add",
            data: form.serialize(),
            type: "POST",
            beforeSend: function() {
                $('#modalBarang .modal-footer button').prop('disabled', true);
                $('#modalBarang .modal-footer button:nth-child(1)').html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#modalBarang").modal("hide");
                    $("#formBarang [name='idbarang']").val("");
                    $("#formBarang [name='idetalase']").val("");
                    $("#formBarang [name='namabarang']").val("");
                    $("#formBarang [name='kodebarang']").val("");
                    $("#formBarang [name='catatan']").val("");
                    $("#formBarang [name='berat']").val("");
                    $("#formBarang [name='deskripsi']").val("");
                    $("#formBarang [name='statusbarang']").val("");
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
                $('#modalBarang .modal-footer button').prop('disabled', false);
                $('#modalBarang .modal-footer button:nth-child(1)').html("Add");
            }
        });

    }

    function barangEdit(id) {

        $("#modalBarangEdit").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modalBarangEdit").modal("show");

        $.ajax({
            url: "api/api.php?mod=barang.get&id=" + id,
            type: "GET",
            beforeSend: function() {
                $('#modalBarangEdit .modal-footer button').prop('disabled', true);
                $('#modalBarangEdit .modal-body div:nth-child(1)').html("<i class='fa fa-spinner fa-spin'></i> Memuat");
                $('#modalBarangEdit #formBarangEdit').hide();
            },
            success: function(result) {
                if (result.status == 1) {
                    $('#modalBarangEdit #formBarangEdit').show();
                    $('#modalBarangEdit .modal-footer button').prop('disabled', false);
                    $("#formBarangEdit [name='idbarang']").val(result.data[0].idbarang);
                    $("#formBarangEdit [name='idetalase']").html("<option value='" + result.data[0].idetalase + "'>" + result.data[0].namaetalase + "</option>");
                    $("#formBarangEdit [name='idetalase']").val(result.data[0].idetalase);
                    $("#formBarangEdit [name='namabarang']").val(result.data[0].namabarang);
                    $("#formBarangEdit [name='catatan']").val(result.data[0].catatan);
                    $("#formBarangEdit [name='berat']").val(result.data[0].berat);
                    $("#formBarangEdit [name='deskripsi']").val(result.data[0].deskripsi);
                    $("#formBarangEdit [name='kodebarang']").val(result.data[0].kodebarang);
                    $("#formBarangEdit [name='statusbarang']").val(result.data[0].statusbarang);
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $('#modalBarangEdit .modal-body div:nth-child(1)').html("");
            }
        });

    }

    function updateBarang() {
        var form = $('#formBarangEdit');
        event.preventDefault();
        $.ajax({
            url: "api/api.php?mod=barang.update",
            data: form.serialize(),
            type: "POST",
            beforeSend: function() {
                $('#modalBarangEdit .modal-footer button').prop('disabled', true);
                $('#modalBarangEdit .modal-footer button:nth-child(1)').html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#modalBarangEdit").modal("hide");
                    $("#formBarangEdit [name='idbarang']").val("");
                    $("#formBarangEdit [name='idetalase']").val("");
                    $("#formBarangEdit [name='namabarang']").val("");
                    $("#formBarangEdit [name='kodebarang']").val("");
                    $("#formBarangEdit [name='catatan']").val("");
                    $("#formBarangEdit [name='berat']").val("");
                    $("#formBarangEdit [name='deskripsi']").val("");
                    $("#formBarangEdit [name='statusbarang']").val("");
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
                $('#modalBarangEdit .modal-footer button').prop('disabled', false);
                $('#modalBarangEdit .modal-footer button:nth-child(1)').html("Update");
            }
        });
    }

    function barangDelete(id) {

        $.ajax({
            url: "api/api.php?mod=barang.delete&id=" + id,
            type: "GET",
            beforeSend: function() {
                $('#act-barang-' + id + ' .iface-delete').prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i>");
            },
            success: function(result) {
                if (result.status == 1) {
                    notifikasi("Hapus: Data berhasil dihapus");
                    dataTabel.ajax.reload();
                } else {
                    $('#act-barang-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
                $('#act-barang-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
            },
            complete: function() {
                $('#act-barang-' + id + ' .iface-delete').prop('disabled', false).html("<i class='fa fa-trash'></i>");
            }
        });

    }
</script>
<!-- Modals -->

<!-- The Modal -->
<div class="modal" id="modalBarang">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form onsubmit="simpanBarang()" id="formBarang">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kategori</label>
                        <div class="col-sm-10">
                            <select class="form-control select2-idetalase" name="idetalase" placeholder="Kategori Barang" style="width:100%"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="namabarang" placeholder="Nama Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kodebarang" placeholder="Kode Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Catatan</label>
                        <div class="col-sm-10">
                            <textarea type="text" class="form-control" name="catatan" placeholder="Catatan"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Berat</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="berat" placeholder="Berat Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea type="text" class="form-control" name="deskripsi" placeholder="Catatan"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="statusbarang" placeholder="Status Barang">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpanBarang()">Add</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<div class="modal" id="modalBarangEdit">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div></div>
                <form onsubmit="updateBarang()" id="formBarangEdit">
                    <input type='hidden' name='idbarang' value=''>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kategori</label>
                        <div class="col-sm-10">
                            <select class="form-control select2-idetalase" name="idetalase" placeholder="Kategori Barang" style="width:100%"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="namabarang" placeholder="Nama Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kodebarang" placeholder="Kode Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Catatan</label>
                        <div class="col-sm-10">
                            <textarea type="text" class="form-control" name="catatan" placeholder="Catatan"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Berat</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="berat" placeholder="Berat Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea type="text" class="form-control" name="deskripsi" placeholder="Catatan"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="statusbarang" placeholder="Status Barang">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateBarang()">Update</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>