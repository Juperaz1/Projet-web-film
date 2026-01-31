<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $requestStack;
    
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    public function getCart(): array
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        
        return array_filter($cart, function($item) {
            return isset($item['id'], $item['name'], $item['price'], $item['quantity']);
        });
    }
    
    public function addToCart(array $product): void
    {
        $session = $this->requestStack->getSession();
        $cart = $this->getCart();
        $id = $product['id'];
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ];
        }
        
        $session->set('cart', $cart);
    }
}