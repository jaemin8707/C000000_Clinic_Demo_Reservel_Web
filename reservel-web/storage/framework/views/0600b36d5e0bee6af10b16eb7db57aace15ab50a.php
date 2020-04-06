<?php $__env->startSection('head'); ?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
<title><?php echo $__env->yieldContent('title',''); ?><?php echo e(env('HOSPITAL_NAME','')); ?> - リザベル</title>
<meta name="description" content="<?php echo e(env('HOSPITAL_NAME','')); ?>の現在の混雑状況です。">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta name="robots" content="noindex" />
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/style.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/slick.css')); ?>">
<script src="<?php echo e(asset('/js/jquery.min.js')); ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo e(asset('/js/slick.min.js')); ?>" type="text/javascript" charset="utf-8"></script>
<?php echo $__env->yieldContent('refresh'); ?>
<?php $__env->stopSection(); ?><?php /**PATH /home/itc-dev/laravel_project/Clinic_Reservel_demo/reservel-web/resources/views/layouts/head.blade.php ENDPATH**/ ?>