<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = "Contact Us - LUXLIGHT";
include '../includes/header.php';
include '../includes/navbar.php';

// Fetch current settings
$settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic form handling (could be sent to email)
    $success = "Thank you! Your message has been sent. We will get back to you shortly.";
}
?>

<div class="bg-gray-900 text-white py-24 px-4">
    <div class="container mx-auto text-center">
        <h1 class="text-5xl font-bold mb-6 tracking-tight">Get in Touch</h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">Have a question about our products or need help with a custom lighting project? Our experts are here to help.</p>
    </div>
</div>

<div class="container mx-auto px-4 -mt-12 relative z-10 mb-20">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <!-- Contact Form -->
            <div class="md:w-3/5 p-12">
                <h2 class="text-3xl font-bold mb-8">Send us a Message</h2>
                
                <?php if ($success): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded">
                        <i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2 uppercase tracking-wide">Full Name</label>
                            <input type="text" placeholder="John Doe" class="w-full px-4 py-4 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 bg-gray-50 transition" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2 uppercase tracking-wide">Email Address</label>
                            <input type="email" placeholder="john@example.com" class="w-full px-4 py-4 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 bg-gray-50 transition" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2 uppercase tracking-wide">Subject</label>
                        <input type="text" placeholder="Product Inquiry" class="w-full px-4 py-4 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 bg-gray-50 transition" required>
                    </div>
                    <div class="mb-8">
                        <label class="block text-gray-700 text-sm font-bold mb-2 uppercase tracking-wide">Your Message</label>
                        <textarea rows="6" placeholder="How can we help you?" class="w-full px-4 py-4 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 bg-gray-50 transition" required></textarea>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-xl hover:bg-black transition shadow-xl text-lg">Send Message &rarr;</button>
                </form>
            </div>

            <!-- Contact Info Sidebar -->
            <div class="md:w-2/5 bg-yellow-400 p-12 text-black">
                <h2 class="text-3xl font-bold mb-10">Contact Information</h2>
                
                <div class="space-y-10">
                    <div class="flex items-start">
                        <div class="bg-black text-yellow-400 w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 mr-6 shadow-lg">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">Our Showroom</h4>
                            <p class="text-gray-900 leading-relaxed"><?php echo $settings ? $settings['shop_address'] : '123 Light St, Glow City, NY 10001'; ?></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="bg-black text-yellow-400 w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 mr-6 shadow-lg">
                            <i class="fas fa-phone-alt text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">Phone Number</h4>
                            <p class="text-gray-900 text-lg"><?php echo $settings ? $settings['admin_phone'] : '+1 234 567 890'; ?></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="bg-black text-yellow-400 w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 mr-6 shadow-lg">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">Email Support</h4>
                            <p class="text-gray-900 text-lg"><?php echo $settings ? $settings['admin_email'] : 'support@luxlight.com'; ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-16">
                    <h4 class="font-bold mb-6">Follow Our Journey</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-12 h-12 bg-black text-yellow-400 rounded-xl flex items-center justify-center hover:scale-110 transition"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="w-12 h-12 bg-black text-yellow-400 rounded-xl flex items-center justify-center hover:scale-110 transition"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="#" class="w-12 h-12 bg-black text-yellow-400 rounded-xl flex items-center justify-center hover:scale-110 transition"><i class="fab fa-pinterest-p text-xl"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
