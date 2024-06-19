<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontController extends AbstractController
{

    #[Route('/', name: 'homepage')]
    public function noLocalHomepage(): Response
    {
        return $this->redirectToRoute('app_front', ['_locale' => 'fr']);
    }


    #[Route('/{_locale<%app.supported_locales%>}', name: 'app_front')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('front/index.html.twig', [
            'products' => $productRepository->findAll()
            //'products' => $productRepository->findByPriceRange(100, 150)
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/product/{slug}', name: 'app_front_product')]
    public function product(#[MapEntity(mapping: ['slug' => 'slug'])] Product $product): Response
    {
        return $this->render('front/product.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/pages/{pageName}', name: 'app_static_page')]
    public function staticPage(string $_locale, string $pageName, Environment $twig): Response
    {
        $template = 'front/pages/' . $pageName . '.' . $_locale . '.html.twig';
        $loader = $twig->getLoader();
        if (!$loader->exists($template))
            throw new NotFoundHttpException();

        return $this->render($template, []);
    }
}
