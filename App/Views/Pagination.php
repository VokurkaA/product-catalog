<?php if ($totalPages > 1): ?>
    <div class="flex justify-center space-x-2 mt-8 mb-8">
        <?php
        $params = $_GET;
        unset($params['page']);
        $queryString = http_build_query($params);
        $queryString = $queryString ? '&' . $queryString : '';

        // Calculate which page numbers to show
        $pagesToShow = [];
        $pagesToShow[] = 1; // Always show first page

        for ($i = max(2, $currentPage - 2); $i <= min($currentPage + 2, $totalPages - 1); $i++) {
            $pagesToShow[] = $i;
        }

        $pagesToShow[] = $totalPages; // Always show last page
        $pagesToShow = array_unique($pagesToShow);
        sort($pagesToShow);
        ?>

        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= ($currentPage - 1) . $queryString ?>"
               class="px-4 py-2 bg-gray-800 text-gray-100 rounded-lg hover:bg-gray-700 transition-colors">
                Previous
            </a>
        <?php endif; ?>

        <?php
        $prevPage = 0;
        foreach ($pagesToShow as $i):
            if ($i - $prevPage > 1): ?>
                <span class="px-4 py-2 text-gray-500">...</span>
            <?php endif; ?>

            <a href="?page=<?= $i . $queryString ?>"
               class="px-4 py-2 rounded-lg transition-colors <?= $i === $currentPage ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-100 hover:bg-gray-700' ?>">
                <?= $i ?>
            </a>

            <?php
            $prevPage = $i;
        endforeach; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= ($currentPage + 1) . $queryString ?>"
               class="px-4 py-2 bg-gray-800 text-gray-100 rounded-lg hover:bg-gray-700 transition-colors">
                Next
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>