<?php
namespace App\Repositories;

interface INotification
{
    public function getAll();
    public function getUnReadNotification();
    public function getBy($column,$operator,$value);
    public function findById($id);
    public function create(array $data);
    public function update($data, $id);

}
