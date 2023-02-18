<?php
namespace Tiptone\Mvc\View;

use Tiptone\Mvc\View\View;

/**
 * Class JsonView
 * @package Tiptone\Mvc\View
 */
class JsonView extends View
{
    /**
     * Serialize to JSON
     * @return string
     */
    public function serialize()
    {
        $variables = $this->getVariables();

        reset($variables);

        if (count($variables) === 1 && key($variables) === 'json') {
            $data = array_pop($variables);

            return json_encode($data, JSON_PRETTY_PRINT);
        } else {
            return json_encode($variables, JSON_PRETTY_PRINT);
        }
    }
}

