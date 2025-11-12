<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: $persist(false) }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Insurance ERP</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/output.css'); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css">

    <!-- Additional CSS -->
    <?php if(isset($additional_css)): ?>
        <?php foreach($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slideDown {
            animation: slideDown 0.3s ease-out;
        }

        .nav-dropdown {
            max-height: 600px;
            overflow-y: auto;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased" x-data="{
    activeMenu: null,
    mobileMenuOpen: false,
    toggleMenu(menu) {
        this.activeMenu = this.activeMenu === menu ? null : menu;
    }
}">

    <!-- Top Navbar -->
    <div class="fixed top-0 left-0 right-0 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 shadow-lg z-50">
        <div class="flex items-center justify-between px-4 h-16">
            <!-- Left Section -->
            <div class="flex items-center gap-4">
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden text-white hover:bg-white/20 p-2 rounded-lg transition-all"
                >
                    <i :class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-xl"></i>
                </button>
                <div class="flex items-center gap-3">
                    <div class="bg-white p-2 rounded-lg shadow-md">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-xl">Insurance ERP</h1>
                        <p class="text-blue-100 text-xs">NA-FIX Solutions</p>
                    </div>
                </div>
            </div>

            <!-- Center - Search -->
            <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="text"
                        placeholder="Search customers, policies, invoices..."
                        class="w-full pl-10 pr-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-blue-200 focus:bg-white focus:text-gray-900 focus:placeholder-gray-400 transition-all outline-none"
                    />
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center gap-3">
                <!-- Notifications -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="relative text-white hover:bg-white/20 p-2 rounded-lg transition-all">
                        <i class="fas fa-bell text-xl"></i>
                        <?php
                        // Get unread notification count
                        $this->load->model('Notification_model');
                        $user_id = $this->session->userdata('user_id');
                        $unread_count = $this->Notification_model->count_unread($user_id);
                        if($unread_count > 0):
                        ?>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                            <?php echo $unread_count > 9 ? '9+' : $unread_count; ?>
                        </span>
                        <?php endif; ?>
                    </button>

                    <!-- Notification Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-2xl py-2 max-h-96 overflow-y-auto">
                        <div class="px-4 py-2 border-b">
                            <h3 class="font-semibold text-gray-900">Notifications</h3>
                        </div>
                        <?php
                        $notifications = $this->Notification_model->get_unread($user_id, 5);
                        if(count($notifications) > 0):
                            foreach($notifications as $notif):
                        ?>
                        <a href="<?php echo $notif->link ? base_url($notif->link) : '#'; ?>"
                           class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-<?php echo $notif->icon ?? 'info-circle'; ?> text-<?php echo $notif->type == 'success' ? 'green' : ($notif->type == 'warning' ? 'yellow' : ($notif->type == 'danger' ? 'red' : 'blue')); ?>-500 mt-1"></i>
                                <div class="flex-1">
                                    <p class="font-medium text-sm text-gray-900"><?php echo $notif->title; ?></p>
                                    <p class="text-xs text-gray-600 mt-1"><?php echo $notif->message; ?></p>
                                    <p class="text-xs text-gray-400 mt-1"><?php echo timespan(strtotime($notif->created_at), time()) . ' ago'; ?></p>
                                </div>
                            </div>
                        </a>
                        <?php
                            endforeach;
                        else:
                        ?>
                        <div class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-bell-slash text-3xl mb-2"></i>
                            <p>No new notifications</p>
                        </div>
                        <?php endif; ?>
                        <a href="<?php echo base_url('notifications'); ?>" class="block px-4 py-2 text-center text-sm text-blue-600 hover:bg-blue-50">
                            View all notifications
                        </a>
                    </div>
                </div>

                <!-- User Menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 bg-white/10 px-3 py-2 rounded-lg hover:bg-white/20 transition-all">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            <?php echo strtoupper(substr($this->session->userdata('username') ?? 'A', 0, 1)); ?>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-white text-sm font-medium"><?php echo $this->session->userdata('username') ?? 'Admin'; ?></p>
                            <p class="text-blue-200 text-xs"><?php echo $this->session->userdata('usertype') ?? 'Administrator'; ?></p>
                        </div>
                        <i class="fas fa-chevron-down text-white text-xs"></i>
                    </button>

                    <!-- User Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl py-1">
                        <a href="<?php echo base_url('profile'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user w-5"></i> My Profile
                        </a>
                        <a href="<?php echo base_url('settings'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog w-5"></i> Settings
                        </a>
                        <hr class="my-1">
                        <a href="<?php echo base_url('login/logout'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt w-5"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="bg-blue-800/50 border-t border-white/10">
            <div class="flex items-center gap-1 px-4 py-2 overflow-x-auto">
                <!-- Dashboard -->
                <button @click="toggleMenu('dashboard')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                        :class="activeMenu === 'dashboard' ? 'bg-white text-blue-700 shadow-md' : 'text-white hover:bg-white/10'">
                    <i class="fas fa-home"></i>
                    <span class="font-medium text-sm">Dashboard</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="activeMenu === 'dashboard' && 'rotate-180'"></i>
                </button>

                <!-- Masters -->
                <button @click="toggleMenu('masters')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                        :class="activeMenu === 'masters' ? 'bg-white text-blue-700 shadow-md' : 'text-white hover:bg-white/10'">
                    <i class="fas fa-database"></i>
                    <span class="font-medium text-sm">Masters</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="activeMenu === 'masters' && 'rotate-180'"></i>
                </button>

                <!-- Insurance -->
                <button @click="toggleMenu('insurance')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                        :class="activeMenu === 'insurance' ? 'bg-white text-blue-700 shadow-md' : 'text-white hover:bg-white/10'">
                    <i class="fas fa-shield-alt"></i>
                    <span class="font-medium text-sm">Insurance</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="activeMenu === 'insurance' && 'rotate-180'"></i>
                </button>

                <!-- Accounting -->
                <button @click="toggleMenu('accounting')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                        :class="activeMenu === 'accounting' ? 'bg-white text-blue-700 shadow-md' : 'text-white hover:bg-white/10'">
                    <i class="fas fa-book"></i>
                    <span class="font-medium text-sm">Accounting</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="activeMenu === 'accounting' && 'rotate-180'"></i>
                </button>

                <!-- Transactions -->
                <button @click="toggleMenu('transactions')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                        :class="activeMenu === 'transactions' ? 'bg-white text-blue-700 shadow-md' : 'text-white hover:bg-white/10'">
                    <i class="fas fa-exchange-alt"></i>
                    <span class="font-medium text-sm">Transactions</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="activeMenu === 'transactions' && 'rotate-180'"></i>
                </button>

                <!-- HR & Payroll -->
                <button @click="toggleMenu('hr')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                        :class="activeMenu === 'hr' ? 'bg-white text-blue-700 shadow-md' : 'text-white hover:bg-white/10'">
                    <i class="fas fa-users"></i>
                    <span class="font-medium text-sm">HR & Payroll</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="activeMenu === 'hr' && 'rotate-180'"></i>
                </button>

                <!-- Reports -->
                <button @click="toggleMenu('reports')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                        :class="activeMenu === 'reports' ? 'bg-white text-blue-700 shadow-md' : 'text-white hover:bg-white/10'">
                    <i class="fas fa-chart-bar"></i>
                    <span class="font-medium text-sm">Reports</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="activeMenu === 'reports' && 'rotate-180'"></i>
                </button>

                <!-- Settings -->
                <a href="<?php echo base_url('settings'); ?>"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap text-white hover:bg-white/10">
                    <i class="fas fa-cog"></i>
                    <span class="font-medium text-sm">Settings</span>
                </a>
            </div>
        </div>

        <!-- Dashboard Dropdown -->
        <div x-show="activeMenu === 'dashboard'" @click.away="activeMenu = null"
             class="absolute left-0 right-0 bg-white shadow-2xl border-t border-gray-200 animate-slideDown nav-dropdown">
            <div class="max-w-7xl mx-auto p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="<?php echo base_url('dashboard'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 transition-all group">
                        <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-600 transition-all">
                            <i class="fas fa-home text-blue-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-blue-600">Overview</span>
                    </a>
                    <a href="<?php echo base_url('dashboard/company'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 transition-all group">
                        <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-600 transition-all">
                            <i class="fas fa-building text-blue-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-blue-600">Company Dashboard</span>
                    </a>
                    <a href="<?php echo base_url('dashboard/branch'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 transition-all group">
                        <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-600 transition-all">
                            <i class="fas fa-code-branch text-blue-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-blue-600">Branch Dashboard</span>
                    </a>
                    <a href="<?php echo base_url('notifications'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 transition-all group">
                        <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-600 transition-all">
                            <i class="fas fa-bell text-blue-600 group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <span class="font-medium text-gray-700 group-hover:text-blue-600">Notifications</span>
                            <?php if($unread_count > 0): ?>
                            <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full"><?php echo $unread_count; ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Masters Dropdown -->
        <div x-show="activeMenu === 'masters'" @click.away="activeMenu = null"
             class="absolute left-0 right-0 bg-white shadow-2xl border-t border-gray-200 animate-slideDown nav-dropdown">
            <div class="max-w-7xl mx-auto p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="<?php echo base_url('customers'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-purple-50 transition-all group">
                        <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-600 transition-all">
                            <i class="fas fa-user-circle text-purple-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-purple-600">Customer Management</span>
                    </a>
                    <a href="<?php echo base_url('agents'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-purple-50 transition-all group">
                        <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-600 transition-all">
                            <i class="fas fa-user-tie text-purple-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-purple-600">Agent Management</span>
                    </a>
                    <a href="<?php echo base_url('brokers'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-purple-50 transition-all group">
                        <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-600 transition-all">
                            <i class="fas fa-handshake text-purple-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-purple-600">Broker Management</span>
                    </a>
                    <a href="<?php echo base_url('suppliers'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-purple-50 transition-all group">
                        <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-600 transition-all">
                            <i class="fas fa-truck text-purple-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-purple-600">Supplier Management</span>
                    </a>
                    <a href="<?php echo base_url('hr/employees'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-purple-50 transition-all group">
                        <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-600 transition-all">
                            <i class="fas fa-users text-purple-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-purple-600">Staff Management</span>
                    </a>
                    <a href="<?php echo base_url('products'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-purple-50 transition-all group">
                        <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-600 transition-all">
                            <i class="fas fa-box text-purple-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-purple-600">Product Management</span>
                    </a>
                    <a href="<?php echo base_url('accounts'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-purple-50 transition-all group">
                        <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-600 transition-all">
                            <i class="fas fa-chart-pie text-purple-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-purple-600">Account Masters</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Insurance Dropdown -->
        <div x-show="activeMenu === 'insurance'" @click.away="activeMenu = null"
             class="absolute left-0 right-0 bg-white shadow-2xl border-t border-gray-200 animate-slideDown nav-dropdown">
            <div class="max-w-7xl mx-auto p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="<?php echo base_url('insurance/policies'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition-all group">
                        <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-600 transition-all">
                            <i class="fas fa-file-contract text-green-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-green-600">Policy Management</span>
                    </a>
                    <a href="<?php echo base_url('insurance/claims'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition-all group">
                        <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-600 transition-all">
                            <i class="fas fa-file-medical text-green-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-green-600">Claims Management</span>
                    </a>
                    <a href="<?php echo base_url('insurance/premium'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition-all group">
                        <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-600 transition-all">
                            <i class="fas fa-dollar-sign text-green-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-green-600">Premium Collection</span>
                    </a>
                    <a href="<?php echo base_url('insurance/commission'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition-all group">
                        <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-600 transition-all">
                            <i class="fas fa-wallet text-green-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-green-600">Commission Processing</span>
                    </a>
                    <a href="<?php echo base_url('insurance/renewals'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition-all group">
                        <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-600 transition-all">
                            <i class="fas fa-calendar-alt text-green-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-green-600">Renewals</span>
                    </a>
                    <a href="<?php echo base_url('insurance/endorsements'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition-all group">
                        <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-600 transition-all">
                            <i class="fas fa-file-signature text-green-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-green-600">Endorsements</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Accounting Dropdown -->
        <div x-show="activeMenu === 'accounting'" @click.away="activeMenu = null"
             class="absolute left-0 right-0 bg-white shadow-2xl border-t border-gray-200 animate-slideDown nav-dropdown">
            <div class="max-w-7xl mx-auto p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <a href="<?php echo base_url('accounts'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-chart-pie text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Chart of Accounts</span>
                    </a>
                    <a href="<?php echo base_url('accounts/categories'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-folder-tree text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Account Categories</span>
                    </a>
                    <a href="<?php echo base_url('accounting/journal'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-book text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Journal Entries</span>
                    </a>
                    <a href="<?php echo base_url('accounting/ledger'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-book-open text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">General Ledger</span>
                    </a>
                    <a href="<?php echo base_url('accounting/daybook'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-calendar-day text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Daybook</span>
                    </a>
                    <a href="<?php echo base_url('receipts'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-receipt text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Receipts</span>
                    </a>
                    <a href="<?php echo base_url('payments'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-credit-card text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Payments</span>
                    </a>
                    <a href="<?php echo base_url('accounting/bank-reconciliation'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-university text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Bank Reconciliation</span>
                    </a>
                    <a href="<?php echo base_url('reports/trial-balance'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-balance-scale text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Trial Balance</span>
                    </a>
                    <a href="<?php echo base_url('reports/balance-sheet'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-file-invoice text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Balance Sheet</span>
                    </a>
                    <a href="<?php echo base_url('accounting/credit-note'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-arrow-down text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Credit Note</span>
                    </a>
                    <a href="<?php echo base_url('accounting/debit-note'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-amber-50 transition-all group">
                        <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-600 transition-all">
                            <i class="fas fa-arrow-up text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-amber-600">Debit Note</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Transactions Dropdown -->
        <div x-show="activeMenu === 'transactions'" @click.away="activeMenu = null"
             class="absolute left-0 right-0 bg-white shadow-2xl border-t border-gray-200 animate-slideDown nav-dropdown">
            <div class="max-w-7xl mx-auto p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <a href="<?php echo base_url('sales'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-cyan-50 transition-all group">
                        <div class="p-2 bg-cyan-100 rounded-lg group-hover:bg-cyan-600 transition-all">
                            <i class="fas fa-file-invoice text-cyan-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-cyan-600">Sales Invoices</span>
                    </a>
                    <a href="<?php echo base_url('quotations'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-cyan-50 transition-all group">
                        <div class="p-2 bg-cyan-100 rounded-lg group-hover:bg-cyan-600 transition-all">
                            <i class="fas fa-file-alt text-cyan-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-cyan-600">Quotations</span>
                    </a>
                    <a href="<?php echo base_url('purchases'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-cyan-50 transition-all group">
                        <div class="p-2 bg-cyan-100 rounded-lg group-hover:bg-cyan-600 transition-all">
                            <i class="fas fa-shopping-cart text-cyan-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-cyan-600">Purchase Bills</span>
                    </a>
                    <a href="<?php echo base_url('receipts'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-cyan-50 transition-all group">
                        <div class="p-2 bg-cyan-100 rounded-lg group-hover:bg-cyan-600 transition-all">
                            <i class="fas fa-money-bill-wave text-cyan-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-cyan-600">Receipts</span>
                    </a>
                    <a href="<?php echo base_url('payments'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-cyan-50 transition-all group">
                        <div class="p-2 bg-cyan-100 rounded-lg group-hover:bg-cyan-600 transition-all">
                            <i class="fas fa-credit-card text-cyan-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-cyan-600">Payments</span>
                    </a>
                    <a href="<?php echo base_url('accounting/journal'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-cyan-50 transition-all group">
                        <div class="p-2 bg-cyan-100 rounded-lg group-hover:bg-cyan-600 transition-all">
                            <i class="fas fa-book text-cyan-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-cyan-600">Journal Entries</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- HR & Payroll Dropdown -->
        <div x-show="activeMenu === 'hr'" @click.away="activeMenu = null"
             class="absolute left-0 right-0 bg-white shadow-2xl border-t border-gray-200 animate-slideDown nav-dropdown">
            <div class="max-w-7xl mx-auto p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <a href="<?php echo base_url('hr/employees'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-rose-50 transition-all group">
                        <div class="p-2 bg-rose-100 rounded-lg group-hover:bg-rose-600 transition-all">
                            <i class="fas fa-users text-rose-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-rose-600">Employee Management</span>
                    </a>
                    <a href="<?php echo base_url('hr/attendance'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-rose-50 transition-all group">
                        <div class="p-2 bg-rose-100 rounded-lg group-hover:bg-rose-600 transition-all">
                            <i class="fas fa-clock text-rose-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-rose-600">Attendance</span>
                    </a>
                    <a href="<?php echo base_url('hr/leave'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-rose-50 transition-all group">
                        <div class="p-2 bg-rose-100 rounded-lg group-hover:bg-rose-600 transition-all">
                            <i class="fas fa-calendar-alt text-rose-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-rose-600">Leave Management</span>
                    </a>
                    <a href="<?php echo base_url('hr/payroll'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-rose-50 transition-all group">
                        <div class="p-2 bg-rose-100 rounded-lg group-hover:bg-rose-600 transition-all">
                            <i class="fas fa-dollar-sign text-rose-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-rose-600">Payroll Processing</span>
                    </a>
                    <a href="<?php echo base_url('hr/salary'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-rose-50 transition-all group">
                        <div class="p-2 bg-rose-100 rounded-lg group-hover:bg-rose-600 transition-all">
                            <i class="fas fa-wallet text-rose-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-rose-600">Salary Structures</span>
                    </a>
                    <a href="<?php echo base_url('hr/performance'); ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-rose-50 transition-all group">
                        <div class="p-2 bg-rose-100 rounded-lg group-hover:bg-rose-600 transition-all">
                            <i class="fas fa-chart-line text-rose-600 group-hover:text-white"></i>
                        </div>
                        <span class="font-medium text-gray-700 group-hover:text-rose-600">Performance</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Reports Dropdown -->
        <div x-show="activeMenu === 'reports'" @click.away="activeMenu = null"
             class="absolute left-0 right-0 bg-white shadow-2xl border-t border-gray-200 animate-slideDown nav-dropdown">
            <div class="max-w-7xl mx-auto p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Financial Reports -->
                    <div>
                        <div class="flex items-center gap-2 mb-3 text-gray-700 font-semibold">
                            <i class="fas fa-file-invoice text-indigo-600"></i>
                            <span>Financial Reports</span>
                        </div>
                        <div class="space-y-1 pl-6">
                            <a href="<?php echo base_url('reports/balance-sheet'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Balance Sheet</a>
                            <a href="<?php echo base_url('reports/profit-loss'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Profit & Loss</a>
                            <a href="<?php echo base_url('reports/cash-flow'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Cash Flow Statement</a>
                            <a href="<?php echo base_url('reports/trial-balance'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Trial Balance</a>
                        </div>
                    </div>

                    <!-- Books of Accounts -->
                    <div>
                        <div class="flex items-center gap-2 mb-3 text-gray-700 font-semibold">
                            <i class="fas fa-book-open text-indigo-600"></i>
                            <span>Books of Accounts</span>
                        </div>
                        <div class="space-y-1 pl-6">
                            <a href="<?php echo base_url('reports/sales-book'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Sales Book</a>
                            <a href="<?php echo base_url('reports/purchase-book'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Purchase Book</a>
                            <a href="<?php echo base_url('reports/cash-book'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Cash Book</a>
                            <a href="<?php echo base_url('reports/bank-book'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Bank Book</a>
                            <a href="<?php echo base_url('reports/day-book'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Day Book</a>
                        </div>
                    </div>

                    <!-- Sales & Purchase -->
                    <div>
                        <div class="flex items-center gap-2 mb-3 text-gray-700 font-semibold">
                            <i class="fas fa-chart-line text-indigo-600"></i>
                            <span>Sales & Purchase</span>
                        </div>
                        <div class="space-y-1 pl-6">
                            <a href="<?php echo base_url('reports/sales-summary'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Sales Summary</a>
                            <a href="<?php echo base_url('reports/customer-sales'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Customer Wise Sales</a>
                            <a href="<?php echo base_url('reports/purchase-summary'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Purchase Summary</a>
                            <a href="<?php echo base_url('reports/supplier-purchase'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Supplier Purchase</a>
                        </div>
                    </div>

                    <!-- Insurance Reports -->
                    <div>
                        <div class="flex items-center gap-2 mb-3 text-gray-700 font-semibold">
                            <i class="fas fa-shield-alt text-indigo-600"></i>
                            <span>Insurance Reports</span>
                        </div>
                        <div class="space-y-1 pl-6">
                            <a href="<?php echo base_url('reports/policy-register'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Policy Register</a>
                            <a href="<?php echo base_url('reports/claims-register'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Claims Register</a>
                            <a href="<?php echo base_url('reports/premium-analysis'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Premium Analysis</a>
                            <a href="<?php echo base_url('reports/commission'); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all">Commission Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="pt-32">
        <main class="min-h-screen bg-gray-50 p-6">
            <?php
            // Display flash messages
            if($this->session->flashdata('success')):
            ?>
                <div class="alert alert-success alert-auto-dismiss mb-6" data-aos="fade-down">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Success!</strong>
                        <p><?php echo $this->session->flashdata('success'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            if($this->session->flashdata('error')):
            ?>
                <div class="alert alert-danger alert-auto-dismiss mb-6" data-aos="fade-down">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Error!</strong>
                        <p><?php echo $this->session->flashdata('error'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            // Load main content
            if(isset($main_content)) {
                $this->load->view($main_content);
            } else {
                echo '<div class="card"><div class="card-body">No content specified.</div></div>';
            }
            ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 px-6 py-4 text-sm text-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    &copy; <?php echo date('Y'); ?> NA-FIX ERP Solutions. All rights reserved.
                </div>
                <div class="flex items-center gap-4">
                    <span>Version 2.0.0</span>
                    <a href="#" class="hover:text-primary-600 transition-colors">Documentation</a>
                    <a href="#" class="hover:text-primary-600 transition-colors">Support</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- AOS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            once: true
        });
    </script>

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastify -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.js"></script>

    <!-- Main App JS -->
    <script src="<?php echo base_url('assets/js/app.js'); ?>"></script>

    <!-- Additional JS -->
    <?php if(isset($additional_js)): ?>
        <?php foreach($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Auto-dismiss alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-auto-dismiss');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>
