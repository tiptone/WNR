<?php
namespace Tiptone\Mvc\View;

use Tiptone\Mvc\View\ViewInterface;

class View implements ViewInterface
{
    /**
     * Template to use when rendering this View
     * @var string
     */
    protected $template = '';

    /**
     * View variables
     * @var array|\ArrayAccess
     */
    protected $variables = [];

    /**
     * View constructor.
     * @param null $variables
     * @return ViewInterface
     */
    public function __construct($variables = null)
    {
        if (null === $variables) {
            $variables = [];
        }

        $this->setVariables($variables);

        return $this;
    }

    /**
     * Property overloading: set variable value
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setVariable($name, $value);
    }

    /**
     * Property overloading: get variable value
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!$this->__isset($name)) {
            return;
        }

        $variables = $this->getVariables();
        return $variables[$name];
    }

    /**
     * Property overloading: do we have the requested variable value?
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        $variables = $this->getVariables();
        return isset($variables[$name]);
    }

    /**
     * Property overloading: unset the requested variable
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        if (!$this->__isset($name)) {
            return;
        }

        unset($this->variables[$name]);
    }

    /**
     * Get a single view variable
     *
     * @param  string       $name
     * @param  mixed|null   $default (optional) default value if the variable is not present.
     * @return mixed
     */
    public function getVariable($name, $default = null)
    {
        $name = (string) $name;
        if (array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        }

        return $default;
    }

    /**
     * Set view variable
     *
     * @param  string $name
     * @param  mixed $value
     * @return View
     */
    public function setVariable($name, $value)
    {
        $this->variables[(string) $name] = $value;
        return $this;
    }

    /**
     * Set view variables all at once
     *
     * @param  array|ArrayAccess $variables
     * @throws Exception\InvalidArgumentException
     * @return ViewModel
     */
    public function setVariables($variables)
    {
        if (!is_array($variables)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s: expects an array received "%s"',
                    __METHOD__,
                    (is_object($variables) ? get_class($variables) : gettype($variables))
                )
            );
        }

        foreach ($variables as $key => $value) {
            $this->setVariable($key, $value);
        }

        return $this;
    }

    /**
     * Get view variables
     *
     * @return array|ArrayAccess|Traversable
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Clear all variables
     *
     * Resets the internal variable container to an empty container.
     *
     * @return View
     */
    public function clearVariables()
    {
        $this->variables = [];
        return $this;
    }

    /**
     * Set the template to be used
     *
     * @param  string $template
     * @return View
     */
    public function setTemplate($template)
    {
        $this->template = (string) $template;
        return $this;
    }

    /**
     * Get the template to be used
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
