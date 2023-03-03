<?php

include __DIR__ . "/../assets/vendor/autoload.php";

class SmallObjectHandler implements \JsonStreamingParser\Listener\ListenerInterface {
    private $json = '';
    private $objects = array();
    private $current_object = null;
    private $current_key = null;

    public function startDocument(): void {}

    public function endDocument(): void {}

    public function startObject(): void {
        $this->current_object = new \stdClass();
    }
    public function endObject(): void {
        $this->objects[] = $this->current_object;
        $this->current_object = null;
    }
    public function startArray(): void {}

    public function endArray(): void {}

    public function key(string $key): void {
        $this->current_key = $key;
    }
    public function value($value) {
        $this->current_object->{$this->current_key} = $value;
    }

    public function whitespace(string $whitespace): void {}

    public function getObjects() {
        return $this->objects;
    }

    public function reset() {
        $this->objects = array();
    }    
}