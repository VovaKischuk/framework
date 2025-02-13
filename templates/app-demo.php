<?php include 'header.php'; ?>
<body>
<?php if (!$user): ?>
    <h1>Login with Session</h1>
    <form action="/login" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <h1>Login with JTW</h1>
    <form action="/jwt-login" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
<?php else: ?>
<h1>Logout</h1>
<form action="/logout" method="post">
    <button type="submit">Logout</button>
</form>

<h1>Products</h1>
<ul>
    <?php
    foreach ($products as $product): ?>
        <li>
            <?= htmlspecialchars($product->name) ?> -
            <?= htmlspecialchars($product->price) ?>
        </li>
    <?php endforeach; ?>

    <h1>Create cart</h1>
    <form action="/cart" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit">Create Cart</button>
    </form>
    <h2>Add Item</h2>
    <form action="/cart/item" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <label for="cart_id">Cart ID:</label>
        <input type="number" id="cart_id" name="cart_id" required>
        <br>
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <br>
        <button type="submit">Add item</button>
    </form>
    <h2>Cart Items</h2>
    <ul>
        <?php foreach ($cartItems as $cartItem): ?>
            <li>
                Item ID: <?= htmlspecialchars($cartItem->id) ?> -
                Cart ID: <?= htmlspecialchars($cartItem->cart_id) ?> -
                Product ID: <?= htmlspecialchars($cartItem->product_id) ?> -
                Quantity: <?= htmlspecialchars($cartItem->quantity) ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <h2>Place Order</h2>
    <form action="/cart/order" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <label for="cart_id">Cart ID:</label>
        <input type="number" id="cart_id" name="cart_id" required>
        <br>
        <button type="submit">Place order</button>
    </form>
        <?php endif; ?>
</body>
<?php include 'footer.php'; ?>

