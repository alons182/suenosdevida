<?php namespace App\Repositories;


interface ProductRepository {

    public function findById($id);
    public function store($data);
    public function getLasts();
    public function getAll($search);


} 