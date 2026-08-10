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
   <!-- inject:css-->
   <link rel="stylesheet" href="<?= base_url('template/assets/vendor_assets/css/apexcharts.min.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('template/assets/vendor_assets/css/datepicker.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('template/assets/vendor_assets/css/line-awesome.min.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('template/assets/vendor_assets/css/nouislider.min.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('template/assets/vendor_assets/css/quill.snow.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('template/assets/vendor_assets/css/svgMap.min.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('template/assets/vendor_assets/css/global.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('template/tailwind.css'); ?>">
   <!-- endinject -->

<?php
    require_once __DIR__.'/receipt.php';
?>