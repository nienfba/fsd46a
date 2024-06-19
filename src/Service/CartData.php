<?php

use App\Entity\Product;
use App\Service\CartInterface;

/** EXEMPLE DE CLASSE pour étendre l'interface
 * Elle permettrait par exemple de stocker le panier dans la base de données.
 * 
 * Il suffit alors de changer comment la session est stocké dans la variable d'environnement 
 */

class CartData implements CartInterface {

    public function add(Product $product, int $qty=1): static 
    {
        dd($product);
        return $this;
    }

    public function update(int $id, int $qty = 1): static 
    {
        return $this;
    }

    public function delete(int $id): static
    {
        return $this;
    }

    public function empty() {

    }

    public function load() {
        // connexion à la base
    }

    public function save() {}

    public function getTotalProducts() {}

}