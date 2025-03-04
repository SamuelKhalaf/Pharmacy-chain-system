<?php
namespace App\Repositories\implementation;

use App\Models\Notification;
use App\Repositories\INotification;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository implements INotification
{
    public function getAll()
    {
        return Notification::query()
            ->where('admin_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUnReadNotification(): Collection|array
    {
        return Notification::query()
            ->where('is_read', '=' , false)
            ->where('admin_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
    }

    public function getBy($column,$operator,$value)
    {
        return Notification::query()
            ->where($column,$operator,$value)
            ->get();
    }

    public function findById($id)
    {
        if ($this->isExists($id)){
            return Notification::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return Notification::create($data);
    }

    public function update($data , $id)
    {
        return Notification::where('id', $id)->update($data);
    }

    public function isExists($id)
    {
        return Notification::where('id',$id)->exists();
    }

}
