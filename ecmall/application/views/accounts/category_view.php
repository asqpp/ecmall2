<div class="container-fluid px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="<?php echo base_url('accounts/categories'); ?>" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900"><?php echo $category['name']; ?></h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?php echo $category['color']; ?>-100 text-<?php echo $category['color']; ?>-800">
                        <?php echo ucfirst($category['type']); ?>
                    </span>
                </div>
                <p class="text-gray-600">Manage <?php echo strtolower($category['type']); ?> accounts in this category</p>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo base_url('accounts/add'); ?>" class="inline-flex items-center px-4 py-2 bg-<?php echo $category['color']; ?>-600 hover:bg-<?php echo $category['color']; ?>-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Account
                </a>
                <?php if (count($accounts) < count($category['accounts'])): ?>
                <a href="<?php echo base_url('accounts/initialize_category/' . urlencode($category['name'])); ?>"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                   onclick="return confirm('This will create all missing default accounts. Continue?');">
                    <i class="fas fa-magic mr-2"></i>
                    Initialize Defaults
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Category Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-<?php echo $category['color']; ?>-500 to-<?php echo $category['color']; ?>-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-<?php echo $category['color']; ?>-100 text-sm">Total Accounts</p>
                    <p class="text-3xl font-bold mt-2"><?php echo count($accounts); ?></p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-list text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Debit Balance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">
                        <?php
                        $total_debit = 0;
                        foreach ($accounts as $account) {
                            if ($account->balance > 0) {
                                $total_debit += $account->balance;
                            }
                        }
                        echo '₹ ' . number_format($total_debit, 2);
                        ?>
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Credit Balance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">
                        <?php
                        $total_credit = 0;
                        foreach ($accounts as $account) {
                            if ($account->balance < 0) {
                                $total_credit += abs($account->balance);
                            }
                        }
                        echo '₹ ' . number_format($total_credit, 2);
                        ?>
                    </p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-<?php echo $category['color']; ?>-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Net Balance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">
                        <?php echo '₹ ' . number_format($total_debit - $total_credit, 2); ?>
                    </p>
                </div>
                <div class="bg-<?php echo $category['color']; ?>-100 rounded-full p-3">
                    <i class="fas fa-balance-scale text-<?php echo $category['color']; ?>-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Default Accounts Info -->
    <div class="bg-<?php echo $category['color']; ?>-50 border border-<?php echo $category['color']; ?>-200 rounded-lg p-6 mb-8">
        <h3 class="font-semibold text-<?php echo $category['color']; ?>-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Default Accounts for <?php echo $category['name']; ?>
        </h3>
        <div class="flex flex-wrap gap-2">
            <?php foreach ($category['accounts'] as $account_name): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?php echo $category['color']; ?>-100 text-<?php echo $category['color']; ?>-800">
                <?php echo $account_name; ?>
            </span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-<?php echo $category['color']; ?>-500 border-b">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-table mr-2"></i>All Accounts in <?php echo $category['name']; ?>
            </h3>
        </div>

        <?php if (empty($accounts)): ?>
        <div class="text-center py-16">
            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Accounts Found</h3>
            <p class="text-gray-600 mb-6">There are no accounts in this category yet.</p>
            <a href="<?php echo base_url('accounts/add'); ?>" class="inline-flex items-center px-6 py-3 bg-<?php echo $category['color']; ?>-600 hover:bg-<?php echo $category['color']; ?>-700 text-white rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Create First Account
            </a>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Account Code
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Account Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Balance
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($accounts as $account): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-semibold text-<?php echo $category['color']; ?>-600">
                                <?php echo $account->account_code; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                <?php echo $account->account_name; ?>
                            </div>
                            <?php if (!empty($account->description)): ?>
                            <div class="text-sm text-gray-500">
                                <?php echo $account->description; ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?php echo $category['color']; ?>-100 text-<?php echo $category['color']; ?>-800">
                                <?php echo ucfirst($account->account_type); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-semibold <?php echo $account->balance >= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo '₹ ' . number_format(abs($account->balance), 2); ?>
                                <?php if ($account->balance >= 0): ?>
                                    <span class="text-xs text-gray-500">Dr</span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-500">Cr</span>
                                <?php endif; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?php echo base_url('accounts/view/' . $account->account_id); ?>"
                                   class="text-blue-600 hover:text-blue-900"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo base_url('accounts/ledger/' . $account->account_id); ?>"
                                   class="text-purple-600 hover:text-purple-900"
                                   title="View Ledger">
                                    <i class="fas fa-book"></i>
                                </a>
                                <?php if (!($account->is_system ?? false)): ?>
                                <a href="<?php echo base_url('accounts/edit/' . $account->account_id); ?>"
                                   class="text-green-600 hover:text-green-900"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo base_url('accounts/delete/' . $account->account_id); ?>"
                                   class="text-red-600 hover:text-red-900"
                                   title="Delete"
                                   onclick="return confirm('Are you sure you want to delete this account?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php else: ?>
                                <span class="text-gray-400" title="System Account">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-sm font-semibold text-gray-900 text-right">
                            Total:
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                            <?php echo '₹ ' . number_format($total_debit - $total_credit, 2); ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
