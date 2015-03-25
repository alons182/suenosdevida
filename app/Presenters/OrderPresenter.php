<?php


namespace App\Presenters;


use Laracasts\Presenter\Presenter;

class OrderPresenter extends Presenter {

    public function status()
    {
        return \Lang::get('utils.status.'. $this->entity->status);
    }

}