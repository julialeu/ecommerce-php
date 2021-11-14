<?php

class Product
{
    public const NUM_PRODUCTS_PER_PAGE = 10;

    private int $productId;
    private string $name;
    private float $cost;
    private float $price;
    private int $categoryId;
    private string $categoryName;

    public function __construct(
        int $productId,
        string $name,
        float $cost,
        float $price,
        int $categoryId,
        string $categoryName
    ) {
        $this->productId = $productId;
        $this->name = $name;
        $this->cost = $cost;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
    }

    public function productId(): int
    {
        return $this->productId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function cost(): float
    {
        return $this->cost;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function categoryId(): int
    {
        return $this->categoryId;
    }

    public function categoryName(): string
    {
        return $this->categoryName;
    }
}