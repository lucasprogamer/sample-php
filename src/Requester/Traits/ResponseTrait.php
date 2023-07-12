<?php

namespace Src\Requester\Traits;

trait ResponseTrait
{
    protected $header = 'Content-Type: application/json';

    public function header(string $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function response($content, $status = 200)
    {
        switch ($this->header) {
            case 'Content-Type: application/json':
                http_response_code($status);
                echo json_encode([
                    'data' => $content,
                    'error' => false,
                ]);
        }
        exit;
    }
}
