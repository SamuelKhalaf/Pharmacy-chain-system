<?php

namespace App\Console\Commands;

use App\Adapters\INotification;
use App\Enums\AdminRole;
use App\Models\Notification;
use App\Services\IAdminService;
use App\Services\IBranchInventoryService;
use App\Services\INotificationService;
use Illuminate\Console\Command;

class NotifyAdminOfCriticalStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:critical-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify admin if products quantities are at critical level or below';
    protected IBranchInventoryService $branchInventoryService;
    protected IAdminService $adminService;
    protected INotification $notificationAdapter;

    public function __construct(INotification $notificationAdapter ,IBranchInventoryService $branchInventoryService , IAdminService $adminService)
    {
        parent::__construct();
        $this->branchInventoryService = $branchInventoryService;
        $this->adminService = $adminService;
        $this->notificationAdapter = $notificationAdapter;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $criticalProducts = $this->branchInventoryService->getCriticalProducts();
        $superAdmins = $this->adminService->getByRoleId(AdminRole::SuperAdmin);
        if ($criticalProducts->isNotEmpty()) {
            foreach ($superAdmins as $superAdmin)
            {
                foreach ($criticalProducts as $product)
                {
                    $this->notificationAdapter->send([
                        'admin_id' => $superAdmin->id,
                        'data' => json_encode([
                            'text' => "Product reached critical level.",
                            'product_name' => $product->product_name,
                            'branch_name' => $product->branch_name,
                            'product_quantity' => $product->quantity,
                            'critical_level' => $product->critical_level
                        ]),
                        'is_read' => false,
                    ]);
                }
            }
            $this->info('Critical stock notifications stored in database.');
        } else {
            $this->info('No critical stock found.');
        }
    }
}
