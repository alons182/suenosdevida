<?php namespace App\Repositories;


interface CategoryRepository {

    public function findById($id);
    public function store($data);
    public function destroy($id);
    public function getLasts();
    public function getAll($search);
    public function getParents();

} 