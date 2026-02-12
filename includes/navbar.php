<!-- <section id="header">
    <a href="index.php"> <img src="Image/Logo/without-bg.png" alt="logo" class="logo" height="70px" width="70px"></a>

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
                    <i class="fa-solid fa-cart-shopping"></i><span id="card-item">1</span>
                    <span class="cart-item-count"></span>
                 </a>
            </li>
            <li><a href=""><i class="fa-solid fa-user"></i></a></li>

        </ul>
    </div>
</section> -->

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
                    <div class="profile-menu-container">
                        <button class="profile-btn" id="profileBtn">
                            <i class="fa-solid fa-user"></i>
                            <span class="profile-name"><?= explode(' ', trim($_SESSION['loggedInUser']['name']))[0]; ?></span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="profile.php" class="profile-menu-item">
                                <i class="fa-solid fa-user-circle"></i> My Profile
                            </a>
                            <a href="settings.php" class="profile-menu-item">
                                <i class="fa-solid fa-gear"></i> Settings
                            </a>
                            <a href="orders.php" class="profile-menu-item">
                                <i class="fa-solid fa-receipt"></i> My Orders
                            </a>
                            <a href="wishlist.php" class="profile-menu-item">
                                <i class="fa-solid fa-heart"></i> Wishlist
                            </a>
                            <div class="profile-menu-divider"></div>
                            <a href="logout.php" class="profile-menu-item logout-item">
                                <i class="fa-solid fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php"><i class="fa-solid fa-user"></i></a>
                <?php endif; ?>
            </li>

        </ul>
    </div>
</section>
