<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>

<body class="bg-gray-900 min-h-screen flex flex-col">
<?php (new \App\Controllers\HeaderController())->handle(); ?>

<div class="flex-grow flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-gray-800 rounded-lg shadow-lg p-8 border border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Login</h2>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-4 bg-red-500 bg-opacity-10 border border-red-500 rounded-lg">
                <?php foreach ($errors as $error): ?>
                    <p class="text-red-500"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/product-catalog/login" class="space-y-6">
            <div>
                <label for="username" class="block text-gray-300 mb-2">Username</label>
                <input type="text"
                       id="username"
                       name="username"
                       required
                       class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label for="password" class="block text-gray-300 mb-2">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                Sign In
            </button>

            <p class="text-center text-gray-400">
                Don't have an account?
                <a href="/product-catalog/register" class="text-blue-500 hover:text-blue-400">Register</a>
            </p>
        </form>
    </div>
</div>

<?php require_once './App/Views/Footer.php'; ?>
</body>

</html>