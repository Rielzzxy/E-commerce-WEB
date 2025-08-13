<?php
/**
 * Created by PhpStorm.
 * User: Tanjil Hasan
 * Date: 9/15/2017
 * Time: 2:17 AM
 */
?>
<?php
    include 'lib/Session.php';
    Session::init();
    function formatRupiah($angka){
        return "Rp" . number_format($angka, 2, ',', '.');
    }
?>
<?php
    include "lib/Database.php";
    include 'helpers/Formate.php';

    spl_autoload_register(function ($classes){ include_once 'classes/'.$classes.'.php';});
    $databaseObject = new Database();
    $productObject  = new Product();
    $categoryObject = new Cart();
    $userObject     = new User();
    $formateObject  = new Formate();
    $cartObject     = new Cart();
?>
<!DOCTYPE html>
<html lang="<?php echo $_SERVER['HTTP_ACCEPT_LANGUAGE']?>">
<head>
    <title> <?php echo $formateObject->title(); ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="Tanjil Hasan" />
    <script type="application/x-javascript">
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
        function hideURLbar(){ window.scrollTo(0,1); }
    </script>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/fasthover.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <link href='//fonts.googleapis.com/css?family=Glegoo:400,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="js/bootstrap-3.1.1.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".scroll").click(function(event){
                event.preventDefault();
                $('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
            });
        });
    </script>
    <style>
        .star-rating {
            line-height:32px;
            font-size:1.25em;
        }
        .star-rating .fa-star{
            color: yellow;
        }
    </style>
</head>
<body>
<div class="header" id="home1">
    <div class="container">
        <div class="w3l_logo">
            <h1><a href="index.php">Electronic Store<span>Your stores. Your place.</span></a></h1>
        </div>
        <div class="search">
            <input class="search_box" type="checkbox" id="search_box">
            <label class="icon-search" for="search_box"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></label>
            <?php if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['search']) ):  ?>
                <?php
                $id = $productObject->searchProduct($_POST['Search']);
                if ($id)
                {
                    while ($pri = $id->fetch_assoc()) {
                        echo "<script>window.location = 'single.php?proid="; echo $pri['proid']; echo "'</script>";
                    }
                }
                ?>
            <?php endif;  ?>
            <div class="search_form">
                <form action="#" method="post">
                    <input type="text" name="Search" placeholder="Search Product..." />
                    <input type="submit" value="Send" name="search" />
                </form>
            </div>
        </div>
    </div>
</div>
<div class="navigation">
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="navbar-header nav_2">
                <button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php" >Products</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li class="w3pages"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pages <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if (Session::get("userLogin")): ?>
                                <li><a href="cart.php">My Cart</a></li>
                            <?php endif; ?>
                            <li><a href="mail.php">Mail Us</a></li>
                        </ul>
                    </li>
                    <?php
                    if (isset($_GET['action']) &&  $_GET['action'] == 'logoutCustomer') {
                        $cartObject->deleteCart();
                        session_destroy();
                        echo "<script>window.location = 'index.php';</script>";
                    }
                    ?>
                    <?php if (Session::get("userLogin")): ?>
                        <li><a href="profile.php">Profile</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</div>
<div class="banner banner10">
    <div class="container">
        <h2>Product Description</h2>
    </div>
</div>
<div class="breadcrumb_dress">
    <div class="container">
        <ul>
            <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a> <i>/</i></li>
            <li>Add to Cart</li>
        </ul>
    </div>
