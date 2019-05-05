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
                        <td id=detail-kategori></td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td id=detail-nama></td>
                    </tr>
                    <tr>
                        <td>Kode</td>
                        <td id=detail-kode></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td id=detail-status></td>
                    </tr>
                    <tr>
                        <td>Ditambah</td>
                        <td id=detail-created></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- col md 6 -->
    <div class='col-md-6'>
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Gambar</h6>
            </div>
            <div class="card-body">
                <div class='image-slider-frame'>
                    <div class='image-slider'>
                        <div class='image-box'>
                            <img src="contents/product/00001.0000001.jpg"><br>
                            <div class='image-buttons'>
                                <button class='btn btn-primary btn-sm'><i class='fa fa-eye'></i></button>
                                <button class='btn btn-primary btn-sm'><i class='fa fa-download'></i></button>
                                <button class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
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

                    .image-slider .image-box:hover > .image-buttons {
                        display:block;
                    }
                </style>
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
                <h6 class="m-0 font-weight-bold text-primary">Harga</h6>
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

<style>
    .detail-table {}

    .detail-table tr td:nth-child(1) {
        font-weight: bold;
    }
</style>