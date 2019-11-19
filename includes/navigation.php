<header>
    <p class="logo"><i class="fas fa-tv"></i>&nbsp;&nbsp;ACME</p>
    <a href="index.php" class="<?= $page === 'index' ? 'active' : '' ?>">Shop</a>

    <!-- Logged-in user navigation -->
    <?php if (isset($_SESSION['current_user'])) { ?>
        <?php $user = getUser($db, $_SESSION['current_user']); ?>

        <a href="cart.php" class="<?= $page === 'cart' ? 'active' : '' ?>">Cart</a>
        <a href="past-orders.php" class="<?= $page === 'orders' ? 'active' : '' ?>">Orders</a>

        <!-- Admin navigation -->
        <?php if ($user['is_admin']) { ?>
            <hr>

            <a href="admin.php" class="<?= $page === 'admin' ? 'active' : '' ?>">Admin</a>
        <?php } ?>
    <?php } ?>

    <!-- Account navigation -->
    <section>
        <?php if (isset($_SESSION['current_user'])) { ?>
            <div class="dropdown">
                <span>
                    <?= $_SESSION['current_user'] ?>
                    <i class="fas fa-angle-down"></i>
                </span>
                <div class="dropdown-content">
                    <a href="settings.php"><i class="fas fa-cogs"></i> Settings</a>
                    <a href="login_files/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        <?php } else { ?>
            <form action="login_files/login_start.php">
                <button type="submit">Login</button>
            </form>
        <?php } ?>
    </section>
</header>