<?php

class Event extends ArrayObject {
    public function __invoke() {
        foreach($this as $callback)
            call_user_func_array($callback, func_get_args());
    }
}

class Eventable
{
    public function __call($name, $args)
    {
        if (method_exists($this, $name))
            call_user_func_array(array(&$this, $name), $args);
        else
            if (isset($this->{$name}) && is_callable($this->{$name}))
                call_user_func_array($this->{$name}, $args);
    }

}

class Test extends Eventable
{
    public $onA;

    public function __construct()
    {
        $this->onA = new Event();
    }

    public function A($text)
    {
        $this->onA("This is A", $text);
    }

}

$test = new Test();

$test->onA[] = function($msg, $text) {
    echo "Message: $msg, Text: $text";
};

$test->A("Le Test");
