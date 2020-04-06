<?php $__env->startSection('title', '受付完了 - '); ?>
<?php echo $__env->make('layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startSection('heading', '受付完了'); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main>
	<section>
		<div class="comprete_title">受付が完了しました</div>
		<div class="comprete_clinictype">受付区分：<?php echo e(config('const.CARE_TYPE_NAME')[$careType]['name']); ?></div>
		<div class="comprete_number">受付番号：<?php echo e($reception_no); ?></div>
		<div class="comprete_text">
			ご記入いただいたメールアドレス宛に<br class="br-u600">受付完了メールを送信しました。<br/>
			受付番号が記載されていますので、<br class="br-u600">ご確認ください。
		</div>
		<div class="comprete_text"> <a href="<?php echo e(route('information.mail')); ?>">※メールが届かないお客様へ</a>
		<div class="comprete_back"><a href="<?php echo e(route('index')); ?>">受付状況トップ画面に戻る</a></div>
	</section>
</main>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.common', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itc-dev/laravel_project/Clinic_Reservel_demo/reservel-web/resources/views/reserve/complete.blade.php ENDPATH**/ ?>