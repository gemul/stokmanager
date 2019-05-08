<!-- Page Heading -->
<div class="d-sm-flex row mb-4">
    <div class='col-md-6'>
        <h1 class="h3 mb-0 text-gray-800">Transaksi</h1>
    </div>
    <div class="input-group mb-3 col-md-6">
        <select class="form-control float-right" id="jenisTransaksi">
            <option value=1>Transaksi Masuk</option>
            <option value=2>Transaksi Keluar</option>
        </select>
        <div class="input-group-append">
            <!-- <button class="btn btn-outline-secondary" type="button">Button</button> -->
            <button onclick="bungkus()" class="float-right d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fas fa-check fa-sm text-white-50"></i> Selesaikan Transaksi</button>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class='col-md-4'>
        <div class="card shadow mb-4 datatable-card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Barang</h6>
            </div>
            <div class="card-body">
                <form onsubmit="addBarang()" id="formBarang">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Barang</label>
                        <div class="col-sm-8">
                            <select class="form-control select2-idbarang" name="idbarang" placeholder="Barang" style="width:100%"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Volume</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="volume" placeholder="Volume">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Harga</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="harga" placeholder="Harga">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8">
                            <button class='btn btn-primary iface-additem' onclick="addBarang()"><i class='fa fa-plus'></i> Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class='col-md-8'>
        <div class='row'>
            <div class='col-md-12'>
                <div class="card shadow mb-4 datatable-card">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Keranjang <a onclick="listCart()">Refresh</a></h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm" id="tabelCart" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th style='width:110px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total</th>
                                        <th id="totalCart">Total</th>
                                        <th style='width:110px;'>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col -->
        </div>
        <!-- row -->
    </div>

