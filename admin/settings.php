<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('admin/index.php');
}

$success = '';

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_name = sanitize($_POST['shop_name']);
    $admin_email = sanitize($_POST['admin_email']);
    $admin_phone = sanitize($_POST['admin_phone']);
    $shop_address = sanitize($_POST['shop_address']);

    // Check if settings exist
    $check = $pdo->query("SELECT id FROM settings LIMIT 1")->fetch();
    
    if ($check) {
        $stmt = $pdo->prepare("UPDATE settings SET shop_name=?, admin_email=?, admin_phone=?, shop_address=? WHERE id=?");
        $stmt->execute([$shop_name, $admin_email, $admin_phone, $shop_address, $check['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO settings (shop_name, admin_email, admin_phone, shop_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$shop_name, $admin_email, $admin_phone, $shop_address]);
    }
    $success = "Settings updated successfully";
}

// Fetch current settings
$settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Settings - LUXLIGHT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6 text-center border-b border-gray-800">
                <h1 class="text-2xl font-bold">LUX<span class="text-yellow-400">LIGHT</span></h1>
            </div>
            <nav class="mt-6 px-4">
                <a href="dashboard.php" class="flex items-center py-3 px-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition">
                    <i class="fas fa-tachometer-alt mr-3 w-5"></i> Dashboard
                </a>
                <a href="products.php" class="flex items-center mt-2 py-3 px-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition">
                    <i class="fas fa-box mr-3 w-5"></i> Products
                </a>
                <a href="categories.php" class="flex items-center mt-2 py-3 px-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition">
                    <i class="fas fa-list mr-3 w-5"></i> Categories
                </a>
                <a href="settings.php" class="flex items-center mt-2 py-3 px-4 bg-yellow-400 text-black rounded-lg font-bold">
                    <i class="fas fa-cog mr-3 w-5"></i> Settings
                </a>
                <div class="mt-10 pt-4 border-t border-gray-800">
                    <a href="logout.php" class="flex items-center py-3 px-4 text-red-400 hover:bg-red-900/20 rounded-lg transition">
                        <i class="fas fa-sign-out-alt mr-3 w-5"></i> Logout
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm py-4 px-8 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Shop Settings</h2>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                <?php if ($success): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="max-w-2xl bg-white p-8 rounded-2xl shadow-sm">
                    <h3 class="text-lg font-bold mb-8 border-b pb-4">General Configuration</h3>
                    <form action="settings.php" method="POST">
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Shop Name</label>
                            <input type="text" name="shop_name" value="<?php echo $settings ? $settings['shop_name'] : 'LUXLIGHT'; ?>" class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Admin Email</label>
                                <input type="email" name="admin_email" value="<?php echo $settings ? $settings['admin_email'] : 'admin@luxlight.com'; ?>" class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Contact Phone</label>
                                <input type="text" name="admin_phone" value="<?php echo $settings ? $settings['admin_phone'] : '+1 234 567 890'; ?>" class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Shop Address</label>
                            <textarea name="shop_address" rows="3" class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required><?php echo $settings ? $settings['shop_address'] : '123 Light St, Glow City'; ?></textarea>
                        </div>

                        <button type="submit" class="w-full md:w-auto px-8 bg-gray-900 text-white font-bold py-3 rounded-lg hover:bg-black transition shadow-lg">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
