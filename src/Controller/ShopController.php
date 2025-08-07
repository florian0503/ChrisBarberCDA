<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'shop')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['isActive' => true], ['createdAt' => 'DESC']);
        
        return $this->render('shop/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/shop/product/{id}', name: 'shop_product_detail')]
    public function productDetail(Product $product): Response
    {
        if (!$product->isActive()) {
            throw $this->createNotFoundException('Produit non disponible');
        }

        return $this->render('shop/product_detail.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/shop/add-to-cart/{id}', name: 'shop_add_to_cart', methods: ['POST'])]
    public function addToCart(Product $product, Request $request, SessionInterface $session): JsonResponse
    {
        if (!$product->isActive() || $product->getStock() <= 0) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Produit non disponible'
            ], 400);
        }

        $quantity = (int) $request->request->get('quantity', 1);
        
        if ($quantity <= 0 || $quantity > $product->getStock()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Quantité invalide'
            ], 400);
        }

        $cart = $session->get('cart', []);
        $productId = $product->getId();

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            if ($newQuantity > $product->getStock()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Stock insuffisant'
                ], 400);
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name' => $product->getName(),
                'price' => $product->getPriceFloat(),
                'image' => $product->getImage(),
                'quantity' => $quantity
            ];
        }

        $session->set('cart', $cart);

        // Calculer le total du panier
        $cartTotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return new JsonResponse([
            'success' => true,
            'message' => 'Produit ajouté au panier',
            'cart' => $cart,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount
        ]);
    }

    #[Route('/shop/cart', name: 'shop_cart')]
    public function cart(SessionInterface $session): JsonResponse
    {
        $cart = $session->get('cart', []);
        
        $cartTotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return new JsonResponse([
            'cart' => $cart,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount
        ]);
    }

    #[Route('/shop/update-cart/{productId}', name: 'shop_update_cart', methods: ['POST'])]
    public function updateCart(int $productId, Request $request, SessionInterface $session): JsonResponse
    {
        $quantity = (int) $request->request->get('quantity', 0);
        $cart = $session->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
            }
        }

        $session->set('cart', $cart);

        $cartTotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return new JsonResponse([
            'success' => true,
            'cart' => $cart,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount
        ]);
    }

    #[Route('/shop/remove-from-cart/{productId}', name: 'shop_remove_from_cart', methods: ['POST'])]
    public function removeFromCart(int $productId, SessionInterface $session): JsonResponse
    {
        $cart = $session->get('cart', []);
        unset($cart[$productId]);
        $session->set('cart', $cart);

        $cartTotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return new JsonResponse([
            'success' => true,
            'cart' => $cart,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount
        ]);
    }
}