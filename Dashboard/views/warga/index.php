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
<?php require "utils/api.php"; ?>
<?php
$get_data = callAPI('GET', $endpoint . "warga?api-key=" . $api_key, false);
$response = json_decode($get_data, true);
$data = $response['data'];
// var_dump($get_data);
// die;

?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/layouts/sidebar.php"; ?>
<div class="page-heading">
    <h3>Data Warga di desa Pandan Makmur</h3>
</div>
<div class="page-content">
    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tabel data warga</h4>
                    </div>
                    <div class="card-content">
                        <!-- table hover -->
                        <button class="btn btn-sm btn-primary ms-3 mb-3" data-bs-toggle="modal" data-bs-target="#inlineForm" onclick="getDusun()">Tambah Data</button>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="table1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>No KTP</th>
                                        <th>Dusun</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="data-dusun">
                                    <?php if (count($data) == 0) : ?>
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <strong>Belum ada data</strong>
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($data as $i => $val) : ?>
                                            <tr class="text-dark">
                                                <td><?= $i + 1; ?></td>
                                                <td class="text-bold-500"><?= $val['nama_warga']; ?></td>
                                                <td class="text-bold-500"><?= $val['jenis_kelamin']; ?></td>
                                                <td class="text-bold-500"><?= $val['no_ktp']; ?></td>
                                                <td class="text-bold-500"><?= $val['nama_dusun']; ?></td>
                                                <td class="align-middle">
                                                    <button class="btn btn-success btn-sm" onclick="detail(<?= $val['warga_id']; ?>)" data-bs-toggle="modal" data-bs-target="#modaldetail"><i class="fa fa-eye"></i></button>
                                                    <button class="btn btn-warning btn-sm" onclick="show(<?= $val['warga_id']; ?>)" data-bs-toggle="modal" data-bs-target="#modaledit"><i class="fas fa-tags"></i></button>
                                                    <button class="btn btn-danger btn-sm" onclick="hapus(<?= $val['warga_id']; ?>)"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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
    <div class="modal-xl modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="myModalLabel33">Form Tambah Data</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="<?= $endpoint; ?>warga" class="formtambah" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <input type="hidden" name="api-key" value="<?= $api_key; ?>">
                            <label>Nama: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Nama lengkap..." class="form-control" id="nama_warga" name="nama_warga">
                                <div id="errorNama" class="invalid-feedback">
                                </div>
                            </div>
                            <label>Dusun: </label>
                            <div class="form-group">
                                <select class="form-select form-control selectdusun" aria-label="Default select example" name="dusun_id">
                                </select>
                                <div id="errorDusun" class="invalid-feedback">
                                </div>
                            </div>
                            <label>Jenis Kelamin: </label>
                            <div class="form-group">
                                <select class="form-select form-control" aria-label="Default select example" name="jenis_kelamin">
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <div id="errorJenis_kelamin" class="invalid-feedback">
                                </div>
                            </div>
                            <label>Tempat Lahir: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Tempat Lahir..." class="form-control" id="tempat_lahir" name="tempat_lahir">
                                <div id="errorTempatLahir" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Tanggal Lahir: </label>
                            <div class="form-group">
                                <input type="date" placeholder="Tanggal Lahir..." class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                                <div id="errorTanggalLahir" class="invalid-feedback">
                                </div>
                            </div>
                            <label>Pekerjaan: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Pekerjaan..." class="form-control" id="pekerjaan" name="pekerjaan">
                                <div id="errorPekerjaan" class="invalid-feedback">
                                </div>
                            </div>
                            <label>Pendidikan: </label>
                            <div class="form-group">
                                <select class="form-select form-control" aria-label="Default select example" name="pendidikan">
                                    <option value="Tidak/Belum Sekolah">Tidak/Belum Sekolah</option>
                                    <option value="Tidak Tamat SD/Sederajat">Tidak Tamat SD/Sederajat</option>
                                    <option value="Tamat SD/Sederajat">Tamat SD/Sederajat</option>
                                    <option value="SLTP/Sederajat">SLTP/Sederajat</option>
                                    <option value="SLTA/Sederajat">SLTA/Sederajat</option>
                                    <option value="Diploma I/II">Diploma I/II</option>
                                    <option value="Akademi/Diploma III/Sarjana Muda">Akademi/Diploma III/Sarjana Muda</option>
                                    <option value="Diploma IV/Strata I">Diploma IV/Strata I</option>
                                    <option value="IX : Strata II">IX : Strata II</option>
                                    <option value="X : Strata III">X : Strata III</option>
                                </select>
                                <div id="errorPendidikan" class="invalid-feedback">
                                </div>
                            </div>
                            <label>Agama: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Agama..." class="form-control" id="agama" name="agama">
                                <div id="errorAgama" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Status Perkawinan: </label>
                            <div class="form-group">
                                <select class="form-select form-control" aria-label="Default select example" name="status_perkawinan">
                                    <option value="Belum Kawin">Belum Kawin</option>
                                    <option value="Kawin">Kawin</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                                <div id="errorNama" class="invalid-feedback">
                                </div>
                            </div>
                            <label>No KTP: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Nomor KTP..." class="form-control" id="no_ktp" name="no_ktp">
                                <div id="errorNo_ktp" class="invalid-feedback">
                                </div>
                            </div>
                            <label>No KK: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Nomor KK..." class="form-control" id="no_kk" name="no_kk">
                                <div id="errorNo_kk" class="invalid-feedback">
                                </div>
                            </div>
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
    <div class="modal-xl modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="editdata">Form Edit Data</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="" class="formedit" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <input type="hidden" name="api-key" value="<?= $api_key; ?>">
                            <input type="hidden" name="id" id="idedit">
                            <label>Nama: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Nama lengkap..." class="form-control" id="nama_wargaedit" name="nama_warga">
                                <div id="errorNamaedit" class="invalid-feedback">
                                </div>
                            </div>
                            <label>Dusun: </label>
                            <div class="form-group">
                                <select class="form-select form-control selectdusun" aria-label="Default select example" name="dusun_id">
                                </select>
                            </div>
                            <label>Jenis Kelamin: </label>
                            <div class="form-group">
                                <select class="form-select form-control" aria-label="Default select example" id="jenis_kelaminedit" name="jenis_kelamin">
                                </select>
                            </div>
                            <label>Tempat Lahir: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Tempat Lahir..." class="form-control" id="tempat_lahiredit" name="tempat_lahir">
                                <div id="errorTempatLahiredit" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Tanggal Lahir: </label>
                            <div class="form-group">
                                <input type="date" placeholder="Tanggal Lahir..." class="form-control" id="tanggal_lahiredit" name="tanggal_lahir">
                                <div id="errorTanggalLahiredit" class="invalid-feedback">
                                </div>
                            </div>
                            <label>Pekerjaan: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Pekerjaan..." class="form-control" id="pekerjaanedit" name="pekerjaan">
                                <div id="errorPekerjaanedit" class="invalid-feedback">
                                </div>
                            </div>
                            <label>Pendidikan: </label>
                            <div class="form-group">
                                <select class="form-select form-control" aria-label="Default select example" id="pendidikanedit" name="pendidikan">
                                </select>
                            </div>
                            <label>Agama: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Agama..." class="form-control" id="agamaedit" name="agama">
                                <div id="errorAgamaedit" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Status Perkawinan: </label>
                            <div class="form-group">
                                <select class="form-select form-control" aria-label="Default select example" id="status_perkawinanedit" name="status_perkawinan">
                                </select>
                            </div>
                            <label>No KTP: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Nomor KTP..." class="form-control" id="no_ktpedit" name="no_ktp">
                                <div id="errorNo_ktpedit" class="invalid-feedback">
                                </div>
                            </div>
                            <label>No KK: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Nomor KK..." class="form-control" id="no_kkedit" name="no_kk">
                                <div id="errorNo_kkedit" class="invalid-feedback">
                                </div>
                            </div>
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

