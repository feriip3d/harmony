<?php
namespace Harmony\Resources;


class Session
{
    public function __construct() {
    }

    public function startSession() {
        session_start();
    }

    public function storeObject(String $object_identifier, $object) :bool {
        if(!empty($object_identifier)) {
            $_SESSION[$object_identifier] = serialize($object);
            return true;
        }

        return false;
    }

    public function restoreObject(String $object_identifier) {
        if(!empty($object_identifier)) {
            if(!empty($_SESSION[$object_identifier]))
                return unserialize($_SESSION[$object_identifier]);
        }

        return null;
    }

    public function destroyObject(String $object_identifier) {
        if(!empty($object_identifier))
            unset($_SESSION[$object_identifier]);
    }

    public function destroySession() {
        unset($_SESSION);
        session_destroy();
    }
}