</div>
<?php global $total; $total = 0; global $emptyCart; ?>
<div class="single">
    <div class="container">
        <div class="row">
            <?php if ( isset($_GET['delCart']) && $_GET['delCart'] != null ): ?>
                <?php $cartObject->delCartById($_GET['delCart']); ?>
            <?php endif; ?>
            <?php if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['updateCart']) ): ?>
                <?php $cartObject->updateQuantity($_POST['cartid'], $_POST['quantity']); ?>
            <?php endif; ?>
            <?php $allCartProduct = $cartObject->allProductBySessionId(); if ($allCartProduct): $emptyCart = true; $i = 0; ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="cart-table">
                        <table class="table">
                            <thead class="table table-bordered">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Update</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ( $allCart = $allCartProduct->fetch_assoc() ): $i++; ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td class="text-center"><?php echo $allCart['proname']; ?></td>
                                        <td class="text-center"><?php echo formatRupiah($allCart['price']); ?></td>
                                        <td class="text-center"><?php echo $allCart['quantity']; ?></td>
                                        <td class="text-center"><?php echo formatRupiah($allCart['quantity'] * $allCart['price']); $total += $allCart['quantity'] * $allCart['price']; ?></td>
                                        <td class="text-center">
                                            <img src="admin/<?php echo $allCart['image']; ?>" class="img-rounded img-responsive" style="height:65%;width:70%;" />
                                        </td>
                                        <td class="text-center">
                                            <form method="post" action="">
                                                <input type="hidden" name="cartid" value="<?php echo $allCart['cartid']; ?>" />
                                                <input type="number" name="quantity" value="<?php echo $allCart['quantity']; ?>" />
                                                <button type="submit" class="btn btn-default" name="updateCart">Update</button>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <a href="?delCart=<?php echo $allCart['cartid']; ?>" onclick="return confirm('Are You Sure You Want to Delete ?');" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4 pull-right hover">
                    <div class="cart-info" style="background-color: #d9edf7; padding: 15%; box-shadow: 5px 4px 18px #888888; border-radius: 3%; font-weight: 700">
                        <p>Total Price: <?php echo formatRupiah($total); Session::set("total", $total); ?></p>
                    </div>
                    <div class="checkout-button" style="box-shadow: 5px 4px 18px #888888;">
                        <a class="btn btn-block btn-warning text-capitalize" href="payment.php">checkout</a>
                    </div>
                </div>
                <div class="col-md-8 col-lg-8 col-sm-8 col-xs-12" style="margin-top: 30px;">
                    <div class="panel panel-info">
                        <div class="panel-heading"><strong>Pilih Metode Pembayaran</strong></div>
                        <div class="panel-body">
                            <form action="payment.php?orderNow=order" method="post" id="paymentForm">
                                <div class="form-group">
                                    <label for="payment_method">Metode Pembayaran:</label>
                                    <select class="form-control" name="payment_method" id="payment_method" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="bank_transfer">Transfer Bank</option>
                                        <option value="e_wallet">E-Wallet (OVO, DANA, GoPay)</option>
                                        <option value="credit_card">Kartu Kredit</option>
                                    </select>
                                </div>
                                <div class="form-group">
    <label for="address">Alamat Pengiriman:</label>
    <textarea class="form-control" name="address" id="address" rows="4" maxlength="1000" placeholder="Masukkan alamat lengkap (maksimal 100 kata)" required></textarea>
    <small id="wordCount" class="form-text text-muted">0 / 100 kata</small>
</div>
<script>
    const addressInput = document.getElementById('address');
    const wordCountDisplay = document.getElementById('wordCount');
    addressInput.addEventListener('input', function () {
        const words = this.value.trim().split(/\s+/);
        const wordCount = words.filter(w => w !== "").length;
        if (wordCount > 100) {
            this.value = words.slice(0, 100).join(" ");
        }
        wordCountDisplay.textContent = `${wordCount} / 100 kata`;
    });
</script>

                                <div id="additional_fields"></div>
                                <button type="submit" class="btn btn-primary">Lanjut ke Pembayaran</button>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    document.getElementById('payment_method').addEventListener('change', function () {
                        const value = this.value;
                        let html = '';
                        if (value === 'bank_transfer') {
                            html = `
                                <div class="form-group">
                                    <label>Bank Tujuan:</label>
                                    <select class="form-control" name="bank_name" required>
                                        <option value="BCA">BCA</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="BNI">BNI</option>
                                        <option value="BRI">BRI</option>
                                    </select>
                                </div>`;
                        } else if (value === 'e_wallet') {
                            html = `
                                <div class="form-group">
                                    <label>Jenis E-Wallet:</label>
                                    <select class="form-control" name="wallet_type" required>
                                        <option value="OVO">OVO</option>
                                        <option value="DANA">DANA</option>
                                        <option value="GoPay">GoPay</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nomor E-Wallet:</label>
                                    <input type="text" name="wallet_number" class="form-control" required>
                                </div>`;
                        } else if (value === 'credit_card') {
                            html = `
                                <div class="form-group">
                                    <label>Nomor Kartu Kredit:</label>
                                    <input type="text" name="card_number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Masa Berlaku:</label>
                                    <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY" required>
                                </div>
                                <div class="form-group">
                                    <label>CVC:</label>
                                    <input type="text" name="card_cvc" class="form-control" required>
                                </div>`;
                        }
                        document.getElementById('additional_fields').innerHTML = html;
                    });
                </script>
            <?php else: ?>
                <div class='alert alert-danger alert-dismissable'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong> Warning !</strong> Cart is Empty.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
