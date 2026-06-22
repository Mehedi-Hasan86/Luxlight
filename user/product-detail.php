<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    redirect('user/products.php');
}

$pageTitle = $product['title'] . " - LUXLIGHT";
include '../includes/header.php';
include '../includes/navbar.php';

// Fetch Related Products
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 4");
$stmt->execute([$product['category_id'], $id]);
$related = $stmt->fetchAll();

// Fetch Admin Settings for contact
$settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();
?>

<div class="container mx-auto px-4 py-12">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-sm font-medium text-gray-500">
        <a href="../index.php" class="hover:text-yellow-600">Home</a>
        <span class="mx-3">/</span>
        <a href="products.php" class="hover:text-yellow-600">Products</a>
        <span class="mx-3">/</span>
        <span class="text-gray-900"><?php echo $product['title']; ?></span>
    </nav>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-20">
        <div class="flex flex-col md:flex-row">
            <!-- Product Image -->
            <div class="md:w-1/2 p-8 bg-gray-50">
                <div class="relative h-[500px] rounded-2xl overflow-hidden shadow-inner group">
                    <?php if ($product['image']): ?>
                        <img src="../uploads/products/<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition duration-700">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="fas fa-image text-[100px]"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Details -->
            <div class="md:w-1/2 p-12">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold uppercase tracking-widest rounded-full mb-3">
                            <?php echo $product['category_name']; ?>
                        </span>
                        <h1 class="text-4xl font-bold text-gray-900"><?php echo $product['title']; ?></h1>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">
                        <?php echo formatPrice($product['price']); ?>
                    </div>
                </div>

                <div class="mb-10">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Description</h3>
                    <p class="text-gray-600 leading-relaxed text-lg"><?php echo nl2br($product['description']); ?></p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-10">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <span class="text-gray-400 text-sm block mb-1">Availability</span>
                        <span class="font-bold <?php echo $product['stock'] > 0 ? 'text-green-600' : 'text-red-500'; ?>">
                            <?php echo $product['stock'] > 0 ? $product['stock'] . ' Units in Stock' : 'Out of Stock'; ?>
                        </span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <span class="text-gray-400 text-sm block mb-1">Delivery</span>
                        <span class="font-bold text-gray-900">3-5 Business Days</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['admin_phone'] ?? ''); ?>" target="_blank" class="flex-1 bg-green-500 text-white text-center py-4 rounded-xl font-bold hover:bg-green-600 transition shadow-lg flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-2 text-xl"></i> Order via WhatsApp
                    </a>
                    <a href="contact.php" class="flex-1 border-2 border-gray-900 text-gray-900 text-center py-4 rounded-xl font-bold hover:bg-gray-900 hover:text-white transition flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i> Inquire Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if ($related): ?>
    <section class="mb-20">
        <h2 class="text-3xl font-bold mb-10">Related Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php foreach ($related as $rp): ?>
                <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition group">
                    <div class="h-48 bg-gray-100 overflow-hidden relative">
                        <img src="../uploads/products/<?php echo $rp['image']; ?>" alt="<?php echo $rp['title']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-gray-900 mb-2 truncate"><?php echo $rp['title']; ?></h3>
                        <p class="text-yellow-600 font-bold mb-4"><?php echo formatPrice($rp['price']); ?></p>
                        <a href="product-detail.php?id=<?php echo $rp['id']; ?>" class="block text-center text-sm font-bold border border-gray-900 py-2 rounded-lg hover:bg-gray-900 hover:text-white transition">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
