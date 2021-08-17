<?php
session_start();
if (!isset($_SESSION["login"])) {
    echo "
                <script>
                    document.location.href = 'login.php';
                </script>
            ";
}
?>

<?php require "config/config.php"; ?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/layouts/sidebar.php"; ?>
<div class="page-heading">
    <h3>Data Dusun di desa Pandan Makmur</h3>
</div>
<div class="page-content">
    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tabel data dusun</h4>
                    </div>
                    <div class="card-content">
                        <!-- table hover -->
                        <button class="btn btn-sm btn-primary ms-3 mb-3" data-bs-toggle="modal" data-bs-target="#inlineForm">Tambah Data</button>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="data-table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAMA DUSUN</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="data-dusun">
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <strong>Belum ada data</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="myModalLabel33">Form Tambah Data</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="<?= $endpoint; ?>dusun" class="formtambah" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="api-key" value="<?= $api_key; ?>">
                    <label>Nama: </label>
                    <div class="form-group">
                        <input type="text" placeholder="Nama Dusun..." class="form-control" id="nama_dusun" name="nama_dusun">
                        <div id="errorNama" class="invalid-feedback">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-window-close"></i> Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1 btnsimpan">
                        <i class="fa fa-share-square"></i> Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="editdata" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="editdata">Form Edit Data</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="" class="formedit" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="api-key" value="<?= $api_key; ?>">
                    <input type="hidden" id="editid">
                    <label>Nama: </label>
                    <div class="form-group">
                        <input type="text" placeholder="Nama Dusun..." class="form-control" id="editnama_dusun" name="nama_dusun">
                        <div id="editerrorNama" class="invalid-feedback">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-window-close"></i> Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1 btnedit">
                        <i class="fa fa-share-square"></i> Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "views/layouts/footer.php"; ?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function getData() {
        $.ajax({
            type: "GET",
            url: "<?= $endpoint ?>dusun",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    let data = "";
                    if (response.data.length != 0) {
                        response.data.forEach((val, i) => {
                            data += `<tr class="text-dark">
                                            <td>${i+1}</td>
                                            <td class="text-bold-500">${val.nama_dusun}</td>
                                            <td class="align-middle">
                                                <button class="btn btn-warning btn-sm" onclick="show(${val.dusun_id})" data-bs-toggle="modal" data-bs-target="#modaledit"><i class="fas fa-tags"></i></button>
                                                <button class="btn btn-danger btn-sm" onclick="hapus(${val.dusun_id})"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>`;
                        });
                        $('.data-dusun').html(data);
                    }
                }
            }
        });
    }

    function show(id) {
        $.ajax({
            type: "GET",
            url: "<?= $endpoint ?>" + "dusun/" + id,
            success: function(response) {
                if (response.success) {
                    $('input#editnama_dusun').val(response.data.nama_dusun);
                    $('#editid').val(id);
                }
            }
        });
    }

    function hapus(id) {
        Swal.fire({
            title: 'Hapus data?',
            text: `Apakah anda ingin menghapus data ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "<?= $endpoint ?>" + "dusun/" + id + '?api-key=<?= $api_key; ?>',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: response.sukses,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            getData();
                        }
                    }
                });
            }
        })
    }

    $(document).ready(function() {
        getData();

        $('.formtambah').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btnsimpan').attr('disable', 'disable');
                    $('.btnsimpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
                },
                complete: function() {
                    $('.btnsimpan').removeAttr('disable', 'disable');
                    $('.btnsimpan').html('<i class="fa fa-share-square"></i>  Save');
                },
                success: function(response) {
                    if (response.success) {
                        $('#nama_dusun').val('');
                        $('#nama_dusun').removeClass('is-invalid');
                        $('.errorNama').html('');
                        getData();
                        $('#inlineForm').modal('hide');
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.sukses,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        if (response.data.nama_dusun) {
                            $('#nama_dusun').addClass('is-invalid');
                            $('#errorNama').html(response.data.nama_dusun[0]);
                        } else {
                            $('#nama_dusun').removeClass('is-invalid');
                            $('.errorNama').html('');
                        }
                    }
                }
            });
        });

        $('.formedit').submit(function(e) {
            let id = $('#editid').val();
            e.preventDefault();
            $.ajax({
                type: "PUT",
                url: "<?= $endpoint ?>" + "dusun/" + id,
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btnedit').attr('disable', 'disable');
                    $('.btnedit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
                },
                complete: function() {
                    $('.btnedit').removeAttr('disable', 'disable');
                    $('.btnedit').html('<i class="fa fa-share-square"></i>  Update');
                },
                success: function(response) {
                    if (response.success) {
                        getData();
                        $('#modaledit').modal('hide');
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.sukses,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        if (response.data.nama_dusun) {
                            $('#editnama_dusun').addClass('is-invalid');
                            $('#editerrorNama').html(response.data.nama_dusun[0]);
                        } else {
                            $('#editnama_dusun').removeClass('is-invalid');
                            $('.editerrorNama').html('');
                        }
                    }
                }
            });
        });
    });
</script>
</body>

</html>