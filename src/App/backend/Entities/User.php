<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/backend/Entities/BaseEntity.php';

class User extends BaseEntity
{
    private int $id;
    private string $email;
    private string $password;
    private DateTimeImmutable $createdAt;


    public function __construct(string $_email, string $_password, DateTimeImmutable $_createdAt) {
        $this->email = $_email;
        $this->password = $_password;
        $this->createdAt = $_createdAt;
    }

    function GetId(): int
    {
        return $this->id;
    }

    function SetId(int $_id)
    {
        $this->id = $_id;
    }

    function GetEmail(): string
    {
        return $this->email;
    }

    function SetEmail(string $_email)
    {
        $this->email = $_email;
    }

    function GetPassword(): string
    {
        return $this->password;
    }

    function SetPassword(string $_password)
    {
        $this->password = $_password;
    }

    function GetCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    
    function SetCreatedAt(DateTimeImmutable $_createdAt)
    {
        $this->createdAt = $_createdAt;
    }

    function CheckIntegrity(): bool
    {
        return true;
    }
    
}
