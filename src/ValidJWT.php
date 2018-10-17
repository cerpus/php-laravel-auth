<?php

namespace Cerpus\LaravelAuth;


class ValidJWT {
    private $contextName;
    private $raw;
    private $payload;
    private $type;

    public function __construct($contextName, string $raw, $payload, $type) {
        $this->contextName = $contextName;
        $this->raw = $raw;
        $this->payload = $payload;
        $this->type = $type;
    }

    public function toString(): string {
        return $this->raw;
    }

    /**
     * @return object
     */
    public function getPayload() {
        return $this->payload;
    }

    /**
     * @return mixed
     */
    public function getContextName() {
        return $this->contextName;
    }

    public function getType() {
        return $this->type;
    }
}