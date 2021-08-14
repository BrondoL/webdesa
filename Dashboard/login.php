<?php require "utils/api.php"; ?>
<?php
session_start();

if (isset($_SESSION["login"])) {
    echo "
				<script>
					document.location.href = 'index.php';
				</script>
			";
}

if (isset($_POST['submit'])) {
    $data = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
    ];
    $make_call = callAPI('POST', 'http://restapi.test/api/auth/login', json_encode($data));
    $response = json_decode($make_call, true);
    if ($response['success']) {
        $_SESSION["login"] = true;
        $_SESSION["username"] = $response['username'];
        $_SESSION["email"] = $response['email'];
        $_SESSION["nama"] = $response['nama'];
        $_SESSION["role_id"] = $response['role_id'];
        echo "
                    <script>
                        alert('Login Success!');
                        document.location.href = 'index.php';
                    </script>
                ";
    } else {
        $error = $response['data'];
    }
}
?>
<?php require "config/config.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard Pandan Makmur</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/mazer/css/bootstrap.css">
    <link rel="stylesheet" href="assets/mazer/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/mazer/css/app.css">
    <link rel="stylesheet" href="assets/mazer/css/pages/auth.css">
</head>

<body>
    <div id="auth">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8 col-12">
                <div id="auth-left">
                    <h1 class="auth-title">Log in.</h1>
                    <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

                    <form action="" method="POST" class="loginbtn">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="username" class="form-control form-control-xl <?= ($error['username']) ? 'is-invalid' : ''; ?>" placeholder="Username">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                <?= $error['username']; ?>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password" class="form-control form-control-xl <?= ($error['password']) ? 'is-invalid' : ''; ?>" placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                <?= $error['password']; ?>
                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-3 btnlogin">Log in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>