<section id="header">
    <a href="index.php">
        <img src="Image/Logo/without-bg.png" alt="logo" class="logo" height="70px" width="70px">
    </a>

    <div>
        <ul id="navbar">
            <li><a href="#">Mens</a></li>
            <li><a href="#">Womens</a></li>
            <li><a href="#">New Arrivals</a></li>
            <li><a href="#">Seasonal</a></li>
            <li>
                <div class="box">
                    <input type="text" placeholder="Search...">
                    <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
                </div>
            </li>

            <li>
                <a href="cart.php" class="cart-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="card-item">
                        <?php
                        if (isset($_SESSION['auth'])) {
                            $uid = $_SESSION['loggedInUser']['user_id'];
                            $cart_query = "SELECT * FROM cart WHERE user_id = '$uid'";
                            $cart_query_run = mysqli_query($conn, $cart_query);

                            if ($cart_query_run) {
                                echo mysqli_num_rows($cart_query_run);
                            } else {
                                echo "0";
                            }
                        } else {
                            echo "0";
                        }
                        ?>
                    </span>
                </a>
            </li>

            <li>
                <?php if (isset($_SESSION['auth'])): ?>
                    <div class="profile-dropdown">
                        <button class="profile-btn" type="button" aria-label="Open profile menu">
                            <i class="fa-solid fa-user"></i>
                            <?= explode(' ', trim($_SESSION['loggedInUser']['name']))[0]; ?>
                            <i class="fa-solid fa-chevron-down profile-chevron"></i>
                        </button>
                        <div class="profile-dropdown-menu">
                            <a href="account-settings.php">Settings</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php"><i class="fa-solid fa-user"></i></a>
                <?php endif; ?>
            </li>

        </ul>
    </div>
</section>
