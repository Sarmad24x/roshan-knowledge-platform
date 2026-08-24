<?php
// includes/cart-functions.php
// Shopping cart helper functions

// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not exists
function initCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

// Add item to cart
function addToCart($product_id, $quantity = 1) {
    initCart();
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Remove item from cart
function removeFromCart($product_id) {
    initCart();
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update cart quantity
function updateCartQuantity($product_id, $quantity) {
    initCart();
    if ($quantity <= 0) {
        removeFromCart($product_id);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Get cart items with product details
function getCartItems($pdo) {
    initCart();
    $items = [];
    $total = 0;
    
    if (!empty($_SESSION['cart'])) {
        $ids = array_keys($_SESSION['cart']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders) AND is_active = 1");
        $stmt->execute($ids);
        $products = $stmt->fetchAll();
        
        foreach ($products as $product) {
            $quantity = $_SESSION['cart'][$product['id']];
            $subtotal = $product['price'] * $quantity;
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
            $total += $subtotal;
        }
    }
    
    return ['items' => $items, 'total' => $total];
}

// Get cart count
function getCartCount() {
    initCart();
    return array_sum($_SESSION['cart']);
}

// Clear cart
function clearCart() {
    $_SESSION['cart'] = [];
}

// Format price in PKR
function formatPrice($price) {
    return 'Rs. ' . number_format($price, 0);
}
?>