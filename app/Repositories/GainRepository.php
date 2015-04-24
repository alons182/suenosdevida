<?php namespace App\Repositories;


interface GainRepository {
    public function store($data);
    public  function getPossibleGainsPerAffiliates($data, $user = null);
    public function getAccumulatedGains($data);

}