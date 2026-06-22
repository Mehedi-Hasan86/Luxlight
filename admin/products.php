<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('admin/index.php');
}

$success = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Product deleted successfully";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $description = sanitize($_POST['description']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = uploadImage($_FILES['image'], '../uploads/products/');
    }

    if ($id) {
        // Update
        if ($imageName) {
            $stmt = $pdo->prepare("UPDATE products SET title=?, category_id=?, price=?, stock=?, description=?, image=? WHERE id=?");
            $stmt->execute([$title, $category_id, $price, $stock, $description, $imageName, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE products SET title=?, category_id=?, price=?, stock=?, description=? WHERE id=?");
            $stmt->execute([$title, $category_id, $price, $stock, $description, $id]);
        }
        $success = "Product updated successfully";
    } else {
        // Create
        $stmt = $pdo->prepare("INSERT INTO products (title, category_id, price, stock, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category_id, $price, $stock, $description, $imageName]);
        $success = "Product added successfully";
    }
}

// Fetch Categories for dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();

// Fetch Products for listing
$products = $pdo->query("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();

// Fetch editing product if applicable
$editProduct = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editProduct = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - LUXLIGHT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar (Reusing sidebar logic) -->
        <div class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6 text-center border-b border-gray-800">
                <h1 class="text-2xl font-bold">LUX<span class="text-yellow-400">LIGHT</span></h1>
            </div>
            <nav class="mt-6 px-4">
                <a href="dashboard.php" class="flex items-center py-3 px-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition">
                    <i class="fas fa-tachometer-alt mr-3 w-5"></i> Dashboard
                </a>
                <a href="products.php" class="flex items-center mt-2 py-3 px-4 bg-yellow-400 text-black rounded-lg font-bold">
                    <i class="fas fa-box mr-3 w-5"></i> Products
                </a>
                <a href="categories.php" class="flex items-center mt-2 py-3 px-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition">
                    <i class="fas fa-list mr-3 w-5"></i> Categories
                </a>
                <a href="settings.php" class="flex items-center mt-2 py-3 px-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition">
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
                <h2 class="text-xl font-bold text-gray-800">Products Management</h2>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                <?php if ($success): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    <!-- Product Form -->
                    <div class="xl:col-span-1">
                        <div class="bg-white p-6 rounded-2xl shadow-sm">
                            <h3 class="text-lg font-bold mb-6"><?php echo $editProduct ? 'Edit Product' : 'Add New Product'; ?></h3>
                            <form action="products.php" method="POST" enctype="multipart/form-data">
                                <?php if ($editProduct): ?>
                                    <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
                                <?php endif; ?>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Product Title</label>
                                    <input type="text" name="title" value="<?php echo $editProduct ? $editProduct['title'] : ''; ?>" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                                    <select name="category_id" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>" <?php echo ($editProduct && $editProduct['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                                <?php echo $cat['category_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Price ($)</label>
                                        <input type="number" step="0.01" name="price" value="<?php echo $editProduct ? $editProduct['price'] : ''; ?>" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Stock</label>
                                        <input type="number" name="stock" value="<?php echo $editProduct ? $editProduct['stock'] : '0'; ?>" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                    <textarea name="description" rows="4" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required><?php echo $editProduct ? $editProduct['description'] : ''; ?></textarea>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Product Image</label>
                                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                    <?php if ($editProduct && $editProduct['image']): ?>
                                        <p class="mt-2 text-xs text-gray-500">Current: <?php echo $editProduct['image']; ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="flex gap-4">
                                    <button type="submit" class="flex-1 bg-gray-900 text-white font-bold py-2 rounded-lg hover:bg-black transition">
                                        <?php echo $editProduct ? 'Update Product' : 'Save Product'; ?>
                                    </button>
                                    <?php if ($editProduct): ?>
                                        <a href="products.php" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg hover:bg-gray-300 transition text-center">Cancel</a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="xl:col-span-2">
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                            <div class="p-6 border-b border-gray-100">
                                <h3 class="text-lg font-bold">Products List</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-bold">
                                        <tr>
                                            <th class="px-6 py-4">ID</th>
                                            <th class="px-6 py-4">Product</th>
                                            <th class="px-6 py-4">Category</th>
                                            <th class="px-6 py-4">Price</th>
                                            <th class="px-6 py-4">Stock</th>
                                            <th class="px-6 py-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <?php foreach ($products as $p): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-gray-500"><?php echo $p['id']; ?></td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded bg-gray-200 mr-3 flex-shrink-0">
                                                        <?php if ($p['image']): ?>
                                                            <img src="../uploads/products/<?php echo $p['image']; ?>" class="w-full h-full object-cover rounded">
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="font-bold text-gray-700"><?php echo $p['title']; ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500"><?php echo $p['category_name']; ?></td>
                                            <td class="px-6 py-4 font-bold"><?php echo formatPrice($p['price']); ?></td>
                                            <td class="px-6 py-4"><?php echo $p['stock']; ?></td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="products.php?edit=<?php echo $p['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3"><i class="fas fa-edit"></i></a>
                                                <a href="products.php?delete=<?php echo $p['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
