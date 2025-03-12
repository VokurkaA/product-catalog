<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= htmlspecialchars($product->name) ?></title>
</head>

<body class="bg-gray-900 min-h-screen flex flex-col pt-24">
<?php (new \App\Controllers\HeaderController())->handle(); ?>

    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-gray-800/80 backdrop-blur rounded-xl shadow-2xl overflow-hidden border border-gray-700/50">
            <div class="p-8">
                <nav class="mb-6 text-gray-400 flex flex-row-reverse w-fit items-center">
                    <span class="text-gray-200 font-medium"><?= htmlspecialchars($product->name) ?></span>
                    <span class="mx-2 text-gray-600">/</span>
                    <?php foreach ($subCategoryIds as $id): ?>
                        <a href="/product-catalog?category=<?= $id ?>" 
                           class="text-gray-300 hover:text-gray-100 transition-colors">
                            <?= htmlspecialchars($categories[$id]->name) ?>
                        </a>
                        <span class="mx-2 text-gray-600">/</span>
                    <?php endforeach; ?>
                    <a href="/product-catalog" class="text-blue-400 hover:text-blue-300 transition-colors">Home</a>
                </nav>

                <div class="flex flex-col md:flex-row gap-12">
                    <div class="w-full md:w-1/2">
                        <div class="relative bg-gray-700/50 backdrop-blur rounded-xl aspect-square flex items-center justify-center group transition-all hover:bg-gray-700/70">
                            <?php if ($user): ?>
                                <form method="POST" class="absolute top-4 right-4">
                                    <button type="submit" name="like" class="transform hover:scale-110 transition-transform">
                                        <?php if ($isLiked): ?>
                                            <svg class="h-8 drop-shadow-glow-red" viewBox="0 -960 960 960" fill="red">
                                                <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z" />
                                            </svg>
                                        <?php else: ?>
                                            <svg class="h-8 hover:fill-red-500 transition-colors duration-300" viewBox="0 -960 960 960" fill="gray">
                                                <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                                            </svg>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                            <img class="h-full w-full rounded-xl" src="<?= '../placeholder.webp' ?>" alt="<?= htmlspecialchars($product->name) ?>">
                        </div>
                    </div>

                    <div class="w-full md:w-1/2">
                        <h1 class="text-3xl font-bold text-white mb-4"><?= htmlspecialchars($product->name) ?></h1>
                        <p class="text-gray-300 mb-4"><?= htmlspecialchars($product->description) ?></p>

                        <div class="mb-4">
                            <span class="text-gray-400">Brand:</span>
                            <a href="/product-catalog?search=<?= urlencode($product->brand) ?>" class="text-gray-200 ml-2 hover:text-blue-400"><?= htmlspecialchars($product->brand) ?></a>
                        </div>

                        <div class="flex items-center mb-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1 text-gray-300">
                                    <?= number_format(array_sum($product->rating) / count($product->rating), 1) ?>
                                </span>
                            </div>
                            <span class="mx-2 text-gray-600">•</span>
                            <span class="text-gray-400"><?= count($product->rating) ?> ratings</span>
                        </div>

                        <div class="text-3xl font-bold text-green-400 mb-6">
                            <?= htmlspecialchars($product->price) ?>﹩
                        </div>

                        <form method="POST" class="w-full">
                            <button type="submit"
                                name="add_to_cart"
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once './App/Views/Footer.php'; ?>
</body>

</html>