<div class="modal fade text-left" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-xl modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="myModalLabel33">Form Tambah Data</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="" class="formdetail" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <label>Nama: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="nama_wargadetail" name="nama_warga" disabled>
                            </div>
                            <label>Dusun: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="nama_dusundetail" name="nama_dusundetail" disabled>
                            </div>
                            <label>Jenis Kelamin: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="jenis_kelamindetail" name="jenis_kelamindetail" disabled>
                            </div>
                            <label>Tempat Lahir: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="tempat_lahirdetail" name="tempat_lahirdetail" disabled>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Tanggal Lahir: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="tanggal_lahirdetail" name="tanggal_lahirdetail" disabled>
                            </div>
                            <label>Pekerjaan: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="pekerjaandetail" name="pekerjaandetail" disabled>
                            </div>
                            <label>Pendidikan: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="pendidikandetail" name="pendidikandetail" disabled>
                            </div>
                            <label>Agama: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="agamadetail" name="agamadetail" disabled>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Status Perkawinan: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="status_perkawinandetail" name="status_perkawinandetail" disabled>
                            </div>
                            <label>No KTP: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="no_ktpdetail" name="no_ktpdetail" disabled>
                            </div>
                            <label>No KK: </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="no_kkdetail" name="no_kkdetail" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-window-close"></i> Close</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "views/layouts/footer.php"; ?>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script>
    function getDusun(warga = '') {
        $.ajax({
            type: "GET",
            url: "<?= $endpoint ?>dusun/",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    if (warga == '') {
                        let data = "";
                        if (response.data.length != 0) {
                            response.data.forEach((val, i) => {
                                data += `<option value="${val.dusun_id}">${val.nama_dusun}</option>`;
                            });
                            $('.selectdusun').html(data);
                        }
                    } else {
                        let data = "";
                        if (response.data.length != 0) {
                            response.data.forEach((val, i) => {
                                data += `<option value="${val.dusun_id}" ${val.dusun_id == warga ? 'selected' : ''}>${val.nama_dusun}</option>`;
                            });
                            $('.selectdusun').html(data);
                        }
                    }
                }
            }
        });
    }

    function show(id) {
        $.ajax({
            type: "GET",
            url: "<?= $endpoint ?>" + "warga/" + id + '?api-key=<?= $api_key; ?>',
            success: async function(response) {
                if (response.success) {
                    getDusun(response.data.dusun_id);
                    $('#optiondusun' + response.data.dusun_id).attr("selected", "selected");
                    $('input#idedit').val(response.data.warga_id);
                    $('input#nama_wargaedit').val(response.data.nama_warga);
                    $('input#nama_dusunedit').val(response.data.nama_dusun);
                    let jenis_kelamin = `
                        <option value="Laki-Laki" ${response.data.jenis_kelamin == 'Laki-Laki' ? 'selected' : ''}>Laki-Laki</option>
                        <option value="Perempuan" ${(response.data.jenis_kelamin == 'Perempuan') ? 'selected' : ''}>Perempuan</option>
                    `;
                    $('select#jenis_kelaminedit').html(jenis_kelamin);
                    $('input#tempat_lahiredit').val(response.data.tempat_lahir);
                    $('input#tanggal_lahiredit').val(response.data.tanggal_lahir);
                    $('input#pekerjaanedit').val(response.data.pekerjaan);
                    let pendidikan = `
                        <option value="Tidak/Belum Sekolah" ${response.data.pendidikan == 'Tidak/Belum Sekolah' ? 'selected' : ''}>Tidak/Belum Sekolah</option>
                        <option value="Tidak Tamat SD/Sederajat" ${response.data.pendidikan == 'Tidak Tamat SD/Sederajat' ? 'selected' : ''}>Tidak Tamat SD/Sederajat</option>
                        <option value="Tamat SD/Sederajat" ${response.data.pendidikan == 'Tamat SD/Sederajat' ? 'selected' : ''}>Tamat SD/Sederajat</option>
                        <option value="SLTP/Sederajat" ${response.data.pendidikan == 'SLTP/Sederajat' ? 'selected' : ''}>SLTP/Sederajat</option>
                        <option value="SLTA/Sederajat" ${response.data.pendidikan == 'SLTA/Sederajat' ? 'selected' : ''}>SLTA/Sederajat</option>
                        <option value="Diploma I/II" ${response.data.pendidikan == 'Diploma I/II' ? 'selected' : ''}>Diploma I/II</option>
                        <option value="Akademi/Diploma III/Sarjana Muda" ${response.data.pendidikan == 'Akademi/Diploma III/Sarjana Muda' ? 'selected' : ''}>Akademi/Diploma III/Sarjana Muda</option>
                        <option value="Diploma IV/Strata I" ${response.data.pendidikan == 'Diploma IV/Strata I' ? 'selected' : ''}>Diploma IV/Strata I</option>
                        <option value="IX : Strata II" ${response.data.pendidikan == 'IX : Strata II' ? 'selected' : ''}>IX : Strata II</option>
                        <option value="X : Strata III" ${response.data.pendidikan == 'X : Strata III' ? 'selected' : ''}>X : Strata III</option>
                    `;
                    $('select#pendidikanedit').html(pendidikan);
                    $('input#agamaedit').val(response.data.agama);
                    let perkawinan = `
                        <option value="Belum Kawin" ${response.data.status_perkawinan == 'Belum Kawin' ? 'selected' : ''}>Belum Kawin</option>
                        <option value="Kawin" ${response.data.status_perkawinan == 'Kawin' ? 'selected' : ''}>Kawin</option>
                        <option value="Cerai Hidup" ${response.data.status_perkawinan == 'Cerai Hidup' ? 'selected' : ''}>Cerai Hidup</option>
                        <option value="Cerai Mati" ${response.data.status_perkawinan == 'Cerai Mati' ? 'selected' : ''}>Cerai Mati</option>
                    `;
                    $('select#status_perkawinanedit').html(perkawinan);
                    $('input#no_ktpedit').val(response.data.no_ktp);
                    $('input#no_kkedit').val(response.data.no_kk);
                }
            }
        });
    }

    function detail(id) {
        $.ajax({
            type: "GET",
            url: "<?= $endpoint ?>" + "warga/" + id + '?api-key=<?= $api_key; ?>',
            success: function(response) {
                if (response.success) {
                    $('input#nama_wargadetail').val(response.data.nama_warga);
                    $('input#nama_dusundetail').val(response.data.nama_dusun);
                    $('input#jenis_kelamindetail').val(response.data.jenis_kelamin);
                    $('input#tempat_lahirdetail').val(response.data.tempat_lahir);
                    $('input#tanggal_lahirdetail').val(response.data.tanggal_lahir);
                    $('input#pekerjaandetail').val(response.data.pekerjaan);
                    $('input#pendidikandetail').val(response.data.pendidikan);
                    $('input#agamadetail').val(response.data.agama);
                    $('input#status_perkawinandetail').val(response.data.status_perkawinan);
                    $('input#no_ktpdetail').val(response.data.no_ktp);
                    $('input#no_kkdetail').val(response.data.no_kk);
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
                    url: "<?= $endpoint ?>" + "warga/" + id + '?api-key=<?= $api_key; ?>',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: response.sukses,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => window.location.href = "/?page=warga");
                        }
                    }
                });
            }
        })
    }

    $(document).ready(function() {
        $('#table1').DataTable({
            "columnDefs": [{
                "targets": [5],
                "orderable": false,
            }],
            // dom: 'Bfrtip',
            // buttons: [
            //     'excel', 'pdf'
            // ]
        });
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
                        $('#inlineForm').modal('hide');
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.sukses,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => window.location.href = "/?page=warga");
                    } else {
                        if (response.data.nama_warga) {
                            $('#nama_warga').addClass('is-invalid');
                            $('#errorNama').html(response.data.nama_warga[0]);
                        } else {
                            $('#nama_warga').removeClass('is-invalid');
                            $('.errorNama').html('');
                        }
                        if (response.data.tempat_lahir) {
                            $('#tempat_lahir').addClass('is-invalid');
                            $('#errorTempatLahir').html(response.data.tempat_lahir[0]);
                        } else {
                            $('#tempat_lahir').removeClass('is-invalid');
                            $('.errorTempatLahir').html('');
                        }
                        if (response.data.tanggal_lahir) {
                            $('#tanggal_lahir').addClass('is-invalid');
                            $('#errorTanggalLahir').html(response.data.tanggal_lahir[0]);
                        } else {
                            $('#tanggal_lahir').removeClass('is-invalid');
                            $('.errorTanggalLahir').html('');
                        }
                        if (response.data.pekerjaan) {
                            $('#pekerjaan').addClass('is-invalid');
                            $('#errorPekerjaan').html(response.data.pekerjaan[0]);
                        } else {
                            $('#pekerjaan').removeClass('is-invalid');
                            $('.errorPekerjaan').html('');
                        }
                        if (response.data.agama) {
                            $('#agama').addClass('is-invalid');
                            $('#errorAgama').html(response.data.agama[0]);
                        } else {
                            $('#agama').removeClass('is-invalid');
                            $('.errorAgama').html('');
                        }
                        if (response.data.no_kk) {
                            $('#no_kk').addClass('is-invalid');
                            $('#errorNo_kk').html(response.data.no_kk[0]);
                        } else {
                            $('#no_kk').removeClass('is-invalid');
                            $('.errorNo_kk').html('');
                        }
                        if (response.data.no_ktp) {
                            $('#no_ktp').addClass('is-invalid');
                            $('#errorNo_ktp').html(response.data.no_ktp[0]);
                        } else {
                            $('#no_ktp').removeClass('is-invalid');
                            $('.errorNo_ktp').html('');
                        }
                    }
                }
            });
        });

        $('.formedit').submit(function(e) {
            let id = $('#idedit').val();
            e.preventDefault();
            $.ajax({
                type: "PUT",
                url: "<?= $endpoint ?>" + "warga/" + id,
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
                    console.log(response)
                    if (response.success) {
                        $('#modaledit').modal('hide');
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.sukses,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => window.location.href = "/?page=warga");
                    } else {
                        if (response.data.nama_warga) {
                            $('#nama_wargaedit').addClass('is-invalid');
                            $('#errorNamaedit').html(response.data.nama_warga[0]);
                        } else {
                            $('#nama_wargaedit').removeClass('is-invalid');
                            $('.errorNamaedit').html('');
                        }
                        if (response.data.tempat_lahir) {
                            $('#tempat_lahiredit').addClass('is-invalid');
                            $('#errorTempatLahiredit').html(response.data.tempat_lahir[0]);
                        } else {
                            $('#tempat_lahiredit').removeClass('is-invalid');
                            $('.errorTempatLahiredit').html('');
                        }
                        if (response.data.tanggal_lahir) {
                            $('#tanggal_lahiredit').addClass('is-invalid');
                            $('#errorTanggalLahiredit').html(response.data.tanggal_lahir[0]);
                        } else {
                            $('#tanggal_lahiredit').removeClass('is-invalid');
                            $('.errorTanggalLahiredit').html('');
                        }
                        if (response.data.pekerjaan) {
                            $('#pekerjaanedit').addClass('is-invalid');
                            $('#errorPekerjaanedit').html(response.data.pekerjaan[0]);
                        } else {
                            $('#pekerjaanedit').removeClass('is-invalid');
                            $('.errorPekerjaanedit').html('');
                        }
                        if (response.data.agama) {
                            $('#agamaedit').addClass('is-invalid');
                            $('#errorAgamaedit').html(response.data.agama[0]);
                        } else {
                            $('#agamaedit').removeClass('is-invalid');
                            $('.errorAgamaedit').html('');
                        }
                        if (response.data.no_kk) {
                            $('#no_kkedit').addClass('is-invalid');
                            $('#errorNo_kkedit').html(response.data.no_kk[0]);
                        } else {
                            $('#no_kkedit').removeClass('is-invalid');
                            $('.errorNo_kkedit').html('');
                        }
                        if (response.data.no_ktp) {
                            $('#no_ktpedit').addClass('is-invalid');
                            $('#errorNo_ktpedit').html(response.data.no_ktp[0]);
                        } else {
                            $('#no_ktpedit').removeClass('is-invalid');
                            $('.errorNo_ktpedit').html('');
                        }
                    }
                }
            });
        });
    });
</script>
</body>

</html>