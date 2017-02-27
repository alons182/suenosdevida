<?php namespace App\Repositories;


interface CatalogueRepository {

    public function findById($id);
    public function store($data);
    public function destroy($id);
    public function getAll($search);
   


} 