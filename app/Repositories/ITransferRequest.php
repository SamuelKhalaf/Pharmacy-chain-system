<?php
namespace App\Repositories;

interface ITransferRequest
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update(array $data , $id);
    public function delete($id);
    public function getAllPendingRequests($status);
    public function getOneByStatus($id,$status);
    public function getLatestTransferRequests();
    public function countPendingRequests();
}