</div>
<!-- Content Row -->
<script>
    $(document).ready(function() {
        $('#formBarang [name=harga]').maskNumber({
            thousands: '.',
            integer: true
        });
        $('.select2-idbarang').select2({
            ajax: {
                delay: 250, // wait 250 milliseconds before triggering the request
                quietMillis: 200,
                url: "api/api.php?mod=order.select2-barang",
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
        listCart();
        $("#modalTransaksiMasuk").modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $("#modalTransaksiKeluar").modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    }); //document ready

    function addBarang() {
        event.preventDefault();
        var form = $('#formBarang');
        $.ajax({
            url: "api/api.php?mod=order.cartitem&cart=add",
            data: form.serialize(),
            type: "POST",
            beforeSend: function() {
                $('#formBarang button.iface-additem').prop('disabled', true).html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#formBarang [name='idbarang']").val("").html("");
                    $("#formBarang [name='volume']").val("");
                    $("#formBarang [name='harga']").val("");
                    notifikasi(result.message, "success");
                    listCart();
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $('#formBarang button.iface-additem').prop('disabled', false).html("<i class='fa fa-plus'></i> Add");
            }
        });

    }

    function removeBarang(id) {
        $.ajax({
            url: "api/api.php?mod=order.cartitem&cart=remove&index=" + id,
            type: "POST",
            beforeSend: function() {
                $('.cartitem[cid=' + id + '] .iface-remove').prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i>");
            },
            success: function(result) {
                if (result.status == 1) {
                    notifikasi(result.message, "success");
                    listCart();
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $('.cartitem[cid=' + id + '] .iface-remove').prop('disabled', true).html("<i class='fa fa-trash'></i>");
            }
        });

    }

    function clearBarang() {
        $.ajax({
            url: "api/api.php?mod=order.cartitem&cart=remove&index=all",
            type: "POST",
            beforeSend: function() {
                $('#tabelCart tbody').html("<td colspan='5'>Mengosongkan keranjang...</td>");
            },
            success: function(result) {
                if (result.status == 1) {
                    notifikasi(result.message, "success");
                    $('#tabelCart tbody').html("<td colspan='5'>Tidak ada data</td>");
                } else {
                    listCart();
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
                listCart();
            },
            complete: function() {}
        });

    }

    function listCart() {
        $.ajax({
            url: "api/api.php?mod=order.cartitem&cart=list",
            type: "GET",
            beforeSend: function() {
                $('#tabelCart tbody').html("<td colspan='5'>Loading...</td>");
            },
            success: function(result) {
                if (result.status == 1) {
                    var text = "";
                    var total = 0;
                    for (x in result.data) {
                        var subtotal = result.data[x].volume * result.data[x].harga;
                        var total = total + parseInt(subtotal);
                        text = text + "\
                        <tr class='cartitem' cid='" + x + "'> \
                            <td>" + result.data[x].namabarang + "</td> \
                            <td>" + result.data[x].volume + "</td> \
                            <td>" + result.data[x].harga + "</td> \
                            <td>" + subtotal + "</td> \
                            <td style='width:110px;'> \
                                <a class='iface-remove' onclick='removeBarang(" + x + ")' href='#'><i class='fa fa-trash'></i></a> \
                            </td> \
                        </tr>";
                    }
                    if (text == "") {
                        $('#tabelCart tbody').html("<td colspan='5'>Tidak ada data</td>");
                    } else {
                        $('#tabelCart tbody').html(text);
                    }
                    $('#tabelCart #totalCart').html(total);
                } else {
                    notifikasi("Error:" + result.message, "danger");
                    $('#tabelCart tbody').html("<td colspan='5'>Error loading table...</td>");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
                $('#tabelCart tbody').html("<td colspan='5'>Error loading table...</td>");
            },
            complete: function() {}
        });

    }

    function bungkus() {
        if ($('#jenisTransaksi').val() == "1") {
            //transaksi masuk
            $("#modalTransaksiMasuk").modal("show");
        } else {
            //transaksi keluar
            $("#modalTransaksiKeluar").modal("show");
        }
    }

    function simpanTransaksi(jenis) {
        event.preventDefault();
        var form = $('#formTransaksi' + jenis).serializeArray();
        form.push({'name':'jenis','value':$("#jenisTransaksi").val()})
        $.ajax({
            url: "api/api.php?mod=order.cartitem&cart=save",
            data: form,
            type: "POST",
            beforeSend: function() {
                $("#formTransaksi" + jenis + " button.iface-simpantransaksi").prop('disabled', true).html("Saving");
            },
            success: function(result) {
                if (result.status == 1) {
                    $("#jenisTransaksi").val("");
                    $("#formTransaksi" + jenis + " [name='subject']").val("");
                    $("#formTransaksi" + jenis + " [name='note']").val("");
                    notifikasi(result.message, "success");
                    listCart();
                } else {
                    notifikasi("Error:" + result.message, "danger");
                }
            },
            error: function() {
                notifikasi("Error: ajax error", "danger");
            },
            complete: function() {
                $("#formTransaksi" + jenis + " button.iface-simpantransaksi").prop('disabled', false).html("<i class='fa fa-plus'></i> Add");
            }
        });
    }
</script>

<!-- modals -->

<div class="modal" id="modalTransaksiMasuk">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Transaksi Masuk</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div></div>
                <form onsubmit="simpanTransaksi('Masuk')" id="formTransaksiMasuk">
                    <input type='hidden' name='jenistransaksi' value='masuk'>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Subject</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="subject" placeholder="Subject">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Note</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="note" placeholder="Note">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary iface-simpantransaksi" onclick="simpanTransaksi('Masuk')">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<div class="modal" id="modalTransaksiKeluar">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Transaksi Keluar</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div></div>
                <form onsubmit="simpanTransaksi('Keluar')" id="formTransaksiKeluar">
                    <input type='hidden' name='jenistransaksi' value='keluar'>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Subject</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="subject" placeholder="Subject">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Note</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="note" placeholder="Note">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary iface-simpantransaksi" onclick="simpanTransaksi('Keluar')">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>