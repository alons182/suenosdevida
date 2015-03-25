<?php


namespace App\Presenters;


use Laracasts\Presenter\Presenter;

class ProductPresenter extends Presenter {

    public function sizes()
    {
        return json_decode($this->entity->sizes);
    }
    public function colors()
    {
        return json_decode($this->entity->colors);
    }
    public function related()
    {
        return json_decode($this->entity->related);
    }
}