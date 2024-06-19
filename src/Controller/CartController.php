<?php

namespace App\Controller;

use App\Service\CartSession;
use App\Service\CartInterface;
use Symfony\UX\Turbo\TurboBundle;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/cart/add', name: 'app_cart_add', methods: ['POST'])]
    public function add(CartInterface $cart, ProductRepository $productRepository, Request $request): Response 
    {
        $id = $request->request->get('id', null);
        if ($id == null)
            return $this->redirectToRoute('app_home'); //nothing to do here

        $product = $productRepository->find($id);
        $qty = $request->request->get('qty', 0);

        $cart->add($product, $qty);
    
        //dd(TurboBundle::STREAM_FORMAT, $request->getPreferredFormat());
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
            return $this->render(
                'front/_cartNav.html.twig',
                [
                    'cartQty' => $cart->getTotalProducts(),
                    'product' => $product
                ],
                new Response(
                    '',
                    200,
                    ['content-type' => TurboBundle::STREAM_MEDIA_TYPE]
                )
            );
        }

        // si pas de TURBO (js desactivé...) : redirection vers la panier !
        return $this->redirectToRoute('app_cart');
    }
}
