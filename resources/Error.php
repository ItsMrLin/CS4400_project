<?php

class Error {
    protected $title = null;
    protected $message = null;

    function Error($title, $message) {
        $this->title = $title;
        $this->message = $message;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getMessage() {
        return $this->message;
    }
}