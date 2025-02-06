<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Cart</title>
</head>

<body class="bg-gray-900 min-h-screen flex flex-col pt-24">
<?php (new \App\Controllers\HeaderController())->handle(); ?>

    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-white mb-8">Shopping Cart</h1>

            <?php if (empty($groupedProducts)): ?>
                <div class="bg-gray-800 rounded-lg p-8 text-center border border-gray-700">
                    <p class="text-gray-400 text-lg mb-4">Your cart is empty</p>
                    <a href="/product-catalog" class="inline-block bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Continue Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700">
                    <div class="p-6">
                        <?php foreach ($groupedProducts as $group): ?>
                            <div class="flex items-center justify-between py-4 border-b border-gray-700 last:border-0">
                                <div class="flex items-center flex-grow">
                                    <div class="w-20 h-20 bg-gray-700 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-semibold text-white">
                                            <?= htmlspecialchars($group['product']->name) ?>
                                        </h3>
                                        <p class="text-gray-400">
                                            <?= htmlspecialchars($group['product']->brand) ?>
                                        </p>
                                        <p class="text-gray-400">
                                            Quantity: <?= $group['quantity'] ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-xl font-bold text-green-400">
                                        <?php if ($group['quantity'] > 1): ?>
                                            <span class="text-sm mr-2 text-gray-400">(<?= htmlspecialchars($group['product']->price) ?>﹩ * <?= htmlspecialchars($group['quantity']) ?>)</span>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($group['product']->price * $group['quantity']) ?>﹩
                                    </span>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="product_id" value="<?= $group['product']->id ?>">
                                        <button type="submit" name="remove" class="text-red-500 hover:text-red-400 transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="bg-gray-900 p-6 rounded-b-lg">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-lg text-gray-400">Total:</span>
                            <span class="text-2xl font-bold text-green-400">
                                <?= number_format(array_sum(array_map(fn($groupedProducts) => $groupedProducts['product']->price * $groupedProducts['quantity'], $groupedProducts)), 2) ?>
                            </span>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <a href="/product-catalog" class="bg-gray-700 text-white hover:bg-gray-600 font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                                Continue Shopping
                            </a>
                            <a href="/product-catalog/checkout" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                                Checkout
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once './App/Views/Footer.php'; ?>
</body>

</html>