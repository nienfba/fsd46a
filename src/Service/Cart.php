<?php

namespace App\Service;

use App\Entity\Photo;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart {

    /**
     * @var array the cart
     */
    private array $cart;

    /**
     * @var SessionInterface session
     */
    private SessionInterface $session;


    /**
     * Construct
     * 
     * @param RequestStack $requestStack
     */
    public function __construct(private RequestStack $requestStack, private string $sessionName)
    {
        // nomaly at this step we have session 
        $this->session = $requestStack->getSession();
        $this->cart = $this->session->get($this->sessionName, []);
        if (empty($this->cart))
            $this->init();
    }


    /**
     * Init cart values
     */
    protected function init() {
        $this->cart = ['qty'=>0, 'total'=>['subtotal'=>0,'tax'=>0,'total'=>0], 'products'=>[]];
        $this->save();
    }

    /**
     * @return array cart
     */
    public function get():array 
    {
        return $this->cart;
    }

    /**
     * @param Product $product 
     * @param int $qty
     * @return self
     */
    public function add(Product $product, int $qty):self
    {

        $id = $product->getId();

        // add product
        if (isset($this->cart['products'][$id]) && isset($this->cart['products'][$id]['qty']))
            $this-> cart['products'][$id]['qty'] += $qty;
        else {
            $this->cart['products'][$id]['image'] = $product->getImages()[0]->getFile();
            $this->cart['products'][$id]['qty'] = $qty;
            $this->cart['products'][$id]['name'] = $product->getName();
            $this->cart['products'][$id]['slug'] = $product->getSlug();
            $this->cart['products'][$id]['priceHT'] = $product->getPrice();
            $this->cart['products'][$id]['tax'] = $product->getTva()->getValue();
            $this->cart['products'][$id]['priceTTC'] = round($product->getPrice() * $product->getTva()->getValue(), 2);
        }

        // Write Cart on session
        $this->save();

        return $this;

    }

    /**
     * @param int $id id product
     * @param int $qty new quantity for product
     */
    public function update(int $id, int $qty): self
    {

        if ($id !== null && isset($this->cart['products'][$id]) && $qty !== null) {
            if ($qty == 0)
                $this->remove($id);
            else
                $this->cart['products'][$id]['qty'] = $qty;
        }

        $this->save();

        return $this;
    }

    /**
     * @param int $id
     * @return self
     */
    public function remove(int $id): self
    {
        unset($this->cart['products'][$id]);
        
        $this->save();

        return $this;
    }


    /**
     * Save cart on session
     */
    public function save() 
    {
        $this->setQty();
        $this->setTotal();
        $this->session->set($this->sessionName, $this->cart);
    }

    /**
     * @param void
     */
    public function setQty()
    {
        $this->cart['qty'] = array_reduce($this->cart['products'], fn ($sum, $item): int => $sum + $item['qty'], 0);
    }

    /**
     * @param void
     * @return int quantity in cart
     */
    public function getQty():int 
    {
        return $this->cart['qty'];
    }


    /**
     * @param void
     * @return void
     */
    public function setTotal() {
        // recalculate total
        $this->cart['total']['subTotal'] = array_reduce($this->cart['products'], fn ($subTotal, $item): float => $subTotal + $item['priceHT'] * $item['qty'], 0);
        $this->cart['total']['tax'] = array_reduce($this->cart['products'], fn ($subTotal, $item): float => $subTotal + $item['tax'] * $item['qty'], 0);
        $this->cart['total']['total'] = $this->cart['total']['subTotal'] + $this->cart['total']['tax'];
    }

    /**
     * @param void
     */
    public function getTotal(): array
    {
        return $this->cart['total'] ;
    }

    /**
     * @return array products
     */
    public function getProducts():array {
        return $this->cart['products'];
    }


}