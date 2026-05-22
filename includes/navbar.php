<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* DETECT IF CURRENT PAGE IS INSIDE /admin */
$isAdminPage = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;

/* BASE PATHS */
$homeLink = $isAdminPage ? '../index.php' : 'index.php';
$productsLink = $isAdminPage ? '../products.php' : 'products.php';
$cartLink = $isAdminPage ? '../cart.php' : 'cart.php';
$profileLink = $isAdminPage ? '../profile.php' : 'profile.php';
$loginLink = $isAdminPage ? '../login.php' : 'login.php';
$registerLink = $isAdminPage ? '../register.php' : 'register.php';
$logoutLink = $isAdminPage ? '../logout.php' : 'logout.php';

$adminDashboardLink = $isAdminPage
    ? 'dashboard.php'
    : 'admin/dashboard.php';

$addProductLink = $isAdminPage
    ? 'add-product.php'
    : 'admin/add-product.php';

$logoPath = $isAdminPage
    ? '../assets/images/logo.png'
    : 'assets/images/logo.png';
?>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

.navbar{
    width:100%;
    background:#1f2937;
    padding:15px 25px;
    position:relative;
    z-index:999;
}

.navbar-container{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    width:100%;
}

/* LOGO */

.logo{
    display:flex;
    align-items:center;
    gap:12px;
    text-decoration:none;
    color:white;
    font-size:24px;
    font-weight:bold;
    white-space:nowrap;
    flex-shrink:0;
}

.logo img{
    height:48px;
    width:auto;
}

/* NAV MENU */

.nav-menu{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex:1;
    min-width:0;
    margin-left:25px;
    width:100%;
}

/* LEFT LINKS */

.nav-links{
    display:flex;
    align-items:center;
    gap:14px;
    list-style:none;
    flex-wrap:wrap;
}

.nav-links a{
    color:white;
    text-decoration:none;
    font-size:14px;
    transition:0.3s;
    white-space:nowrap;
}

.nav-links a:hover{
    color:#3b82f6;
}

/* RIGHT SIDE */

.navbar-right{
    display:flex;
    align-items:center;
    gap:14px;
    flex-shrink:0;
}

.navbar-right a{
    color:white;
    text-decoration:none;
    font-size:14px;
    white-space:nowrap;
}

.logout-btn{
    background:#ef4444;
    color:white !important;
    padding:10px 16px;
    border-radius:6px;
    text-decoration:none;
    transition:0.3s;
}

.logout-btn:hover{
    background:#dc2626;
}

/* MOBILE BUTTON */

.menu-toggle{
    display:none;
    background:none;
    border:none;
    color:white;
    font-size:34px;
    cursor:pointer;
    margin-left:auto;
}

/* TABLET */

@media(max-width:1200px){

    .nav-links{
        gap:10px;
    }

    .nav-links a{
        font-size:13px;
    }

    .navbar-right a{
        font-size:13px;
    }

}

/* MOBILE */

@media(max-width:768px){

    .navbar{
        padding:15px;
    }

    .navbar-container{
        display:flex;
        align-items:center;
        justify-content:space-between;
        flex-wrap:wrap;
        width:100%;
    }

    .logo{
        display:flex;
        align-items:center;
        gap:10px;
        font-size:20px;
    }

    .logo img{
        height:40px;
    }

    .menu-toggle{
        display:block;
        background:none;
        border:none;
        color:white;
        font-size:34px;
        cursor:pointer;
        margin-left:auto;
    }

    /* FORCE MENU UNDER NAVBAR */

    .nav-menu{

        display:none !important;

        width:100% !important;

        flex-direction:column !important;

        align-items:flex-start !important;

        justify-content:flex-start !important;

        margin-top:15px !important;

        margin-left:0 !important;

        padding:15px 0 !important;

        background:#1f2937 !important;

        position:relative !important;

        left:0 !important;

        right:auto !important;
    }

    .nav-menu.active{
        display:flex !important;
    }

    .nav-links{

        width:100% !important;

        display:flex !important;

        flex-direction:column !important;

        align-items:flex-start !important;

        padding:0 !important;

        margin:0 !important;

        gap:0 !important;
    }

    .nav-links li{
        width:100% !important;
        list-style:none;
    }

    .nav-links a{

        display:block !important;

        width:100% !important;

        text-align:left !important;

        padding:14px 10px !important;

        font-size:18px !important;

        white-space:normal !important;
    }

    .navbar-right{

        width:100% !important;

        display:flex !important;

        flex-direction:column !important;

        align-items:flex-start !important;

        margin-top:10px !important;

        gap:0 !important;
    }

    .navbar-right a{

        width:100% !important;

        display:block !important;

        text-align:left !important;

        padding:14px 10px !important;

        font-size:18px !important;
    }

    .logout-btn{

        width:100% !important;

        text-align:center !important;

        margin-top:10px !important;
    }

}

</style>

<nav class="navbar">

    <div class="navbar-container">

        <!-- LOGO -->
        <a href="<?php echo $homeLink; ?>" class="logo">

            <img src="<?php echo $logoPath; ?>" alt="TownTrade SA Logo">

            <span>TownTrade SA</span>

        </a>

        <!-- MOBILE BUTTON -->
        <button class="menu-toggle" id="menuToggle">
            ☰
        </button>

        <!-- MENU -->
        <div class="nav-menu" id="navMenu">

            <!-- LEFT LINKS -->
            <ul class="nav-links">

                <li>
                    <a href="<?php echo $homeLink; ?>">Home</a>
                </li>

                <li>
                    <a href="<?php echo $productsLink; ?>">Products</a>
                </li>

                <li>

                    <?php if(isset($_SESSION['user_id'])) { ?>

                        <a href="<?php echo $addProductLink; ?>">
                            Add Product
                        </a>

                    <?php } else { ?>

                        <a href="<?php echo $loginLink; ?>">
                            Add Product
                        </a>

                    <?php } ?>

                </li>

                <?php if(isset($_SESSION['user_id'])) { ?>

                    <li>
                        <a href="<?php echo $isAdminPage ? '../my-products.php' : 'my-products.php'; ?>">
                            My Products
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $isAdminPage ? '../my-orders.php' : 'my-orders.php'; ?>">
                            My Orders
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $isAdminPage ? '../seller-orders.php' : 'seller-orders.php'; ?>">
                            My Orders Received
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $cartLink; ?>">
                            Cart
                        </a>
                    </li>

                <?php } ?>

            </ul>

            <!-- RIGHT SIDE -->
            <div class="navbar-right">

                <?php if(isset($_SESSION['user_id'])) { ?>

                    <a href="<?php echo $profileLink; ?>">
                        Profile
                    </a>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>

                        <a href="<?php echo $adminDashboardLink; ?>">
                            Admin
                        </a>

                    <?php } ?>

                    <a href="<?php echo $logoutLink; ?>" class="logout-btn">
                        Logout
                    </a>

                <?php } else { ?>

                    <a href="<?php echo $loginLink; ?>">
                        Login
                    </a>

                    <a href="<?php echo $registerLink; ?>">
                        Register
                    </a>

                <?php } ?>

            </div>

        </div>

    </div>

</nav>

<script>

const menuToggle = document.getElementById('menuToggle');
const navMenu = document.getElementById('navMenu');

menuToggle.addEventListener('click', () => {

    navMenu.classList.toggle('active');

});

</script>