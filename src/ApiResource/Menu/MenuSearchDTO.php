<?php

namespace App\ApiResource\Menu;

class MenuSearchDTO {
    private string $token;
    private string $searchRequest;

    /**
     * @param string $token
     * @param string $searchRequest
     */
    public function __construct(string $token, string $searchRequest)
    {
        $this->token = $token;
        $this->searchRequest = $searchRequest;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getSearchRequest(): string
    {
        return $this->searchRequest;
    }

    public function setSearchRequest(string $searchRequest): void
    {
        $this->searchRequest = $searchRequest;
    }
}