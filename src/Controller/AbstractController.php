<?php
namespace Tiptone\Mvc\Controller;

use Psr\Log\LoggerInterface;

class AbstractController
{
    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @param LoggerInterface $log
     */
    public function setLogger(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * Provide a fully qualified URL to redirect to.
     *
     * @param $url string
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
}

