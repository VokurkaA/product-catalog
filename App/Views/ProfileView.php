<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Profile</title>
</head>

<body class="bg-gray-900 min-h-screen flex flex-col pt-24 select-none">
    <?php (new \App\Controllers\HeaderController())->handle(); ?>

    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-gray-800 rounded-lg shadow-lg p-8 border border-gray-700">
            <h1 class="text-3xl font-bold text-white mb-6">Profile Details</h1>

            <?php if (!empty($errors)): ?>
                <div class="mb-4 p-4 bg-red-500 bg-opacity-10 border border-red-500 rounded-lg">
                    <?php foreach ($errors as $error): ?>
                        <p class="text-red-500"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-400 mb-1">Username</label>
                        <input type="text"
                            name="username"
                            value="<?= htmlspecialchars($user->username) ?>"
                            class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-400 mb-2">Email Address</label>
                        <input type="email"
                            name="email"
                            value="<?= htmlspecialchars($user->email) ?>"
                            class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 
                            text-white placeholder-gray-400 focus:outline-none 
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            transition-all duration-200 hover:bg-gray-600">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-400 mb-2">Phone Number</label>
                        <input type="tel"
                            name="phoneNumber"
                            value="<?= htmlspecialchars($user->phoneNumber ?? '') ?>"
                            placeholder="Not set"
                            class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 
                            text-white placeholder-gray-400 focus:outline-none 
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            transition-all duration-200 hover:bg-gray-600">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-400 mb-2">Address</label>
                        <input type="text"
                            name="address"
                            value="<?= htmlspecialchars($user->address ?? '') ?>"
                            placeholder="Not set"
                            class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 
                            text-white placeholder-gray-400 focus:outline-none 
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            transition-all duration-200 hover:bg-gray-600">
                    </div>

                    <div class="mb-6 select">
                        <label class="block text-gray-400 mb-2">Role</label>
                        <p class="text-white text-lg capitalize bg-gray-700 p-3 rounded-lg border border-gray-600">
                            <?= htmlspecialchars($user->role) ?>
                        </p>
                    </div>

                    <div class="mb-6">
                        <details class="text-white bg-gray-700 rounded-lg border border-gray-600">
                            <summary class="cursor-pointer p-4 hover:bg-gray-600 transition-colors duration-200">
                                <span class="font-medium">Liked Products (<?= count($user->liked) ?>)</span>
                            </summary>
                            <div class="p-4 border-t border-gray-600">
                                <?php if (count($user->liked) === 0): ?>
                                    <p class="text-gray-400">No liked products.</p>
                                <?php else: ?>
                                    <div class="space-y-2">
                                        <?php foreach ($user->liked as $likedProductId): ?>
                                            <a href="/product-catalog/product/<?= htmlspecialchars($likedProductId) ?>"
                                                class="block text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                                <?= htmlspecialchars($products[$likedProductId]->name) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </details>
                    </div>

                    <div class="mb-6">
                        <details class="text-white bg-gray-700 rounded-lg border border-gray-600">
                            <summary class="cursor-pointer p-4 hover:bg-gray-600 transition-colors duration-200">
                                <span class="font-medium">Previous Purchases (<?= count($user->previousPurchases) ?>)</span>
                            </summary>
                            <div class="p-4 border-t border-gray-600">
                                <?php if (count($user->previousPurchases) === 0): ?>
                                    <p class="text-gray-400">No previous purchases.</p>
                                <?php else: ?>
                                    <div class="space-y-2">
                                        <?php foreach ($user->previousPurchases as $purchase): ?>
                                            <a href="/product-catalog/product/<?= htmlspecialchars($purchase) ?>"
                                                class="block text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                                <?= htmlspecialchars($products[$purchase]->name) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </details>
                    </div>
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="submit"
                        name="update"
                        class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Update Profile
                    </button>
                    <button type="submit"
                        name="logout"
                        class="bg-red-600 hover:bg-red-500 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Logout
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php require_once './App/Views/Footer.php'; ?>
</body>

</html>