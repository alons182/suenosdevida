<?php namespace App\Mailers;


use Carbon\Carbon;
use App\User;

class PaymentMailer extends Mailer{

    protected $listLocalEmail = ['alonso@avotz.com'];
    protected $listProductionEmail = ['johnny100782@hotmail.com'];

    public function sendReportGenerateCutMonthlyMessageTo($count)
    {
        $view = 'emails.payments.generateCutGeneral';
        $subject = 'Se genero un corte en el sistema!';
        $emailTo = $this->listProductionEmail;
        $data['usersCount'] = $count;
        $data['month'] = Carbon::now()->month;
        $data['year'] = Carbon::now()->year;


        return $this->sendTo($emailTo, $subject, $view, $data);
    }
    public function sendReportGenerateCutMessageTo($user)
    {
        $view = 'emails.payments.generateCut';
        $subject = 'Se genero un corte en el sistema!';
        $emailTo = $this->listProductionEmail;
        $data['user_email'] =$user->email;
        $data['username'] =$user->username;
        $data['month'] = Carbon::now()->month;
        $data['year'] = Carbon::now()->year;


        return $this->sendTo($emailTo, $subject, $view, $data);
    }
} 