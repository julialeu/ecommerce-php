<?php

class Category
{
    private int $categorytId;
    private string $name;

    public function __construct(
        int $categorytId,
        string $name

    ) {
        $this->categorytId = $categorytId;
        $this->name = $name;
    }

    public function categorytId(): int
    {
        return $this->categorytId;
    }

    public function name(): string
    {
        return $this->name;
    }

}