<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Home</title>
</head>

<body class="bg-gray-900 min-h-screen px-8 pt-24">
    <?php (new \App\Controllers\HeaderController())->handle(); ?>
    <div class="container mx-auto">
        <!-- Sort & Search Section -->
        <div class="block md:flex md:justify-between md:items-center mb-8 space-y-4 md:space-y-0 md:space-x-4">
            <!-- Sort Dropdown -->
            <form method="GET" action="">
                <!-- Keep existing hidden fields -->
                <?php foreach ($_GET as $key => $value): ?>
                    <?php if ($key !== 'sort'): ?>
                        <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>

                <select name="sort"
                    onchange="this.form.submit()"
                    class="w-full md:w-auto bg-gray-800 text-gray-100 border border-gray-700 rounded-lg 
                               px-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 h-14
                               transition-colors duration-200 hover:bg-gray-700">
                    <option value="default" <?= isset($_GET['sort']) && $_GET['sort'] == 'default' ? 'selected' : '' ?>>Sort by...</option>
                    <option value="name_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                    <option value="name_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                    <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : '' ?>>Price (Low to High)</option>
                    <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : '' ?>>Price (High to Low)</option>
                    <option value="rating_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'rating_desc' ? 'selected' : '' ?>>Rating (Highest)</option>
                </select>
            </form>

            <!-- Search Form -->
            <form method="GET" action="" class="flex items-center space-x-3 flex-grow">
                <?php foreach ($_GET as $key => $value): ?>
                    <?php if ($key !== 'search'): ?>
                        <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="flex-grow relative group">
                    <input type="text"
                        name="search"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                        placeholder="Search products..."
                        class="w-full p-4 pl-12 rounded-lg bg-gray-800 border border-gray-700 
                                  text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 
                                  focus:ring-blue-500 focus:border-transparent transition-all
                                  group-hover:bg-gray-700">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-500
                              group-hover:text-gray-400 transition-colors"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-4 px-6 
                               rounded-lg transition-all duration-200 shadow-md hover:shadow-lg 
                               hover:scale-105 active:scale-95">
                    Search
                </button>
            </form>
        </div>

        <!-- Category Navigation -->
        <div>
            <nav class="mb-4 select-none flex flex-row-reverse w-fit items-center space-x-2 text-gray-400
                        bg-gray-800/50 px-4 py-2 rounded-lg backdrop-blur">
                <?php
                $params = $_GET;
                unset($params['category']);
                $queryString = http_build_query($params);
                $queryString = $queryString ? '&' . $queryString : '';
                ?>

                <?php foreach ($idHierarchy as $c): ?>
                    <a href="?category=<?= $c . $queryString ?>"
                        class="font-medium text-blue-400 hover:text-blue-300 transition-colors">
                        <?= htmlspecialchars($categories[$c]->name, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                    <span class="mx-2 text-gray-600">/</span>
                <?php endforeach; ?>
                <a href="?<?= substr($queryString, 1) ?>"
                    class="text-blue-400 hover:text-blue-300 transition-colors">
                    Home
                </a>
            </nav>

            <!-- Category Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                <?php if (empty($_GET['category'])): ?>
                    <?php foreach ($categories as $c): ?>
                        <?php if ($c->parentId == null): ?>
                            <a href="?category=<?= $c->id . $queryString ?>"
                                class="w-full bg-gray-800 rounded-lg p-6 border border-gray-700 
                                      hover:shadow-lg transition-all duration-200 hover:scale-105
                                      hover:bg-gray-700/80 group">
                                <h3 class="text-lg font-semibold text-gray-100 
                                         group-hover:text-white transition-colors">
                                    <?= $c->name ?>
                                </h3>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php foreach ($categories[$_GET['category']]->childrenIds as $c): ?>
                        <a href="?category=<?= $c . $queryString ?>" class="w-full bg-gray-800 rounded-lg p-4 border border-gray-700 hover:shadow-lg transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-100"><?= $categories[$c]->name ?></h3>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($selectedProducts as $p) : ?>
                <a href="product/<?= $p->id ?>" class="group bg-gray-800 rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-700 block hover:scale-[1.02]">
                    <!-- Product Image -->
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img
                            src="placeholder.webp"
                            alt="<?= htmlspecialchars($p->name) ?>"
                            class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <!-- Product Details -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-xl font-semibold text-gray-100 line-clamp-2"><?= $p->name ?></h2>
                            <p class="text-lg font-bold text-green-400 ml-2 whitespace-nowrap"><?= $p->price ?>﹩</p>
                        </div>

                        <p class="text-gray-400 text-sm mb-3"><?= $p->brand ?></p>
                        <p class="text-gray-300 mb-4 line-clamp-2 text-sm"><?= $p->description ?></p>

                        <div class="flex items-center mt-auto">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="ml-1 text-gray-300"><?= number_format(array_sum($p->rating) / count($p->rating), 1) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php require_once './App/Views/Pagination.php'; ?>
    </div>
    <?php require_once './App/Views/Footer.php'; ?>
</body>

</html>