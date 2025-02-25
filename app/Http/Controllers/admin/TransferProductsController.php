<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repositories\IBranch;

class TransferProductsController extends Controller
{
    protected IBranch $branchRepository;

    public function __construct(IBranch $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }
    public function showTransferForm()
    {
        $branches = $this->branchRepository->getAll();
        return view('admin');
    }
}
