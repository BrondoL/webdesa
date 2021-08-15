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
    <h3>Data Warga di desa Pandan Makmur</h3>
</div>
<div class="page-content">
    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tabel daftar warga</h4>
                    </div>
                    <div class="card-content">
                        <!-- table hover -->
                        <button class="btn btn-sm btn-primary ms-3 mb-3">Tambah Data</button>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAMA DUSUN</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="data-dusun">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include "views/layouts/footer.php"; ?>
<script>
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "<?= $endpoint ?>dusun",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    let data = "";
                    response.data.forEach((val, i) => {
                        data += `<tr>
                                        <td>${i+1}</td>
                                        <td class="text-bold-500">${val.nama_dusun}</td>
                                        <td class="align-middle">
                                            <button class="btn btn-warning btn-sm" onclick="edit(${val.dusun_id})"><i class="fas fa-tags"></i></button>
                                            <button class="btn btn-danger btn-sm" onclick="hapus(${val.dusun_id})"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>`;
                    });
                    $('.data-dusun').html(data);
                }
            }
        });
    });
</script>
</body>

</html>