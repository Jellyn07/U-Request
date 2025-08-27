<!DOCTYPE html>
<html lang="en">
<head>
    <title>UTRMS</title>
    <link rel="icon" href="../../assets/icon/logo.png" type="icon">
    <link rel="icon" href="../../assets/icon/logo.png" type="icon">
    <link rel="stylesheet" type="text/css" href="../../css/shared/global.css">
    <link rel="stylesheet" type="text/css" href="../../css/GSUAdmin/login.css">
</head>
<body class="landingPage_body">
    <div class="admin">
    <img src="../../assets/icon/logo.png" alt="GSU Logo" id="main_logo">
    <h1>Motorpool Admin Panel</h1>
    <form method="post" action="login.php">
        <input type="text" name="username" class="username" placeholder="Username" required>
        <input type="password" name="password" class="password" placeholder="Password" required>
        <button class="login" name="login">LOGIN</button>
    </form>
    </div>
    <script src="../../js/global.js"></script>
</body>    
</html>

<!-- $adminUser = "admin_gsu";
     $plainPass = "gsu123admin"; -->

     <?php
        session_start();
        
        define('ENCRYPT_METHOD', 'aes-256-cbc');
        define('SECRET_KEY', '12345678901234567890123456789012');
        define('SECRET_IV', '1234567890123456');

        function encrypt($string) {
            return base64_encode(openssl_encrypt($string, ENCRYPT_METHOD, SECRET_KEY, 0, SECRET_IV));
        }

        if (isset($_POST['login'])) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // $conn = new mysqli("localhost", "root", "", "gsu_system");
            // if ($conn->connect_error) {
            //     die("Connection failed: " . $conn->connect_error);
            // }
            
            // $conn->query("CALL spGetAdminPassword('$username', @admin_pass)");

            // $result = $conn->query("SELECT @admin_pass AS pass");

            if($username ='motorpooladmin' && $password = 'admin123'){
                header("Location: dashboard.php");
                exit();
            }

            // if ($row = $result->fetch_assoc()) {
            //     $storedPassword = $row['pass'];
            //     $inputEncrypted = encrypt($password);

            //     if ($inputEncrypted === $storedPassword) {
            //         $_SESSION['admin_logged_in'] = true;
            //         $_SESSION['admin_username'] = $username;
            //         header("Location: dashboard.php");
            //         exit();
            //     }
            // }


            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: 'Wrong Username or Password. Try again.'
            }).then(() => {
                window.location.href = 'login.php';
            });
            </script>
            ";
            $conn->close();
        }
?>


