<?php
namespace Tiptone\Mvc\View;

interface ViewInterface
{
    /**
     * Get a single view variable
     *
     * @param  string       $name
     * @param  mixed|null   $default (optional) default value if the variable is not present.
     * @return mixed
     */
    public function getVariable($name, $default = null);

    /**
     * Set view variable
     *
     * @param  string $name
     * @param  mixed $value
     * @return ViewInterface
     */
    public function setVariable($name, $value);

    /**
     * Set view variables en masse
     *
     * @param  array|\ArrayAccess $variables
     * @return ViewInterface
     */
    public function setVariables($variables);

    /**
     * Get view variables
     *
     * @return array|\ArrayAccess
     */
    public function getVariables();

    /**
     * Set the template to be used by this model
     *
     * @param  string $template
     * @return ViewInterface
     */
    public function setTemplate($template);

    /**
     * Get the template to be used by this model
     *
     * @return string
     */
    public function getTemplate();
}

