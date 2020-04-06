<?php $__env->startSection('title', '受付状況 - '); ?>
<?php $__env->startSection('refresh'); ?>
<meta http-equiv="refresh" content="120">
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startSection('heading', '受付状況'); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main>
	
	<div class="wrapper">
		<div class="time"><?php echo e(date('Y/m/d H:i')); ?> <span>時点の情報です</span></div>
		<? $reserveFirstCnt = count($reserveFirst);$reserveRegularCnt = count($reserveRegular); $reserveEtcCnt = count($reserveEtc)?>
		<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt"><?php echo e($reserveFirstCnt + $reserveRegularCnt + $reserveEtcCnt); ?></span><span class="bold">人</span> </div>
		<div class="detail">
			<div class="firstCustomer">
				<div class="label">初診 <span class="count"> <?php echo e($reserveFirstCnt); ?> </span><span class="bold">人</span></div>
				<div class="number">
				  <ul class="number_items">
				    <?php $__currentLoopData = $reserveFirst; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reserve): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					   <li <?php if($reserve->status==config('const.RESERVE_STATUS.CALLED')): ?>class="called" <?php elseif($reserve->status==config('const.RESERVE_STATUS.EXAMINE')): ?>class="examine" <?php elseif($reserve->status==config('const.RESERVE_STATUS.PAYMENT')): ?>class="payment" <?php endif; ?>><span><?php echo e($reserve->reception_no); ?></span></li>
				    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</ul>
				</div>
			</div>
			<div class="regularCustomer">
				<div class="label">再診 <span class="count"> <?php echo e($reserveRegularCnt); ?> </span><span class="bold">人</span></div>
				<div class="number">
					<ul class="number_items">
					<?php $__currentLoopData = $reserveRegular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reserve): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li <?php if($reserve->status==config('const.RESERVE_STATUS.CALLED')): ?>class="called" <?php elseif($reserve->status==config('const.RESERVE_STATUS.EXAMINE')): ?>class="examine" <?php elseif($reserve->status==config('const.RESERVE_STATUS.PAYMENT')): ?>class="payment" <?php endif; ?>><span><?php echo e($reserve->reception_no); ?></span></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="btns">
      <?php if($webTicketable==='false'): ?>
          <span class="receptionMsg" style="color:red;font-weight:900;">ただいま、受付を行っておりません。</span>
      <?php endif; ?>
      <div style="display:flex;justify-content:center;margin-top:1rem;">
        <form method="GET" action="<?php echo e(route('reserve.create',['diagnosisType'=>1])); ?>">
          <button class="btn_first"   accesskey="1"<?php if($webTicketable==='false'): ?> disabled <?php endif; ?>>初診受付</button>
        </form>
        <form method="GET" action="<?php echo e(route('reserve.create',['diagnosisType'=>2])); ?>">
          <button class="btn_regular" accesskey="2"<?php if($webTicketable==='false'): ?> disabled <?php endif; ?>>再診受付</button>
				</form>
				
      </div>
    </div>
  </div>
  <div class="notice">※ネットでの受付は午前00:00～00:00　午後00:00～00:00とさせていただきます。<br>※診療終了時刻(午前の部 00:00、午後の部 00:00)までにご来院いただけなかった方はキャンセルとさせていただきます。</div>
</main>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.common', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itc-dev/laravel_project/Clinic_Reservel_demo/reservel-web/resources/views/index.blade.php ENDPATH**/ ?>