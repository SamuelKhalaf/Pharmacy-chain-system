<?php
namespace App\Services;

interface ITransferRequestService
{
    public function getAllTransferRequests();
    public function getOneTransferRequest($id);
    public function createTransferRequest(array $data);
    public function updateTransferRequest(array $data,$id);
    public function deleteTransferRequest($id);
    public function getAllPendingRequests($status);
    public function getOneByStatus($id,$status);
    public function getLatestTransferRequests();
    public function countPendingRequests();
    public function transferRequestProducts(array $data);

}
