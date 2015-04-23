<?php namespace App\Repositories;


interface GainRepository {
    public function store($data);
    public  function getGainsPerClick($data);
    public  function getAccumulatedGains($data);
    public  function getPossibleGainsPerAffiliates($data, $user = null);

}