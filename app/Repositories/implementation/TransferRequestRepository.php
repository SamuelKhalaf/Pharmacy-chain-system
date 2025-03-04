<?php
namespace App\Repositories\implementation;

use App\Models\TransferRequest;
use App\Repositories\ITransferRequest;
use Illuminate\Support\Facades\DB;

class TransferRequestRepository implements ITransferRequest
{
    public function getAll()
    {
        return TransferRequest::paginate(PAGINATE_COUNT);
    }

    public function findById($id)
    {
        if ($this->isExists($id)){
            return TransferRequest::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return TransferRequest::create([
            'from_branch_id' => $data['from_branch_id'],
            'to_branch_id' => $data['to_branch_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'status' => 'pending'
        ])->id;
    }

    public function update(array $data, $id)
    {
        if ($this->isExists($id)){
            return TransferRequest::where('id',$id)->update($data);
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->isExists($id)){
            return TransferRequest::where('id',$id)->delete();
        }else{
            return false;
        }
    }

    public function isExists($id)
    {
        return TransferRequest::where('id',$id)->exists();
    }

    public function getAllPendingRequests($status)
    {
        return TransferRequest::where('status', trim($status))->get();
    }

    public function getLatestTransferRequests()
    {
        return DB::table('transfer_requests as t')
            ->join('branches as fb', 't.from_branch_id', '=', 'fb.id')
            ->join('branches as tb', 't.to_branch_id', '=', 'tb.id')
            ->join('products as p', 't.product_id', '=', 'p.id')
            ->select(
                't.*',
                'fb.name as from_branch_name',
                'tb.name as to_branch_name',
                'p.name as product_name',
            )
            ->where('t.status', 'pending')
            ->latest('t.created_at')
            ->limit(5)
            ->get();
    }

    public function countPendingRequests()
    {
        return TransferRequest::where('status', 'pending')->count();
    }

    public function getOneByStatus($id, $status)
    {
        return TransferRequest::query()
            ->where('id', $id)
            ->where('status', trim($status))
            ->first();
    }
}
