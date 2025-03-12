<header class="bg-gray-800 shadow-lg fixed left-0 top-0 w-full h-20 z-50 rounded-b-2xl">
    <div class="relative">
        <div class="container mx-auto px-8 py-5">
            <nav class="flex items-center justify-between">
                <div class="flex items-center">
                    <a class="absolute left-5" title="profile" href="/product-catalog/profile">
                        <svg class="h-8 w-8 text-white mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                    <a href="/product-catalog" class="text-3xl font-bold text-white hover:text-gray-200 transition duration-300">
                        Product Catalog
                    </a>
                </div>
                <?php if ($user && ($user->role === 'admin' || $user->role === 'owner')) : ?>
                    <a class="flex items-center space-x-2 text-white hover:text-gray-200 transition duration-300" href="/product-catalog/admin">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="M680-280q25 0 42.5-17.5T740-340q0-25-17.5-42.5T680-400q-25 0-42.5 17.5T620-340q0 25 17.5 42.5T680-280Zm0 120q31 0 57-14.5t42-38.5q-22-13-47-20t-52-7q-27 0-52 7t-47 20q16 24 42 38.5t57 14.5ZM480-80q-139-35-229.5-159.5T160-516v-244l320-120 320 120v227q-19-8-39-14.5t-41-9.5v-147l-240-90-240 90v188q0 47 12.5 94t35 89.5Q310-290 342-254t71 60q11 32 29 61t41 52q-1 0-1.5.5t-1.5.5Zm200 0q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80ZM480-494Z" />
                        </svg>
                        <span class="text-lg font-medium">Admin Panel</span>
                    </a>
                <?php endif; ?>
                <?php if ($user) : ?>
                    <a class="relative group" title="cart" href="/product-catalog/cart">
                        <svg class="h-8 w-8 text-white hover:text-gray-200 transition duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <?php if (count($user->liked) > 0) : ?>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                <?= count($user->cart) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>