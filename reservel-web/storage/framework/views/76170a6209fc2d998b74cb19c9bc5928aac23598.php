<?php $__env->startSection('title', 'Web受付 - '); ?>
<?php echo $__env->make('layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startSection('heading', 'Web受付'); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    var set=0;
    function double() {
    if(set==0){ set=1; } else {
      alert("只今処理中です。\nそのままお待ちください。");
    return false; }}
</script>
<?php $careTypeName = config('const.CARE_TYPE_NAME')[$request->careType]; ?>
<main>
  <div class="wrapper">
    <section>
      <div class="clinicType <?php echo e($careTypeName['class_name']); ?>"><?php echo e($careTypeName['name']); ?>受付申し込み</div>
    </section>
    <section>
      <form action="<?php echo e(route('reserve.store')); ?>" method="POST" onSubmit="return double()">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" name="careType"        value="<?php echo e($request->careType); ?>" />
        <input type="hidden" name="patient_no" value="<?php echo e($request->patient_no); ?>" />
        <input type="hidden" name="name"            value="<?php echo e($request->name); ?>" />
        <input type="hidden" name="age"             value="<?php echo e($request->age); ?>" />
        <input type="hidden" name="gender"          value="<?php echo e($request->gender); ?>" />
        <input type="hidden" name="email"           value="<?php echo e($request->email); ?>" />
        <input type="hidden" name="tel"             value="<?php echo e($request->tel); ?>" />
        <input type="hidden" name="pet_symptom"     value="<?php echo e($request->pet_symptom); ?>" />
        <div class="type"><span>受付区分</span>：<span><?php echo e($careTypeName['name']); ?></span></div>
        <dl class="form_items">
<?php if($request->careType==2 || $request->careType==9): ?>
          <dt class="required"><span>診察券番号</span></dt>
          <dd><span><?php echo e($request->patient_no); ?></span></dd>
<?php endif; ?>
          <dt class="required"><span>受診される方のお名前</span></dt>
          <dd><span><?php echo e($request->name); ?></span></dd>
          <dt class="required"><span>年齢</span></dt>
          <dd><span><?php echo e($request->age); ?>歳</span></dd>
          <dt class="required"><span>性別</span></dt>
          <dd><span><?php echo e(config('const.GENDER')[$request->gender]); ?></span></dd>
          <dt class="required"><span>メールアドレス</span></dt>
          <dd><span><?php echo e($request->email); ?></span></dd>
          <dt class="required"><label for="tel">電話番号</label></dt>
          <dd><span><?php echo e($request->tel); ?></span></dd>
          <dt><span>症状など</span></dt>
          <dd><span class="symptom"><?php echo nl2br(htmlspecialchars($request->pet_symptom)); ?></span></dd>
        </dl>
        <div class="console">
          <a href="#" class="btn_cancel" onclick="javascript:window.history.back(-1);return false;">戻　る</a>
          <button type="submit" class="btn_execution">受　付</button>
        </div>
      </form>
    </section>
  </div>
</main>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.common', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itc-dev/laravel_project/Clinic_Reservel_demo/reservel-web/resources/views/reserve/confirm.blade.php ENDPATH**/ ?>