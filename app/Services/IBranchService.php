<?php
namespace App\Services;

interface IBranchService
{
    public function getAllBranches();
    public function getNewBranches();
    public function getOneBranch($id);
    public function createBranch(array $data);
    public function updateBranch(array $data,$id);
    public function deleteBranch($id);
}
