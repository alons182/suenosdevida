<?php namespace App\Mailers;

use App\User;

class UserMailer extends Mailer{

    protected $listLocalEmail = ['alonso@avotz.com'];
    protected $listProductionEmail = ['alons182@gmail.com'];

    public function sendWelcomeMessageTo(User $user, $password = null)
    {
        $view = 'emails.registration.confirm';
        $subject = 'Bienvenido a sueños de vida!';
        $emailTo = $user->email;
        $data = $user->toArray();
        $data['password'] = $password;

        return $this->sendTo($emailTo, $subject, $view, $data);
    }
} 