<?php
include 'header.php';
global $total;

// Format Rupiah
function formatRupiah($angka){
    return "Rp" . number_format($angka, 2, ',', '.');
}
?>

<div class="banner banner10">
    <div class="container">
        <h2>Payment</h2>
    </div>
</div>

<div class="breadcrumb_dress">
    <div class="container">
        <ul>
            <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a> <i>/</i></li>
            <li>Payment</li>
        </ul>
    </div>
</div>

<div class="single">
    <div class="container">
        <div class="row">

        <?php
        // Jika user melakukan order
        if (isset($_GET['orderNow']) && $_GET['orderNow'] == 'order'):
            // Simpan pesanan dan hapus cart
            $productObject->orderProductByCustomerId(Session::get("userId"));
            $cartData = $cartObject->allProductBySessionId();
            $cartObject->deleteCart();

            // Simulasi ambil data dari session atau POST
            $payment_method = $_POST['payment_method'] ?? 'Tidak diketahui';
            $address = $_POST['address'] ?? 'Tidak tersedia';
            $invoiceId = "INV" . time();
            $tanggal = date("d M Y, H:i");
        ?>

        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading"><strong>Invoice Pembayaran</strong></div>
                <div class="panel-body">
                    <p><strong>No. Invoice:</strong> <?= $invoiceId ?></p>
                    <p><strong>Tanggal:</strong> <?= $tanggal ?></p>
                    <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($payment_method) ?></p>
                    <p><strong>Alamat Pengiriman:</strong><br><?= nl2br(htmlspecialchars($address)) ?></p>

                    <hr>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0; $grandTotal = 0;
                            if ($cartData):
                                while ($item = $cartData->fetch_assoc()):
                                    $i++;
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $grandTotal += $subtotal;
                            ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $item['proname'] ?></td>
                                <td><?= formatRupiah($item['price']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= formatRupiah($subtotal) ?></td>
                            </tr>
                            <?php endwhile; endif; ?>
                        </tbody>
                    </table>
                    <h4 class="text-right">Total: <?= formatRupiah($grandTotal) ?></h4>
                    <hr>
                    <div class="alert alert-success">
                        Terima kasih! Pesanan Anda telah diterima dan akan segera diproses.
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
            <?php
            // Jika user belum order, tampilkan isi cart seperti biasa
            $allCartProduct = $cartObject->allProductBySessionId();
            if ($allCartProduct):
                $emptyCart = true; $i = 0;
            ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="cart-table">
                        <table class="table">
                            <thead class="table table-bordered">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">Image</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($allCart = $allCartProduct->fetch_assoc()): $i++; ?>
                                <tr>
                                    <th scope="row" class="text-center"><?= $i ?></th>
                                    <td class="text-center"><?= $allCart['proname'] ?></td>
                                    <td class="text-center"><?= formatRupiah($allCart['price']) ?></td>
                                    <td class="text-center"><?= $allCart['quantity'] ?></td>
                                    <td class="text-center">
                                        <?php
                                            $subtotal = $allCart['quantity'] * $allCart['price'];
                                            echo formatRupiah($subtotal);
                                            $total += $subtotal;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <img src="admin/<?= $allCart['image'] ?>" class="img-rounded img-responsive" style="height: 65%; width: 70%;" />
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if (isset($total)): ?>
                    <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4 pull-right hover">
                        <div class="cart-info" style="background-color: #d9edf7; padding: 15%; box-shadow: 5px 4px 18px #888888; border-radius: 3%; font-weight: 700">
                            <p>Total Price: <?= formatRupiah($total) ?></p>
                        </div>
                        <div class="checkout-button" style="box-shadow: 5px 4px 18px #888888; margin-top: 15px;">
                            <form method="post" action="?orderNow=order">
                                <input type="hidden" name="payment_method" value="<?= htmlspecialchars($_POST['payment_method'] ?? 'Tidak diketahui') ?>">
                                <input type="hidden" name="address" value="<?= htmlspecialchars($_POST['address'] ?? 'Tidak tersedia') ?>">
                                <button type="submit" class="btn btn-warning btn-block">Order Sekarang</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class='alert alert-danger alert-dismissable'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Warning!</strong> Cart is Empty.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
