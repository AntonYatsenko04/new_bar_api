<?php

namespace App\Entity\Users;

interface IUserEntity
{
    public function getId(): ?int;


    public function getEmail(): ?string;


    public function getToken(): ?string;


}