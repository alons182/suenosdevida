<?php namespace App\Mailers;


class ContactMailer extends Mailer{

    protected $listLocalEmail = ['alonso@avotz.com'];
    protected $listProductionEmail = ['info@suenosdevidacr.com'];

    public function contact($data)
    {
        $view = 'emails.contact.contact';
        $subject = 'Información desde formulario de contacto de Sueños de vida';
        $emailTo = $this->listProductionEmail;

        return $this->sendTo($emailTo, $subject, $view, $data);
    }
    public function comment($data)
    {
        $view = 'emails.contact.comment';
        $subject = 'Información desde formulario de publicidad de Sueños de vida';
        $emailTo = 'alonso@avotz.com';

        return $this->sendTo($emailTo, $subject, $view, $data);
    }
} 