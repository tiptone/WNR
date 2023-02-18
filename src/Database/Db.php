<?php

namespace Tiptone\Mvc\Database;

class Db extends \SQLite3
{
    public function __construct($database)
    {
        if (file_exists($database)) {
            $this->open($database);
        }
    }
}
