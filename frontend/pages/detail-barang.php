<script>
    var idBarang = <?php echo $_GET['id'] ?>;
</script>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Barang</h1>
    <button onclick="loadPage('frontend/pages/detail-barang.php?id=<?php echo $_GET['id'] ?>')" class="btn btn-sm btn-primary shadow-sm"><i class="fa fa-refresh fa-sm text-white-50"></i> Refresh</button>
    <button onclick="tambahBarang()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang</button>
</div>

<!-- Content Row -->
<div class="row">
    <div class='col-md-6'>
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail</h6>
            </div>
            <div class="card-body">
                <table class="detail-table">
                    <tr>
                        <td>Kategori</td>
                        <td>:</td>
                        <td id=detail-kategori></td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td id=detail-nama></td>
                    </tr>
                    <tr>
                        <td>Kode</td>
                        <td>:</td>
                        <td id=detail-kode></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td id=detail-status></td>
                    </tr>
                    <tr>
                        <td>Catatan</td>
                        <td>:</td>
                        <td>
                            <pre id=detail-catatan></pre>
                        </td>
                    </tr>
                    <tr>
                        <td>Berat</td>
                        <td>:</td>
                        <td id=detail-berat></td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td>:</td>
                        <td>
                            <pre id=detail-deskripsi></pre>
                        </td>
                    </tr>
                    <tr>
                        <td>Ditambah</td>
                        <td>:</td>
                        <td id=detail-created></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- col md 6 -->
    <div class='col-md-6'>
        <div class="card shadow">
            <div class="card-header py-3 container-fluid">
                <div class='row'>
                    <div class='col-md-6'>
                        <h6 class="m-0 font-weight-bold text-primary">Gambar</h6>
                    </div>
                    <div class='col-md-6'>
                        <button onclick="addImages()" class="btn btn-xs btn-primary float-right"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class='image-slider-frame'>
                    <div class='image-slider' id="image-slider">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- col md 6 -->
</div>
<!-- row -->
<div class="row mt-4">
    <div class='col-md-12'>
        <div class="card shadow mb-4 datatable-card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Stok</h6>
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
                                <th style='width:70px;'></th>
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
</div>
<!-- Content Row -->
<script>
</script>
<!-- Modals -->

<!-- The Modal -->
<div class="modal" id="modalImages">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Images</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form onsubmit="simpanImages()" id="formImages" enctype="multipart/form-data">
                    <input type='hidden' name="idbarang" value="">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">File</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="file" placeholder="file" style="width:100%">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Label</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="label" placeholder="Label">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpanImages()">Add</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        loadDetail();
        loadImages();
    });

    function loadDetail() {
        $.ajax({
            url: "api/api.php?mod=barang.get&id=" + idBarang,
            type: "GET",
            beforeSend: function() {},
            success: function(result) {
                if (result.status == 1) {
                    $("#detail-kategori").html(result.data[0].namaetalase);
                    $("#detail-nama").html(result.data[0].namabarang);
                    $("#detail-kode").html(result.data[0].kode);
                    $("#detail-status").html(result.data[0].status);
                    $("#detail-catatan").html(result.data[0].catatan);
                    $("#detail-berat").html(result.data[0].berat);
                    $("#detail-deskripsi").html(result.data[0].deskripsi);
                    $("#detail-created").html(result.data[0].created);
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {}
        });

    }

    function loadImages() {
        $.ajax({
            url: "api/api.php?mod=images.list&id=" + idBarang,
            type: "GET",
            beforeSend: function() {},
            success: function(result) {
                if (result.status == 1) {
                    //make the html
                    var imageBoxesHtml = "";
                    for (x in result.data) {
                        var imageBoxesHtml = imageBoxesHtml + " \
                                        <div class='image-box' title='" + result.data[x].label + "'> \
                                            <img src='contents/product/" + result.data[x].filename + "'><br> \
                                            <div class='image-buttons'> \
                                                <button onclick='lihatImages(" + result.data[x].idimages + ")' class='btn btn-primary btn-sm'><i class='fa fa-eye'></i></button> \
                                                <button onclick='downloadImages(" + result.data[x].idimages + ")' class='btn btn-primary btn-sm'><i class='fa fa-download'></i></button> \
                                                <button onclick='hapusImages(" + result.data[x].idimages + ")' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></button> \
                                            </div> \
                                        </div>";
                    }
                    //insert the html
                    $("#image-slider").html(imageBoxesHtml);
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {}
        });

    }

    function addImages() {
        $("#formImages [name='idbarang']").val(idBarang);
        $("#modalImages").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modalImages").modal("show");
    }

    function simpanImages() {
        event.preventDefault();
        var form_data = new FormData($('#formImages')[0]);
        $.ajax({
            url: 'api/api.php?mod=images.add',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'POST',
            beforeSend: function() {
                $('#modalImages .modal-footer button').prop('disabled', true);
                $('#modalImages .modal-footer button:nth-child(1)').html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#modalImages").modal("hide");
                    notifikasi("Gambar berhasil ditambahkan");
                    loadImages();
                } else {
                    notifikasi("Error: " + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $('#modalImages .modal-footer button:nth-child(1)').html("Add");
                $('#modalImages .modal-footer button').prop('disabled', false);
            }
        });
    }

    function hapusImages(id) {
        if (confirm("Hapus?")) {
            $.ajax({
                url: 'api/api.php?mod=images.delete&id=' + id,
                dataType: 'json',
                type: 'POST',
                beforeSend: function() {},
                success: function(result) {
                    if (result.status == 1) {
                        notifikasi("Gambar berhasil dihapus");
                        loadImages();
                    } else {
                        notifikasi("Error: " + result.message, "danger");
                    }
                },
                error: function() {
                    notifikasi("Error: ajax error", "danger");
                },
                complete: function() {}
            });
        }
    }
</script>
<style>
    .detail-table {}

    .detail-table tr td:nth-child(1) {
        font-weight: bold;
    }

    .image-slider-frame {
        width: 100%;
        height: 200px;
        overflow-x: scroll;
        overflow-y: hidden;
    }

    .image-slider {
        white-space: nowrap;
        height: 200px;
    }

    .image-slider .image-box {
        display: inline-block;
        height: 170px;
        border: 1px solid #000;
        position: relative;
    }

    .image-slider .image-box img {
        height: 100%;
    }

    .image-slider .image-box .image-buttons {
        position: absolute;
        bottom: 2px;
        left: 2px;
        display: none;
    }

    .image-slider .image-box:hover>.image-buttons {
        display: block;
    }

    .btn-group-xs>.btn,
    .btn-xs {
        padding: .25rem .4rem;
        font-size: .5rem;
        line-height: .1;
        border-radius: .2rem;
    }
</style>