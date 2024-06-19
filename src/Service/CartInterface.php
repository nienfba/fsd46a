<?php

namespace App\Service;

use App\Entity\Product;


interface CartInterface {

    public function add(Product $product, int $qty=1): static;

    public function update(int $id, int $qty = 1): static;

    public function delete(int $id):static;

    public function empty();

    public function load();

    public function save();

    public function getTotalProducts();
}