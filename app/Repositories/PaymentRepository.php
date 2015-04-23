<?php namespace App\Repositories;


interface PaymentRepository {
    public function store($data);
    public function membershipFee();
    public  function getPayments($data);


} 