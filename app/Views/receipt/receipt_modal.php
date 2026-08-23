<?php
    use App\Controllers\PaymentsController;
    use chillerlan\QRCode\QRCode;
    use chillerlan\QRCode\QROptions;
    use chillerlan\QRCode\Output\QRGdImagePNG;
    
    $paymentsController = new PaymentsController();
    $payment = json_decode($paymentsController->view($id));

    $data = json_encode([
        'type' => 'receipt',
        'uuid' => $payment->data->id
    ], JSON_UNESCAPED_SLASHES);

    $options = new QROptions([
        'outputInterface' => QRGdImagePNG::class,
        'scale'           => 6,
    ]);

    $qr = (new QRCode($options))->render($data);
?>
   <link rel="stylesheet" href="<?= base_url('template/tailwind.css'); ?>">

<?php
    require_once __DIR__.'/receipt.php';
?>