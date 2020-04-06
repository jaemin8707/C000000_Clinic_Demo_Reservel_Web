<?php $__env->startSection('head'); ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $__env->yieldContent('title',''); ?>管理画面 - <?php echo e(env('HOSPITAL_NAME','')); ?> - リザベル</title>
<meta name="description" content="<?php echo e(env('HOSPITAL_NAME','')); ?>の現在の混雑状況です。">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta name="robots" content="noindex" />
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('/css/style.css')); ?>">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js">
</script>
<script src="<?php echo e(asset('/js/Sortable.min.js')); ?>" charset="UTF-8"></script>
<?php echo $__env->yieldContent('refresh'); ?>
<?php $__env->stopSection(); ?><?php /**PATH /home/itc-dev/laravel_project/Clinic_Reservel_demo/reservel-admin/resources/views/layouts/head.blade.php ENDPATH**/ ?>