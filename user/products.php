<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = "Products - LUXLIGHT";
include '../includes/header.php';
include '../includes/navbar.php';

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// Build Query
$query = "SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];

if ($category_id) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_id;
}

if ($search) {
    $query .= " AND (p.title LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();
?>

<div class="bg-gray-900 text-white py-20 mb-12">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Collection</h1>
        <p class="text-gray-400">Browse through our extensive range of high-quality lighting products.</p>
    </div>
</div>

<div class="container mx-auto px-4">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="w-full md:w-1/4">
            <div class="bg-white p-6 rounded-2xl shadow-sm mb-8">
                <h3 class="text-lg font-bold mb-6 flex items-center">
                    <i class="fas fa-search mr-2 text-yellow-500"></i> Search
                </h3>
                <form action="" method="GET" class="relative">
                    <input type="text" name="search" value="<?php echo $search; ?>" placeholder="What are you looking for?" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <button type="submit" class="absolute right-3 top-3 text-gray-400 hover:text-yellow-500">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h3 class="text-lg font-bold mb-6 flex items-center">
                    <i class="fas fa-filter mr-2 text-yellow-500"></i> Categories
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="products.php" class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 <?php echo !$category_id ? 'bg-yellow-50 text-yellow-700 font-bold' : 'text-gray-600'; ?>">
                            All Products
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="products.php?category=<?php echo $cat['id']; ?>" class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 <?php echo $category_id == $cat['id'] ? 'bg-yellow-50 text-yellow-700 font-bold' : 'text-gray-600'; ?>">
                            <?php echo $cat['category_name']; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>

        <!-- Product Grid -->
        <main class="flex-1">
            <?php if ($search): ?>
                <div class="mb-8 text-gray-600">
                    Showing results for: <span class="font-bold text-gray-900">"<?php echo $search; ?>"</span>
                </div>
            <?php endif; ?>

            <?php if ($products): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($products as $p): ?>
                        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 group">
                            <div class="relative h-64 overflow-hidden bg-gray-100">
                                <?php if ($p['image']): ?>
                                    <img src="../uploads/products/<?php echo $p['image']; ?>" alt="<?php echo $p['title']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-image text-4xl"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-bold shadow-md">
                                    <?php echo formatPrice($p['price']); ?>
                                </div>
                            </div>
                            <div class="p-6">
                                <span class="text-xs text-yellow-600 font-bold uppercase tracking-widest mb-2 block"><?php echo $p['category_name']; ?></span>
                                <h3 class="text-xl font-bold mb-2 group-hover:text-yellow-600 transition"><?php echo $p['title']; ?></h3>
                                <p class="text-gray-500 text-sm mb-6 line-clamp-2"><?php echo $p['description']; ?></p>
                                <a href="product-detail.php?id=<?php echo $p['id']; ?>" class="block text-center bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-black transition duration-300 shadow-lg">View Product</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white p-20 rounded-2xl text-center shadow-sm">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
                        <i class="fas fa-search text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">No Products Found</h2>
                    <p class="text-gray-500 mb-8">Try adjusting your search or filters to find what you're looking for.</p>
                    <a href="products.php" class="bg-yellow-400 text-black px-8 py-3 rounded-full font-bold hover:bg-yellow-500">Clear All Filters</a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
