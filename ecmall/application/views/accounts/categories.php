<div class="container-fluid px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Account Categories</h1>
                <p class="text-gray-600 mt-2">Manage your chart of accounts organized by categories</p>
            </div>
            <a href="<?php echo base_url('accounts'); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <i class="fas fa-list mr-2"></i>
                View All Accounts
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <?php
        $total_accounts = 0;
        foreach ($statistics as $stat):
            $total_accounts += $stat['count'];
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-<?php echo $stat['color']; ?>-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600"><?php echo $stat['name']; ?></p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo $stat['count']; ?></p>
                </div>
                <div class="bg-<?php echo $stat['color']; ?>-100 rounded-full p-3">
                    <i class="fas fa-folder text-<?php echo $stat['color']; ?>-600 text-xl"></i>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Total Summary -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100">Total Accounts Across All Categories</p>
                <p class="text-4xl font-bold mt-2"><?php echo $total_accounts; ?></p>
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <i class="fas fa-chart-pie text-4xl"></i>
            </div>
        </div>
    </div>

    <!-- Category Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php foreach ($categories as $category): ?>
        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <!-- Category Header -->
            <div class="bg-<?php echo $category['color']; ?>-500 text-white rounded-t-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold"><?php echo $category['name']; ?></h3>
                        <p class="text-<?php echo $category['color']; ?>-100 mt-1">
                            <?php echo $category['total_accounts']; ?> accounts • Type: <?php echo ucfirst($category['type']); ?>
                        </p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-folder-open text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Category Content -->
            <div class="p-6">
                <!-- Default Accounts -->
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Default Accounts:</h4>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($category['accounts'] as $account_name): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-<?php echo $category['color']; ?>-100 text-<?php echo $category['color']; ?>-800">
                            <?php echo $account_name; ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Existing Accounts Preview -->
                <?php if (!empty($category['account_details'])): ?>
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Current Accounts (<?php echo count($category['account_details']); ?>):</h4>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        <?php
                        $preview_accounts = array_slice($category['account_details'], 0, 5);
                        foreach ($preview_accounts as $account):
                        ?>
                        <div class="flex items-center justify-between text-sm bg-gray-50 rounded p-2">
                            <span class="text-gray-700">
                                <span class="font-mono text-<?php echo $category['color']; ?>-600"><?php echo $account->account_code; ?></span>
                                - <?php echo $account->account_name; ?>
                            </span>
                        </div>
                        <?php endforeach; ?>

                        <?php if (count($category['account_details']) > 5): ?>
                        <p class="text-xs text-gray-500 text-center pt-2">
                            + <?php echo count($category['account_details']) - 5; ?> more accounts
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="mb-4 text-center py-4 bg-gray-50 rounded">
                    <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600">No accounts created yet</p>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6">
                    <a href="<?php echo base_url('accounts/category/' . urlencode($category['name'])); ?>"
                       class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-<?php echo $category['color']; ?>-600 hover:bg-<?php echo $category['color']; ?>-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        View All
                    </a>

                    <?php if (count($category['account_details']) < count($category['accounts'])): ?>
                    <a href="<?php echo base_url('accounts/initialize_category/' . urlencode($category['name'])); ?>"
                       class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                       onclick="return confirm('This will create all missing default accounts for this category. Continue?');">
                        <i class="fas fa-magic mr-2"></i>
                        Initialize
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Help Section -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start gap-4">
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-blue-900 mb-2">About Account Categories</h4>
                <p class="text-blue-800 text-sm mb-3">
                    Account categories help organize your chart of accounts into logical groups. Each category represents a different type of account in your double-entry accounting system.
                </p>
                <ul class="text-blue-800 text-sm space-y-1">
                    <li><i class="fas fa-check-circle text-blue-600 mr-2"></i><strong>Assets:</strong> Resources owned by the business (Current & Fixed)</li>
                    <li><i class="fas fa-check-circle text-blue-600 mr-2"></i><strong>Liabilities:</strong> Amounts owed to others (Current & Long Term)</li>
                    <li><i class="fas fa-check-circle text-blue-600 mr-2"></i><strong>Equity/Capital:</strong> Owner's stake in the business</li>
                    <li><i class="fas fa-check-circle text-blue-600 mr-2"></i><strong>Income:</strong> Revenue earned from business activities</li>
                    <li><i class="fas fa-check-circle text-blue-600 mr-2"></i><strong>Expenses:</strong> Costs incurred in running the business (Direct & Indirect)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
