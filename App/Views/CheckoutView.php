.php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Checkout</title>
</head>

<body class="bg-gray-900 min-h-screen flex flex-col pt-24">
<?php (new \App\Controllers\HeaderController())->handle(); ?>

<div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-8">Checkout</h1>
        <?php if (!empty($errors)): ?>
            <div class="mb-6 bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded relative" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p class="mb-2"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
            <form method="POST" class="space-y-6">
                <div>
                    <h2 class="text-xl font-semibold text-white mb-4">Shipping Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-gray-300 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" required
                                   value="<?= $user->username ?? '' ?>"
                                   class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-300 mb-2">Email</label>
                            <input type="email" id="email" name="email" required
                                   value="<?= $user->email ?? '' ?>"
                                   class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="address" class="block text-gray-300 mb-2">Address</label>
                            <input type="text" id="address" name="address" required
                                   value="<?= $user->address ?? '' ?>"
                                   class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-300 mb-2">Phone number</label>
                            <input type="text" id="phone" name="phone" required
                                   value="<?= $user->phoneNumber ?? '' ?>"
                                   class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="border-t border-gray-700 pt-6">
                    <h2 class="text-xl font-semibold text-white mb-4">Order Details</h2>
                    <div class="space-y-4">
                        <?php foreach ($groupedProducts as $group): ?>
                            <div class="flex justify-between items-center text-gray-300">
                                <div class="flex items-center">
                                    <span class="mr-2"><?= htmlspecialchars($group['quantity']) ?>x</span>
                                    <span><?= htmlspecialchars($group['product']->name) ?></span>
                                    <span class="text-gray-500 ml-2">(<?= htmlspecialchars($group['product']->price) ?>﹩ each)</span>
                                </div>
                                <span><?= htmlspecialchars($group['product']->price * $group['quantity']) ?>﹩</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t border-gray-700 pt-6">
                    <h2 class="text-xl font-semibold text-white mb-4">Order Summary</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-400">
                            <span>Subtotal:</span>
                            <span><?= number_format($total, 2) ?>﹩</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <div class="flex justify-between text-white font-semibold text-lg pt-2 border-t border-gray-700">
                            <span>Total:</span>
                            <span class="text-green-400"><?= number_format($total, 2) ?>﹩</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-6">
                    <a href="/product-catalog/cart"
                       class="bg-gray-700 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        Back to Cart
                    </a>
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once './App/Views/Footer.php'; ?>
</body>

</html>