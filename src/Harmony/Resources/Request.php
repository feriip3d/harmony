<?php

namespace Harmony\Resources;

class Request
{
    public function sanitizeGet($param, $sanitizer) {
        return filter_input(INPUT_GET, $param, $sanitizer);
    }

    public function sanitizePost($param, $sanitizer) {
        return filter_input(INPUT_POST, $param, $sanitizer);
    }
}
