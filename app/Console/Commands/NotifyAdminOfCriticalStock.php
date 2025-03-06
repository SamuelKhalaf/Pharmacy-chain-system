<?php

namespace App\Console\Commands;

use App\Enums\AdminRole;
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
    protected INotificationService $notificationService;

    public function __construct(
        IBranchInventoryService $branchInventoryService,
        IAdminService $adminService,
        INotificationService $notificationService
    ) {
        parent::__construct();
        $this->branchInventoryService = $branchInventoryService;
        $this->adminService = $adminService;
        $this->notificationService = $notificationService;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $critical_products = $this->branchInventoryService->getCriticalProducts();
        $super_admins = $this->adminService->getByRoleId(AdminRole::SuperAdmin->value);
        if ($critical_products->isNotEmpty()) {

            $this->notificationService->notifyAdminOfCriticalStock((array)$super_admins, (array)$critical_products);

            $this->info('Critical stock notifications stored in database.');
        } else {
            $this->info('No critical stock found.');
        }
    }
}
