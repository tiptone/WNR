<?php
namespace Tiptone\Mvc\Controller;

use Tiptone\Mvc\Controller\AbstractController;
use Tiptone\Mvc\View\View;

/**
 * Class IndexController
 * @package Tiptone\Mvc\Controller
 */
class IndexController extends AbstractController
{
    public function indexAction()
    {
        return new View();
    }
}
