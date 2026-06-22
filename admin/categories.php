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
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Category deleted successfully";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['category_name']);
    $description = sanitize($_POST['description']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE categories SET category_name=?, description=? WHERE id=?");
        $stmt->execute([$name, $description, $id]);
        $success = "Category updated successfully";
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        $success = "Category added successfully";
    }
}

// Fetch Categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();

// Fetch editing category
$editCategory = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editCategory = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - LUXLIGHT</title>
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
                <a href="categories.php" class="flex items-center mt-2 py-3 px-4 bg-yellow-400 text-black rounded-lg font-bold">
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
                <h2 class="text-xl font-bold text-gray-800">Categories Management</h2>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                <?php if ($success): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Category Form -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm h-fit">
                        <h3 class="text-lg font-bold mb-6"><?php echo $editCategory ? 'Edit Category' : 'Add New Category'; ?></h3>
                        <form action="categories.php" method="POST">
                            <?php if ($editCategory): ?>
                                <input type="hidden" name="id" value="<?php echo $editCategory['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Category Name</label>
                                <input type="text" name="category_name" value="<?php echo $editCategory ? $editCategory['category_name'] : ''; ?>" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none" required>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-yellow-400 outline-none"><?php echo $editCategory ? $editCategory['description'] : ''; ?></textarea>
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="flex-1 bg-gray-900 text-white font-bold py-2 rounded-lg hover:bg-black transition">
                                    <?php echo $editCategory ? 'Update Category' : 'Save Category'; ?>
                                </button>
                                <?php if ($editCategory): ?>
                                    <a href="categories.php" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg hover:bg-gray-300 transition text-center">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <!-- Category Table -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold">Categories List</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-bold">
                                    <tr>
                                        <th class="px-6 py-4">ID</th>
                                        <th class="px-6 py-4">Category Name</th>
                                        <th class="px-6 py-4">Products</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($categories as $c): 
                                        $pCount = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
                                        $pCount->execute([$c['id']]);
                                        $count = $pCount->fetchColumn();
                                    ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-gray-500"><?php echo $c['id']; ?></td>
                                        <td class="px-6 py-4 font-bold text-gray-700"><?php echo $c['category_name']; ?></td>
                                        <td class="px-6 py-4 text-gray-500"><?php echo $count; ?> items</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="categories.php?edit=<?php echo $c['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3"><i class="fas fa-edit"></i></a>
                                            <a href="categories.php?delete=<?php echo $c['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Deleting this category will set associated products to uncategorized. Continue?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
