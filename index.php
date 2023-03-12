<?php
ob_start();
session_start();

use Shopier\Shopier;
use Shopier\Exceptions\NotRendererClassException;
use Shopier\Exceptions\RendererClassNotFoundException;
use Shopier\Exceptions\RequiredParameterException;
use Shopier\Models\Address;
use Shopier\Models\Buyer;
use Shopier\Renderers\AutoSubmitFormRenderer;
use Shopier\Renderers\ButtonRenderer;
use Shopier\Enums\ProductType;

require 'vendor/autoload.php';
require 'template/settings.php';
require 'template/connect.php';
$today = date('Y');

define('API_KEY', 'apikey');
define('API_SECRET', 'apisecret');

$shopier = new Shopier(API_KEY, API_SECRET);

if (isset($_POST['pay'])) {
    $select = $db->prepare('SELECT * FROM `accounts` WHERE `username`=:username');
    $select->execute([
        'username' => $_POST['username']
    ]);

    if ($_POST['username'] == NULL) {
        $_SESSION['error'] = '1';
        $alert = [
            'icon' => 'warning',
            'title' => 'Username cannot be empty!'
        ];
    } elseif ($select->rowCount() == 0) {
        $_SESSION['error'] = '1';
        $alert = [
            'icon' => 'warning',
            'title' => 'The user has not been found!'
        ];
    } elseif ($_POST['price'] == NULL) {
        $_SESSION['error'] = '1';
        $alert = [
            'icon' => 'warning',
            'title' => 'Amount cannot be empty!'
        ];
    } elseif ($_POST['email'] == NULL) {
        $_SESSION['error'] = '1';
        $alert = [
            'icon' => 'warning',
            'title' => 'E-mail address cannot be empty!'
        ];
    } elseif ($_POST['phone'] == NULL) {
        $_SESSION['error'] = '1';
        $alert = [
            'icon' => 'warning',
            'title' => 'Phone number cannot be empty!'
        ];
    } elseif ($_POST['name'] == NULL) {
        $_SESSION['error'] = '1';
        $alert = [
            'icon' => 'warning',
            'title' => 'Name cannot be empty!'
        ];
    } elseif ($_POST['surname'] == NULL) {
        $_SESSION['error'] = '1';
        $alert = [
            'icon' => 'warning',
            'title' => 'Surname cannot be empty!'
        ];
    } else {
        setcookie('usernamee', $_POST['username'], time() + 3600);

        $guncelle = $db->prepare('UPDATE `accounts` SET `postbalance`=:balance WHERE `username`=:username');
        $guncelle->execute([
            'balance' => $_POST['price'],
            'username' => $_POST['username']
        ]);

        $buyer = new Buyer([
            'id' => 101,
            'name' => $_POST['name'],
            'surname' => $_POST['surname'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        ]);

        $address = new Address([
            'address' => 'address',
            'city' => 'city',
            'country' => 'country',
            'postcode' => 'zipcode',
        ]);

        $price = $_POST['price'];
        $text = " $ Balance - Arzea.online";
        $productname = $price . $text;

        $params = $shopier->getParams();

        $params->setBuyer($buyer);

        $params->setAddress($address);

        $shopier->setOrderData('52003', $_POST['price']);

        $shopier->setProductData($productname, ProductType::DEFAULT_TYPE);
        try {


            /**
             *
             * @var AutoSubmitFormRenderer $renderer
             */
            $renderer = $shopier->createRenderer(AutoSubmitFormRenderer::class);
            $shopier->goWith($renderer);

            /**
             *
             * @var ButtonRenderer $renderer
             */
            $renderer = $shopier->createRenderer(ButtonRenderer::class);
            $renderer
                ->withStyle("padding:15px; color: #fff; background-color:#51cbb0; border:1px solid #fff; border-radius:7px")
                ->withText('Pay Safely With Shopier');


            // $shopier->goWith($renderer);

        } catch (RequiredParameterException $e) {
            // One and more of the mandatory parameters are missing
        } catch (NotRendererClassException $e) {
            // $shopier->createRenderer(...) the class name given in the method is not derived from the AbstracRenderer class!
        } catch (RendererClassNotFoundException $e) {
            // $shopier->createRenderer(...) metodunda verilen class bulunamadı !
        }
    }
} else {
    setcookie('usernamee', null, time() + 3600);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="twitter:title" content="Arzea - Shopier Pay System" />
    <meta name="twitter:description" content="Arzea - Shopier Pay System" />
    <meta name="twitter:image" content="#" />
    <meta property="og:url" content="#" />
    <meta name="twitter:title" content="Arzea - Shopier Pay System" />
    <meta property="og:description" content="Arzea - Shopier Pay System" />
    <meta name="description" content="Arzea - Shopier Pay System" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= $favicon ?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat&subset=latin,latin-ext" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap" rel="stylesheet">

    <!-- Payment CSS -->
    <link rel="stylesheet" href="template/payment.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="template/js/cleave.js"></script>
    <title>Arzea.online - Shopier Pay System</title>
</head>

<body>
    <section id="loader">
        <svg class="svg" viewBox="25 25 50 50">
            <circle class="circle" r="20" cy="50" cx="50"></circle>
        </svg>
    </section>
    <section id="page" style="display:none;">
        <main class="page payment-page" style="height: 1067px;">
            <section class="payment-form dark" style="background-color: transparent !important;">
                <div class="container" style="max-width: 86rem;">
                    <form method="post">
                        <div class="header-bar">
                            <i id="i" class="fa-regular fa-check" style="margin-top: 18px;display:none;margin-left: 63px;margin-right: 8px;opacity:0;"></i>
                            <a class="active item" id="usercontent" href="#user" style="margin-left: 85px;">User Details</a>
                            <i class="fa-light fa-angle-right"></i>
                            <a class="coming item" id="payment" href="#payment">Payment Details</a>
                            <i class="fa-light fa-angle-right"></i>
                            <a class="coming item">Confirmation</a>
                        </div>
                        <div class="side-div card-details">
                            <p style="font-weight: 600;">You've to pay,</p>
                            <h2>$<span id="payto">0.00</span></h2>
                            <hr>
                            <div class="side-black">
                                <h2>No refund can be paid after the payment process.</h2>
                            </div>
                            <div class="form-group col-sm-12">
                                <button type="submit" name="pay" class="btn btn-primary btn-block">Pay now</button>
                            </div>
                        </div>
                        <div class="card-details" id="user" style="background-color: #e6f2ff;padding: 22px 0px 22px 50px;margin: 50px;margin-top: 20px;margin-bottom: 25px;">
                            <h3 class="title">User Details</h3>
                            <div id="payment" class="row">
                                <div class="form-group col-sm-4">
                                    <label for="username">Username</label>
                                    <input id="username" type="text" name="username" class="form-control user" placeholder="Username" autocomplete="off" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="price">Amount</label>
                                    <input id="price" onkeyup="amount()" type="number" name="price" class="form-control price" placeholder="Amount" autocomplete="off" aria-label="Amount" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="card-details" id="payment" style="padding-bottom: 29px;">
                            <h3 class="title">Payment Details</h3>
                            <div class="row">

                                <div class="form-group col-sm-8">
                                    <label for="card-holder">E-mail address</label>
                                    <input id="card-holder" type="text" name="email" class="form-control" placeholder="E-mail address" autocomplete="off" aria-label="E-mail address" aria-describedby="basic-addon1">
                                </div>
                                <div class="form-group col-sm-8">
                                    <label for="card-number">Phone number</label>
                                    <div>
                                        <input id="card-number" type="number" name="phone" class="form-control" placeholder="Phone number" autocomplete="off" aria-label="Phone number" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                                <div class="form-group col-sm-5" style="max-width: 33.5%;">
                                    <label for="cvv">Name</label>
                                    <input id="cvv" type="text" name="name" class="form-control" placeholder="Name" autocomplete="off" aria-label="CVC" aria-describedby="basic-addon1">
                                </div>
                                <div class="form-group col-sm-4" style="max-width: 30.5%;">
                                    <label for="cvv">Surname</label>
                                    <input id="cvv" type="text" name="surname" class="form-control" placeholder="Surname" autocomplete="off" aria-label="CVC" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </section>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="template/js/os.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <?php if (isset($_SESSION['error'])) { ?>
        <script>
            Swal.fire({
                position: 'top-center',
                icon: '<?= $alert['icon'] ?>',
                title: '<?= $alert['title'] ?>',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    <?php unset($_SESSION['error']); } ?>
    <a href="https://arzea.online" target="_blank" class="footerp">Arzea © <?= $today; ?></a>

</body>

</html>