<?php

namespace Reviews\Model;

class Review
{
    public $timestamp;
    public $whiskey;
    public $reviewer;
    public $url;
    public $rating;
    public $region;
    public $price;
    public $date;

    public function exchangeArray($data)
    {
        foreach ($data as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }
}
