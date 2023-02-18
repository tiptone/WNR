<?php

namespace Tiptone\Mvc\Model;

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

    public function __construct($data=null)
    {
        if (!is_null($data)) {
            foreach ($data as $k => $v) {
                if (property_exists($this, $k)) {
                    $this->$k = $v;
                }
            }
        }
    }
}
