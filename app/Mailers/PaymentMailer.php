<?php namespace App\Mailers;


use Carbon\Carbon;
use App\User;

class PaymentMailer extends Mailer{

    protected $listLocalEmail = ['alonso@avotz.com'];
    protected $listProductionEmail = ['johnny100782@hotmail.com'];

    public function sendPaymentsMembershipMessageTo(User $user)
    {
        $view = 'emails.payments.confirm';
        $subject = 'Cobro de membresia de sueños de vida!';
        $emailTo = $user->email;
        $data = $user->toArray();
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