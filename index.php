<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

$pageTitle = "LUXLIGHT - Premium Lighting Solutions";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- Hero Section -->
<header class="bg-gray-900 text-white py-32 px-4 relative overflow-hidden">
    <div class="container mx-auto relative z-10">
        <div class="max-w-2xl">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">Illuminate Your <span class="text-yellow-400">Dream Space</span></h1>
            <p class="text-xl text-gray-400 mb-10">Discover our curated collection of designer lamps, chandeliers, and modern lighting solutions that transform any room.</p>
            <div class="flex space-x-4">
                <a href="user/products.php" class="bg-yellow-400 text-black px-8 py-4 rounded-full font-bold hover:bg-yellow-500 transition duration-300">Shop Collection</a>
                <a href="#featured" class="border-2 border-white px-8 py-4 rounded-full font-bold hover:bg-white hover:text-black transition duration-300">Learn More</a>
            </div>
        </div>
    </div>
    <!-- Decorative background element -->
    <div class="absolute top-0 right-0 w-1/2 h-full opacity-20 pointer-events-none">
        <i class="fas fa-lightbulb text-[400px] text-yellow-400 rotate-12 -mr-32 -mt-32"></i>
    </div>
</header>

<!-- Categories Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-bold mb-2">Shop by Category</h2>
                <div class="w-20 h-1 bg-yellow-400"></div>
            </div>
            <a href="user/products.php" class="text-yellow-600 font-bold hover:underline">View All Categories &rarr;</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Static Category 1 -->
            <div class="group relative h-80 overflow-hidden rounded-2xl shadow-lg cursor-pointer">
                <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-30 transition duration-300"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-8 text-white">
                    <h3 class="text-2xl font-bold mb-2">Pendant Lights</h3>
                    <p class="text-gray-200 text-sm mb-4">Elegant hanging solutions for dining and living areas.</p>
                    <span class="inline-block w-fit text-sm font-bold border-b-2 border-yellow-400">Explore Collection</span>
                </div>
            </div>
            <!-- Static Category 2 -->
            <div class="group relative h-80 overflow-hidden rounded-2xl shadow-lg cursor-pointer">
                <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-30 transition duration-300"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-8 text-white">
                    <h3 class="text-2xl font-bold mb-2">Modern Lamps</h3>
                    <p class="text-gray-200 text-sm mb-4">Stylish desk and floor lamps for your workspace.</p>
                    <span class="inline-block w-fit text-sm font-bold border-b-2 border-yellow-400">Explore Collection</span>
                </div>
            </div>
            <!-- Static Category 3 -->
            <div class="group relative h-80 overflow-hidden rounded-2xl shadow-lg cursor-pointer">
                <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-30 transition duration-300"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-8 text-white">
                    <h3 class="text-2xl font-bold mb-2">Wall Sconces</h3>
                    <p class="text-gray-200 text-sm mb-4">Subtle and artistic wall lighting for every corner.</p>
                    <span class="inline-block w-fit text-sm font-bold border-b-2 border-yellow-400">Explore Collection</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="featured" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 text-center mb-16">
        <h2 class="text-4xl font-bold mb-4">Featured Products</h2>
        <p class="text-gray-600 max-w-xl mx-auto">Our most popular lighting pieces, loved by interior designers and homeowners alike.</p>
    </div>
    
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
        <?php
        // Fetch some products if they exist, otherwise show placeholders
        $stmt = $pdo->query("SELECT * FROM products LIMIT 4");
        $products = $stmt->fetchAll();
        
        if ($products):
            foreach ($products as $product):
        ?>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="uploads/products/<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-bold shadow-md">
                        <?php echo formatPrice($product['price']); ?>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-2"><?php echo $product['title']; ?></h3>
                    <p class="text-gray-500 text-sm mb-4 line-clamp-2"><?php echo $product['description']; ?></p>
                    <a href="user/product-detail.php?id=<?php echo $product['id']; ?>" class="block text-center border-2 border-gray-900 py-2 rounded-lg font-bold hover:bg-gray-900 hover:text-white transition duration-300">View Details</a>
                </div>
            </div>
        <?php 
            endforeach;
        else:
            // Placeholder if no products yet
            for($i=1; $i<=4; $i++):
        ?>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 group">
                <div class="relative h-64 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                    <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-bold shadow-md">
                        $99.00
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-2">Classic Pendant #<?php echo $i; ?></h3>
                    <p class="text-gray-500 text-sm mb-4">Elegant lighting fixture that adds a touch of class to any room in your home.</p>
                    <a href="#" class="block text-center border-2 border-gray-900 py-2 rounded-lg font-bold hover:bg-gray-900 hover:text-white transition duration-300">View Details</a>
                </div>
            </div>
        <?php 
            endfor;
        endif; 
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
