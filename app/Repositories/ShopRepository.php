<?php namespace App\Repositories;


interface ShopRepository {

    public function findById($id);
    public function store($data);
    public function getAll($search);


} 