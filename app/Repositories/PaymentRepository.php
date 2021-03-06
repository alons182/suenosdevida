<?php namespace App\Repositories;


interface PaymentRepository {

    public function store($data);

    public function getPaymentsOfUser($data = null);
    public function getPaymentsOfUserRed($data = null);
    public function getPaymentsOfMembership($data = null);
    public function getCommissionOfGain($data = null);
    public function getPayments($data);


} 