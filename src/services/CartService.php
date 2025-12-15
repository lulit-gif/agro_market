<?php

/**
 * CartService
 *
 * What this file should do:
 * - Provide simple functions to manage the cart in session.
 * - Add items, update quantities, remove items, clear cart.
 * - Get full cart contents and calculate totals.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Get the current cart array from session.
 *
 * @return array ['product_id' => quantity]
 */
function cart_get()
{
    return $_SESSION['cart'] ?? [];
}

/**
 * Save cart array to session.
 *
 * @param array $cart
 * @return void
 */
function cart_set(array $cart)
{
    $_SESSION['cart'] = $cart;
}

/**
 * Add a product to the cart.
 *
 * @param int $productId
 * @param int $quantity
 * @return void
 */
function cart_add($productId, $quantity = 1)
{
    $cart = cart_get();
    $productId = (int)$productId;
    $quantity  = (int)$quantity;

    if ($productId <= 0 || $quantity <= 0) {
        return;
    }

    if (!isset($cart[$productId])) {
        $cart[$productId] = 0;
    }

    $cart[$productId] += $quantity;
    cart_set($cart);
}

/**
 * Update quantity for a product in the cart.
 *
 * @param int $productId
 * @param int $quantity
 * @return void
 */
function cart_update($productId, $quantity)
{
    $cart = cart_get();
    $productId = (int)$productId;
    $quantity  = (int)$quantity;

    if ($productId <= 0) {
        return;
    }

    if ($quantity <= 0) {
        unset($cart[$productId]);
    } else {
        $cart[$productId] = $quantity;
    }

    cart_set($cart);
}

/**
 * Clear the cart.
 *
 * @return void
 */
function cart_clear()
{
    unset($_SESSION['cart']);
}
