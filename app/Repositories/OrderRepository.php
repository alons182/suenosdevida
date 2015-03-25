<?php namespace App\Repositories;

interface OrderRepository {
    public function store($data);
    public function findAll($data);
}