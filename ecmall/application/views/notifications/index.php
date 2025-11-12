<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-bell text-blue-600"></i> Notifications
        </h1>
        <?php if($unread_count > 0): ?>
        <a href="<?php echo base_url('notifications/mark_all_read'); ?>" class="btn btn-primary">
            <i class="fas fa-check-double"></i> Mark All as Read
        </a>
        <?php endif; ?>
    </div>

    <?php if(count($notifications) > 0): ?>
    <div class="space-y-3">
        <?php foreach($notifications as $notif): ?>
        <div class="card hover:shadow-lg transition-all <?php echo $notif->is_read ? 'bg-gray-50' : 'bg-white border-l-4 border-blue-500'; ?>">
            <div class="card-body">
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="p-3 rounded-lg <?php
                        echo $notif->type == 'success' ? 'bg-green-100' :
                             ($notif->type == 'warning' ? 'bg-yellow-100' :
                             ($notif->type == 'danger' ? 'bg-red-100' : 'bg-blue-100'));
                    ?>">
                        <i class="fas fa-<?php echo $notif->icon ?? 'info-circle'; ?> text-2xl <?php
                            echo $notif->type == 'success' ? 'text-green-600' :
                                 ($notif->type == 'warning' ? 'text-yellow-600' :
                                 ($notif->type == 'danger' ? 'text-red-600' : 'text-blue-600'));
                        ?>"></i>
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900">
                                    <?php echo $notif->title; ?>
                                    <?php if(!$notif->is_read): ?>
                                    <span class="badge badge-primary ml-2">New</span>
                                    <?php endif; ?>
                                </h3>
                                <p class="text-gray-600 mt-1"><?php echo $notif->message; ?></p>
                                <p class="text-sm text-gray-400 mt-2">
                                    <i class="fas fa-clock"></i>
                                    <?php echo timespan(strtotime($notif->created_at), time()); ?> ago
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                <?php if($notif->link): ?>
                                <a href="<?php echo base_url('notifications/mark_as_read/' . $notif->notification_id); ?>"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <?php endif; ?>

                                <?php if(!$notif->is_read): ?>
                                <a href="<?php echo base_url('notifications/mark_as_read/' . $notif->notification_id); ?>"
                                   class="btn btn-sm btn-outline"
                                   title="Mark as read">
                                    <i class="fas fa-check"></i>
                                </a>
                                <?php endif; ?>

                                <a href="<?php echo base_url('notifications/delete/' . $notif->notification_id); ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this notification?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-12">
            <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Notifications</h3>
            <p class="text-gray-500">You're all caught up! No new notifications at the moment.</p>
        </div>
    </div>
    <?php endif; ?>
</div>
