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
    <h3>Dashboard</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <h1 class="text-center">Selamat datang di dashboard admin desa</h1>
                <h1 class="text-center mb-5">Pandan Makmur !</h1>
            </div>
            <div class="row">
                <div class="col-6 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="bi bi-house-door-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Dusun</h6>
                                    <h6 class="font-extrabold mb-0 totaldusun"></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Warga</h6>
                                    <h6 class="font-extrabold mb-0 totalwarga"></h6>
                                </div>
                            </div>
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
            url: "<?= $endpoint ?>dusun/total",
            dataType: "json",
            success: function(response) {
                $('.totaldusun').html(response.data);
            }
        });
        $.ajax({
            url: "<?= $endpoint ?>warga/total",
            dataType: "json",
            success: function(response) {
                $('.totalwarga').html(response.data);
            }
        });
    });
</script>
</body>

</html>