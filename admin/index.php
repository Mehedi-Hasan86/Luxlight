<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isAdmin()) {
    redirect('admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        redirect('admin/dashboard.php');
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - LUXLIGHT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">LUX<span class="text-yellow-500">LIGHT</span></h1>
            <p class="text-gray-500 mt-2">Admin Control Panel Access</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400" type="text" name="username" id="username" placeholder="Enter username" required>
                </div>
            </div>
            <div class="mb-8">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400" type="password" name="password" id="password" placeholder="Enter password" required>
                </div>
            </div>
            <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3 rounded-lg hover:bg-black transition duration-300 shadow-lg">Login to Dashboard</button>
        </form>
        
        <div class="mt-8 text-center text-gray-500 text-sm">
            <a href="../index.php" class="hover:text-yellow-600"><i class="fas fa-arrow-left mr-2"></i>Back to Website</a>
        </div>
    </div>
</body>
</html>
