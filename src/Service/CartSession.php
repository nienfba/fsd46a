<?php

namespace App\Service;

use App\Entity\Product;
use App\Service\CartInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartSession implements CartInterface {


    private $cart=[];

    public function __construct(private RequestStack $request, private string $sessionName){
        $this->load();
    }

    public function add(Product $product, int $qty=1): static 
    {
        if(!isset($this->cart['products'][$product->getId()])) {
            $this->cart['qty'] += $qty;
            $this->cart['products'][$product->getId()] = ['qty' => $qty, 'name' => $product->getName()];
        }
        else
            $this->update($product->getId(), $qty);
       
        $this->save();
        return $this;
    }

    public function update(int $id, int $qty = 1): static 
    {
        $this->cart['qty'] += $qty;
        $this->cart['products'][$id]['qty']+=$qty;
        return $this;
    }

    public function delete(int $id): static
    {
        unset($this->cart['products'][$id]);
        return $this;
    }

    public function empty() {

    }


    public function load()
    {
        $this->cart = $this->request->getSession()->get($this->sessionName, ['qty' => 0, 'products' => []]);
    }

    public function save()
    {
        $this->request->getSession()->set($this->sessionName, $this->cart);
    }
    


    /**
     * Get the value of cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set the value of cart
     */
    public function setCart($cart): self
    {
        $this->cart = $cart;

        return $this;
    }


    public function getTotalProducts() {
        return $this->cart['qty'];
    }

}