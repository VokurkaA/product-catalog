<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Panel</title>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen pt-24">
<?php (new \App\Controllers\HeaderController())->handle(); ?>

<div class="container mx-auto px-4 pb-12">
    <h1 class="text-4xl font-bold mb-8">Admin Panel</h1>
    <?php if (!empty($this->errors)): ?>
        <div class="bg-red-500 bg-opacity-10 border border-red-500 rounded-lg p-4 mb-6">
            <?php foreach ($this->errors as $error): ?>
                <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="flex border-b border-gray-700 mb-6">
        <a href="?tab=products"
           class="py-3 px-6 font-medium text-lg <?= $activeTab === 'products' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200' ?>">
            Products
        </a>
        <a href="?tab=categories"
           class="py-3 px-6 font-medium text-lg <?= $activeTab === 'categories' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200' ?>">
            Categories
        </a>
        <?php if ($user->role === 'owner'): ?>
            <a href="?tab=users"
               class="py-3 px-6 font-medium text-lg <?= $activeTab === 'users' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200' ?>">
                Users
            </a>
        <?php endif; ?>
    </div>

    <div class="mb-6">
        <a href="?tab=<?= $activeTab ?>&action=add"
           class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
            Add New <?= ucfirst(substr($activeTab, 0, -1)) ?>
        </a>
    </div>

    <?php if ($action === 'add' || $action === 'edit'): ?>
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 mb-8">
            <?php if ($activeTab === 'products'): ?>
                <?php
                $product = null;
                if ($action === 'edit' && isset($id)) {
                    $product = $products[$id] ?? null;
                }
                ?>
                <h2 class="text-2xl font-bold mb-4"><?= $action === 'add' ? 'Add' : 'Edit' ?> Product</h2>
                <form method="POST" class="space-y-4">
                    <?php if ($product): ?>
                        <input type="hidden" name="product_id" value="<?= $product->id ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 mb-1">Name</label>
                            <input type="text" name="name" required
                                   value="<?= $product ? htmlspecialchars($product->name) : '' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Brand</label>
                            <input type="text" name="brand" required
                                   value="<?= $product ? htmlspecialchars($product->brand) : '' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Price</label>
                            <input type="number" step="10" name="price" required
                                   value="<?= $product ? htmlspecialchars($product->price) : '' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Stock</label>
                            <input type="number" name="stock" required
                                   value="<?= $product ? htmlspecialchars($product->stock) : '10' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Rating (comma separated numbers)</label>
                            <input type="text" name="rating"
                                   value="<?= $product ? implode(',', $product->rating) : '5' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Category</label>
                            <select name="category_id" required
                                    class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->id ?>" <?= $product && $product->categoryId === $category->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-400 mb-1">Description</label>
                        <textarea name="description" rows="4" required
                                  class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white"><?= $product ? htmlspecialchars($product->description) : '' ?></textarea>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" name="save_product"
                                class="bg-green-600 hover:bg-green-500 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Save Product
                        </button>
                        <a href="?tab=products"
                           class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>

            <?php elseif ($activeTab === 'categories'): ?>
                <?php
                $category = null;
                if ($action === 'edit' && isset($id)) {
                    $category = $categories[$id] ?? null;
                }
                ?>
                <h2 class="text-2xl font-bold mb-4"><?= $action === 'add' ? 'Add' : 'Edit' ?> Category</h2>
                <form method="POST" class="space-y-4">
                    <?php if ($category): ?>
                        <input type="hidden" name="category_id" value="<?= $category->id ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 mb-1">Name</label>
                            <input type="text" name="name" required
                                   value="<?= $category ? htmlspecialchars($category->name) : '' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Parent Category</label>
                            <select name="parent_id"
                                    class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                                <option value="">No Parent (Top Level)</option>
                                <?php foreach ($categories as $cat): ?>
                                    <?php if ($category && $cat->id !== $category->id): ?>
                                        <option value="<?= $cat->id ?>" <?= $category && $category->parentId === $cat->id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat->name) ?>
                                        </option>
                                    <?php elseif (!$category): ?>
                                        <option value="<?= $cat->id ?>">
                                            <?= htmlspecialchars($cat->name) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" name="save_category"
                                class="bg-green-600 hover:bg-green-500 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Save Category
                        </button>
                        <a href="?tab=categories"
                           class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>

            <?php elseif ($activeTab === 'users' && $user->role === 'owner'): ?>
                <?php
                $editUser = null;
                if ($action === 'edit' && isset($id)) {
                    $editUser = $users[$id] ?? null;
                }
                ?>
                <h2 class="text-2xl font-bold mb-4"><?= $action === 'add' ? 'Add' : 'Edit' ?> User</h2>
                <form method="POST" class="space-y-4">
                    <?php if ($editUser): ?>
                        <input type="hidden" name="user_id" value="<?= $editUser->id ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 mb-1">Username</label>
                            <input type="text" name="username" required
                                   value="<?= $editUser ? htmlspecialchars($editUser->username) : '' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Email</label>
                            <input type="email" name="email" required
                                   value="<?= $editUser ? htmlspecialchars($editUser->email) : '' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Password <?= $editUser ? '(leave blank to keep current)' : '' ?></label>
                            <input type="password" name="password" <?= $editUser ? '' : 'required' ?>
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Role</label>
                            <select name="role" required
                                    class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                                <option value="user" <?= $editUser && $editUser->role === 'user' ? 'selected' : '' ?>>
                                    User
                                </option>
                                <option value="admin" <?= $editUser && $editUser->role === 'admin' ? 'selected' : '' ?>>
                                    Admin
                                </option>
                                <?php if ($user->role === 'owner'): ?>
                                    <option value="owner" <?= $editUser && $editUser->role === 'owner' ? 'selected' : '' ?>>
                                        Owner
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Phone Number</label>
                            <input type="text" name="phone_number"
                                   value="<?= $editUser ? htmlspecialchars($editUser->phoneNumber ?? '') : '' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1">Address</label>
                            <input type="text" name="address"
                                   value="<?= $editUser ? htmlspecialchars($editUser->address ?? '') : '' ?>"
                                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white">
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" name="save_user"
                                class="bg-green-600 hover:bg-green-500 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Save User
                        </button>
                        <a href="?tab=users"
                           class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <?php if ($activeTab === 'products'): ?>
            <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
                <div class="grid grid-cols-7 bg-gray-700 text-left font-semibold">
                    <div class="px-4 py-3">ID</div>
                    <div class="px-4 py-3">Name</div>
                    <div class="px-4 py-3">Brand</div>
                    <div class="px-4 py-3">Price</div>
                    <div class="px-4 py-3">Stock</div>
                    <div class="px-4 py-3">Category</div>
                    <div class="px-4 py-3">Action</div>
                </div>

                <?php foreach ($products as $product): ?>
                    <div class="grid grid-cols-7 border-t border-gray-700">
                        <div class="px-4 py-3"><?= $product->id ?></div>
                        <div class="px-4 py-3"><?= htmlspecialchars($product->name) ?></div>
                        <div class="px-4 py-3"><?= htmlspecialchars($product->brand) ?></div>
                        <div class="px-4 py-3"><?= htmlspecialchars($product->price) ?>﹩</div>
                        <div class="px-4 py-3"><?= $product->stock ?></div>
                        <div class="px-4 py-3">
                            <?= isset($categories[$product->categoryId]) ? htmlspecialchars($categories[$product->categoryId]->name) : 'N/A' ?>
                        </div>
                        <div class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="?tab=products&action=edit&id=<?= $product->id ?>"
                                   class="text-blue-400 hover:text-blue-300">Edit</a>
                                <form method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                    <button type="submit" name="delete_product" class="text-red-400 hover:text-red-300">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($activeTab === 'categories'): ?>
            <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
                <div class="grid grid-cols-5 bg-gray-700 text-left font-semibold">
                    <div class="px-4 py-3">ID</div>
                    <div class="px-4 py-3">Name</div>
                    <div class="px-4 py-3">Parent</div>
                    <div class="px-4 py-3">Child Categories</div>
                    <div class="px-4 py-3">Action</div>
                </div>

                <?php foreach ($categories as $category): ?>
                    <div class="grid grid-cols-5 border-t border-gray-700">
                        <div class="px-4 py-3"><?= $category->id ?></div>
                        <div class="px-4 py-3"><?= htmlspecialchars($category->name) ?></div>
                        <div class="px-4 py-3">
                            <?= $category->parentId !== null && isset($categories[$category->parentId])
                                ? htmlspecialchars($categories[$category->parentId]->name)
                                : 'None' ?>
                        </div>
                        <div class="px-4 py-3">
                            <?php
                            if (!empty($category->childrenIds)) {
                                $childrenNames = [];
                                foreach ($category->childrenIds as $childId) {
                                    if (isset($categories[$childId])) {
                                        $childrenNames[] = $categories[$childId]->name;
                                    }
                                }
                                echo htmlspecialchars(implode(', ', $childrenNames));
                            } else {
                                echo 'None';
                            }
                            ?>
                        </div>
                        <div class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="?tab=categories&action=edit&id=<?= $category->id ?>"
                                   class="text-blue-400 hover:text-blue-300">Edit</a>
                                <form method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    <input type="hidden" name="category_id" value="<?= $category->id ?>">
                                    <button type="submit" name="delete_category"
                                            class="text-red-400 hover:text-red-300">Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($activeTab === 'users' && $user->role == "owner"): ?>
            <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
                <div class="grid grid-cols-6 bg-gray-700 text-left font-semibold">
                    <div class="px-4 py-3">ID</div>
                    <div class="px-4 py-3">Username</div>
                    <div class="px-4 py-3">Email</div>
                    <div class="px-4 py-3">Role</div>
                    <div class="px-4 py-3">Phone</div>
                    <div class="px-4 py-3">Action</div>
                </div>
                <?php foreach ($users as $userItem): ?>
                    <div class="grid grid-cols-6 border-t border-gray-700">
                        <div class="px-4 py-3"><?= substr($userItem->id, 0, 8) ?>...</div>
                        <div class="px-4 py-3"><?= htmlspecialchars($userItem->username) ?></div>
                        <div class="px-4 py-3"><?= htmlspecialchars($userItem->email) ?></div>
                        <div class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                        <?php
                                if ($userItem->role === 'admin') echo 'bg-purple-900 text-purple-200';
                                elseif ($userItem->role === 'owner') echo 'bg-red-900 text-red-200';
                                else echo 'bg-gray-700 text-gray-300';
                                ?>">
                                    <?= ucfirst(htmlspecialchars($userItem->role)) ?>
                                </span>
                        </div>
                        <div class="px-4 py-3"><?= $userItem->phoneNumber ? htmlspecialchars($userItem->phoneNumber) : 'N/A' ?></div>
                        <div class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="?tab=users&action=edit&id=<?= $userItem->id ?>"
                                   class="text-blue-400 hover:text-blue-300">Edit</a>
                                <?php if ($userItem->id !== $user->id && $userItem->role !== 'owner'): ?>
                                    <form method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <input type="hidden" name="user_id" value="<?= $userItem->id ?>">
                                        <button type="submit" name="delete_user"
                                                class="text-red-400 hover:text-red-300">Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once './App/Views/Footer.php'; ?>
</body>

</html>