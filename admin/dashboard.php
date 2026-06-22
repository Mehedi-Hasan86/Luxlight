<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('admin/index.php');
}

// Fetch Stats
$productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Fetch Recent Products
$recentProducts = $pdo->query("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LUXLIGHT</title>
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
                <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Administrator</p>
            </div>
            <nav class="mt-6 px-4">
                <a href="dashboard.php" class="flex items-center py-3 px-4 bg-yellow-400 text-black rounded-lg font-bold transition duration-200">
                    <i class="fas fa-tachometer-alt mr-3 w-5"></i> Dashboard
                </a>
                <a href="products.php" class="flex items-center mt-2 py-3 px-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition duration-200">
                    <i class="fas fa-box mr-3 w-5"></i> Products
                </a>
                <a href="categories.php" class="flex items-center mt-2 py-3 px-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition duration-200">
                    <i class="fas fa-list mr-3 w-5"></i> Categories
                </a>
                <a href="settings.php" class="flex items-center mt-2 py-3 px-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition duration-200">
                    <i class="fas fa-cog mr-3 w-5"></i> Settings
                </a>
                <div class="mt-10 pt-4 border-t border-gray-800">
                    <a href="logout.php" class="flex items-center py-3 px-4 text-red-400 hover:bg-red-900/20 rounded-lg transition duration-200">
                        <i class="fas fa-sign-out-alt mr-3 w-5"></i> Logout
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm py-4 px-8 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Dashboard Overview</h2>
                <div class="flex items-center">
                    <span class="mr-4 text-sm text-gray-500">Welcome, <strong><?php echo $_SESSION['username']; ?></strong></span>
                    <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-black font-bold">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto p-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg mr-4 text-blue-500">
                                <i class="fas fa-box text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Products</p>
                                <h3 class="text-3xl font-bold"><?php echo $productCount; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg mr-4 text-green-500">
                                <i class="fas fa-list text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Categories</p>
                                <h3 class="text-3xl font-bold"><?php echo $categoryCount; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg mr-4 text-yellow-600">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Users</p>
                                <h3 class="text-3xl font-bold"><?php echo $userCount; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold">Recently Added Products</h3>
                        <a href="products.php" class="text-sm text-yellow-600 font-bold hover:underline">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-bold">
                                <tr>
                                    <th class="px-6 py-4">Product</th>
                                    <th class="px-6 py-4">Category</th>
                                    <th class="px-6 py-4">Price</th>
                                    <th class="px-6 py-4">Stock</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if ($recentProducts): ?>
                                    <?php foreach ($recentProducts as $product): ?>
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded bg-gray-200 mr-3 flex-shrink-0">
                                                    <?php if ($product['image']): ?>
                                                        <img src="../uploads/products/<?php echo $product['image']; ?>" class="w-full h-full object-cover rounded">
                                                    <?php endif; ?>
                                                </div>
                                                <span class="font-bold text-gray-700"><?php echo $product['title']; ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500"><?php echo $product['category_name'] ?: 'Uncategorized'; ?></td>
                                        <td class="px-6 py-4 font-bold text-gray-900"><?php echo formatPrice($product['price']); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-bold"><?php echo $product['stock']; ?> in stock</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="products.php?edit=<?php echo $product['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3"><i class="fas fa-edit"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">No products found. Start adding some!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
