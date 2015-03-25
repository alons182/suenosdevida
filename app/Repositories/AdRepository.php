<?php
/**
 * Created by PhpStorm.
 * User: Alonso
 * Date: 23/03/2015
 * Time: 11:55 AM
 */

namespace App\Repositories;


interface AdRepository {
    public function getAll($search);
    public function store($data);
    public function findById($id);
    public function update($id, $data);
    public function destroy($id);
    public function checkAd($ad, $user_id);
}