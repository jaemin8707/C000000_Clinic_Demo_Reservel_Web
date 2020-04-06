<?php $__env->startSection('header'); ?>
<header>
	<?php if(env('APP_ENV')!=='prod'): ?><div style="color:red;position:absolute;top:0;left:0;"><?php echo e(env('APP_ENV')); ?>環境</div><?php endif; ?>
 <div class="header">
  <h1><?php echo e(env('HOSPITAL_NAME','')); ?>　<?php echo $__env->yieldContent('heading',''); ?></h1>
 </div>
</header>
<?php $__env->stopSection(); ?>
<?php /**PATH /home/itc-dev/laravel_project/Clinic_Reservel_demo/reservel-web/resources/views/layouts/header.blade.php ENDPATH**/ ?>