<?php
namespace App\Adapters;

interface INotification
{
    public function send($data);
}
