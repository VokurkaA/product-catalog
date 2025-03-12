<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Dashboard</title>
</head>

<body class="bg-gray-900 min-h-screen flex flex-col pt-24">
    <?php (new \App\Controllers\HeaderController())->handle(); ?>

    <?php if (!empty($errors)): ?>
        <div class="fixed top-24 right-4 z-50 animate-fade-out">
            <div class="bg-red-500 bg-opacity-10 border border-red-500 text-red-500 px-6 py-4 rounded-lg shadow-lg">
                <?php foreach ($errors as $error): ?>
                    <p class="mb-1"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-white mb-8">Admin Dashboard</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Products Section -->
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <h2 class="text-xl font-bold text-white mb-6">Products Management</h2>
                <form action="<?php $selectedProductId ? 'editProduct' : 'addProduct' ?>" method="POST">
                    <div class="mb-4">
                        <label for="productSelect" class="block mb-2 text-gray-300">Select Product</label>
                        <select id="productSelect" name="selectedProductId" class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white" onchange="this.form.submit()">
                            <option value="">Select a product...</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product->id ?>" <?= isset($_POST['selectedProductId']) && $_POST['selectedProductId'] == $product->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($product->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $selectedProductId ?? '' ?>">

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">ID:</label>
                        <input type="text" value="<?= $selectedProductId ? $selectedProduct->id : '' ?>"
                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">Name:</label>
                        <input type="text" name="name" value="<?= $selectedProductId ? htmlspecialchars($selectedProduct->name) : '' ?>"
                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">Description:</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white"><?= $selectedProductId ? htmlspecialchars($selectedProduct->description) : '' ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">Brand:</label>
                        <input type="text" name="brand" value="<?= $selectedProductId ? htmlspecialchars($selectedProduct->brand) : '' ?>"
                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">Price:</label>
                        <input type="text" name="price" value="<?= $selectedProductId ? htmlspecialchars($selectedProduct->price) : '' ?>"
                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">Stock:</label>
                        <input type="number" name="stock" value="<?= $selectedProductId ? $selectedProduct->stock : '' ?>"
                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">Category:</label>
                        <select name="category_id" class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                            <option value="">Select a category...</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>" <?= $selectedProductId && $selectedProduct->categoryId == $category->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">Rating:</label>
                        <input type="text" value="<?= $selectedProductId ? number_format(array_sum($selectedProduct->rating) / count($selectedProduct->rating), 1) : '' ?>"
                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-gray-300">
                    </div>

                    <?php if ($selectedProductId): ?>
                        <div class="flex justify-end mt-6 space-x-4">
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200"> Remove Product</button>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">Save Changes</button>
                        </div>
                    <?php else: ?>
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                                Add New Product
                            </button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Categories Section -->
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <h2 class="text-xl font-bold text-white mb-6">Categories Management</h2>
                <form action="<?php $selectedCategoryId ? 'editCategory' : 'addCategory' ?>" method="POST">

                    <div class="mb-4">
                        <label for="categorySelect" class="block mb-2 text-gray-300">Select Category</label>
                        <select id="categorySelect" name="selectedCategoryId" class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white" onchange="this.form.submit()">
                            <option value="">Select a category...</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>" <?= isset($_POST['selectedCategoryId']) && $_POST['selectedCategoryId'] == $category->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">ID:</label>
                        <input type="text" value="<?= $selectedCategoryId ?? '' ?>"
                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">Name:</label>
                        <input type="text" name="name" value="<?= $selectedCategoryId ? htmlspecialchars($categories[$selectedCategoryId]->name) : '' ?>"
                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-gray-300">Parent Category:</label>
                        <select name="parent_id" class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                            <option value="">No parent</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>" <?= $selectedCategoryId && $categories[$selectedCategoryId]->parentId == $category->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if ($selectedCategoryId): ?>
                        <div class="flex justify-end mt-6 space-x-4">
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">Remove Category</button>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">Save Changes</button>
                        </div>
                    <?php else: ?>
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">Add New Category</button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Users Section -->
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6 lg:col-span-2">
                <h2 class="text-xl font-bold text-white mb-6">Users Management</h2>
                <form action="<?php $selectedUserId ? 'editUser' : 'addUser' ?>" method="POST">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <div class="mb-4">
                                <label for="userSelect" class="block mb-2 text-gray-300">Select User</label>
                                <select id="userSelect" name="selectedUserId" class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white" onchange="this.form.submit()">
                                    <option value="">Select a user...</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user->id ?>" <?= isset($_POST['selectedUserId']) && $_POST['selectedUserId'] == $user->id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($user->username) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="border-b border-gray-700 pb-4 mb-4">
                                <div class="mb-4">
                                    <label class="block mb-2 text-gray-300">ID:</label>
                                    <input type="text" name="id" value="<?= $selectedUserId ? htmlspecialchars($users[$selectedUserId]->id) : '' ?>"
                                        class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white" readonly>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-gray-300">Username:</label>
                                    <input type="text" name="username" value="<?= $selectedUserId ? htmlspecialchars($users[$selectedUserId]->username) : '' ?>"
                                        class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-gray-300">Email:</label>
                                    <input type="email" name="email" value="<?= $selectedUserId ? htmlspecialchars($users[$selectedUserId]->email) : '' ?>"
                                        class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-gray-300">New Password:</label>
                                    <input type="password" name="newPassword"
                                        class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white"
                                        placeholder="Leave empty to keep current password">
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-gray-300">Phone Number:</label>
                                    <input type="tel" name="phoneNumber" value="<?= $selectedUserId ? htmlspecialchars($users[$selectedUserId]->phoneNumber ?? '') : '' ?>"
                                        class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-gray-300">Role:</label>
                                    <select name="role" class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white">
                                        <option value="user" <?= $selectedUserId && $users[$selectedUserId]->role === 'user' ? 'selected' : '' ?>>User</option>
                                        <option value="admin" <?= $selectedUserId && $users[$selectedUserId]->role === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="owner" <?= $selectedUserId && $users[$selectedUserId]->role === 'owner' ? 'selected' : '' ?>>Owner</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-gray-300">Address:</label>
                                    <textarea name="address" class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-white" rows="3"><?= $selectedUserId ? htmlspecialchars($users[$selectedUserId]->address ?? '') : '' ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="space-y-6">
                                <div>
                                    <label class="block mb-2 text-gray-300">Cart Items:</label>
                                    <div class="bg-gray-700 p-4 rounded-lg text-white max-h-40 overflow-y-auto">
                                        <?php if ($selectedUserId && count($users[$selectedUserId]->cart) != 0): ?>
                                            <?php foreach ($users[$selectedUserId]->cart as $productId): ?>
                                                <div class="py-2 px-3 hover:bg-gray-600 rounded">
                                                    <input type="text" value="<?= $productId ?>" class="bg-gray-600 text-white p-1 rounded w-12 mr-2 inline-block">
                                                    <?= htmlspecialchars($products[$productId]->name) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="text-gray-400 py-2 px-3">No items in cart</div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($selectedUserId): ?>
                                        <div class="mt-2 flex">
                                            <select name="cart_product_id" class="bg-gray-700 text-white p-2 rounded flex-grow">
                                                <option value="">Select product to add...</option>
                                                <?php foreach ($products as $product): ?>
                                                    <option value="<?= $product->id ?>"><?= htmlspecialchars($product->name) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" name="add_to_cart" class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 ml-2">
                                                Add
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <label class="block mb-2 text-gray-300">Liked Items:</label>
                                    <div class="bg-gray-700 p-4 rounded-lg text-white max-h-40 overflow-y-auto">
                                        <?php if ($selectedUserId && count($users[$selectedUserId]->liked) != 0): ?>
                                            <?php foreach ($users[$selectedUserId]->liked as $productId): ?>
                                                <div class="py-2 px-3 hover:bg-gray-600 rounded">
                                                    <input type="text" value="<?= $productId ?>" class="bg-gray-600 text-white p-1 rounded w-12 mr-2 inline-block">
                                                    <?= htmlspecialchars($products[$productId]->name) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="text-gray-400 py-2 px-3">No liked items</div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($selectedUserId): ?>
                                        <div class="mt-2 flex">
                                            <select name="liked_product_id" class="bg-gray-700 text-white p-2 rounded flex-grow">
                                                <option value="">Select product to like...</option>
                                                <?php foreach ($products as $product): ?>
                                                    <option value="<?= $product->id ?>"><?= htmlspecialchars($product->name) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" name="add_to_liked" class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 ml-2">
                                                Add
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <label class="block mb-2 text-gray-300">Previous Purchases:</label>
                                    <div class="bg-gray-700 p-4 rounded-lg text-white max-h-40 overflow-y-auto">
                                        <?php if ($selectedUserId && count($users[$selectedUserId]->previousPurchases) != 0): ?>
                                            <?php foreach ($users[$selectedUserId]->previousPurchases as $productId): ?>
                                                <div class="py-2 px-3 hover:bg-gray-600 rounded">
                                                    <input type="text" value="<?= $productId ?>" class="bg-gray-600 text-white p-1 rounded w-12 mr-2 inline-block">
                                                    <?= htmlspecialchars($products[$productId]->name) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="text-gray-400 py-2 px-3">No purchase history</div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($selectedUserId): ?>
                                        <div class="mt-2 flex">
                                            <select name="purchase_product_id" class="bg-gray-700 text-white p-2 rounded flex-grow">
                                                <option value="">Select product to add...</option>
                                                <?php foreach ($products as $product): ?>
                                                    <option value="<?= $product->id ?>"><?= htmlspecialchars($product->name) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" name="add_to_purchases" class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 ml-2">
                                                Add
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($selectedUserId): ?>
                        <div class="flex justify-end mt-6 space-x-4">
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">Remove User</button>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">Save Changes</button>
                        </div>
                    <?php else: ?>
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">Add New User</button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

        </div>
    </div>

    <?php require_once './App/Views/Footer.php'; ?>
</body>

</html>