<?php $__env->startSection('title', '受付状況一覧 - '); ?>
<?php $__env->startSection('refresh'); ?>
<meta http-equiv="refresh" content="120">
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startSection('heading', '受付状況'); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main>
    <div class="wrapper">
        <section>
            
            <div class="tools">
                <div class="counter">
                    <span class="time"><?php echo e(date('Y/m/d H:i')); ?></span>
                    <span>現在の待ち人数</span><span id="totalCnt"><?php echo e($waitCnt); ?></span><span>人</span>
                </div>
                <div class="console_reserve_button">
                    <form method="POST" action="<?php echo e(route('reserve.create',['diagnosisType'=>1])); ?>" onsubmit="getScroll()">
                    <?php echo csrf_field(); ?>
                      <input type="hidden" name="_method" value="PUT">
                      <button class="btn_first_reserve" onclick="return doubleClick()" accesskey="1">初診受付</button>
                    </form>
          
                    <form method="POST" action="<?php echo e(route('reserve.create',['diagnosisType'=>2])); ?>" onsubmit="getScroll()">
                      <?php echo csrf_field(); ?>
                        <input type="hidden" name="_method" value="PUT">
                        <button type="button" class="btn_repeat_reserve btn_reserve_repeat"   accesskey="2"><span>再診受付</span></button>
                        <div class="reserve_repeat_modal">
                          <label>診察券番号</label>
                          <input type="text" name="patient_no" value="">
                          <input class="submit_button"type="submit" onclick="return doubleClick()" value="受付">
                          <input class="close_button" type="button" value="閉じる" name="close">
                        </div>
                      </form>

                </div> 
                <div class="console_ticketable">
                    <form method="POST" action="<?php echo e(route('setting.update.tabTicketable')); ?>">
                        <input type="hidden" name="_method" value="PUT">
                        <?php echo csrf_field(); ?>
                        <? $tabTicketButton = config('const.SETTINGBUTTON_BY_TABTICKETABLE')[$tabTicketable]; ?>
                        <button class="<?php echo e($tabTicketButton['CSS']); ?>" onclick="return doubleClick()" type="submit" name="tabTicketable" value='<?php echo e($tabTicketButton['VALUE']); ?>'><?php echo e($tabTicketButton['TEXT']); ?></button>
                    </form>
                    <form method="POST" action="<?php echo e(route('setting.update.webTicketable')); ?>">
                        <input type="hidden" name="_method" value="PUT">
                        <?php echo csrf_field(); ?>
                        <? $webTicketButton = config('const.SETTINGBUTTON_BY_WEBTICKETABLE')[$webTicketable]; ?>
                        <button class="<?php echo e($webTicketButton['CSS']); ?>" onclick="return doubleClick()" type="submit" name="webTicketable" value='<?php echo e($webTicketButton['VALUE']); ?>'><?php echo e($webTicketButton['TEXT']); ?></button>
                    </form>
                </div>
                <!-- <div class="csv_dl">
                    CSVダウンロード
                </div> -->
            </div>
<!--
            <div class="console" style="margin:10px auto;">
                <form action="/find/ITC" method="GET">
                    <dl>
                        <dt>状態</dt>
                        <dd></dd>
                    </dl>
                        <dd><button >すべて</button></dd>
                        <dd><button >待ち</button></dd>
                        <dd><button >呼出中</button></dd>
                        <dd><button >診察中</button></dd>
                        <dd><button >完了</button></dd>
                        <dd><button >キャンセル</button></dd>
                        <dd><button >完了、キャンセル以外</button></dd>
                </form>
            </div>
 -->
      <div class="list_header">
        <span class="a_number">受付<br/>番号</span>
        <span class="a_type">受付区分</span><!-- 6% -->
        <span class="a_status">現在の状態</span>
        <span class="a_status_edit">状態の変更</span>
        <span class="a_remind">リマインドメール</span>
        <span class="a_reserve_time">呼出時刻</span>
        <span class="a_patient_no">診察券番号</span>
        <span class="a_name">受信される方のお名前</span>
        <span class="a_age">年齢</span>
        <span class="a_gender">性別</span>
        <span class="a_button_area"> </span> 
      </div>
      <ul class="list">
      <?php if(isset($reserves) && count($reserves)>0): ?>
        <?php $__currentLoopData = $reserves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reserve): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span id="reserve_id_<?php echo e($reserve->id); ?>"></span>
        <li>
          <div class="a_number"><?php echo e($reserve->reception_no); ?></div>
          <div class="a_type"><?php echo e(config('const.CARE_TYPE_NAME')[$reserve->care_type]['name']); ?></div>
          <?php if($reserve->status >= config('const.RESERVE_STATUS.WAITING')): ?>
          <div class="a_status"><span><?php echo e(config('const.CURRENTSTATUS_STRING')[$reserve->status]); ?></span></div>
          <?php else: ?>
          <div class="a_status"><?php echo e(config('const.CURRENTSTATUS_STRING')[$reserve->status]); ?></div>
          <?php endif; ?>
          <div class="a_status_edit">
            <?php if(in_array($reserve->status,[config('const.RESERVE_STATUS.WAITING'),config('const.RESERVE_STATUS.CALLED'),config('const.RESERVE_STATUS.EXAMINE'),config('const.RESERVE_STATUS.PAYMENT')], true)): ?>
            <div class="console_status">
              <form method="POST" action="<?php echo e(route('reserve.update.status',['reserve'=>$reserve->id])); ?>" onsubmit="getScroll()">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" value="PUT">
                <? $nextStatusButton = config('const.NEXTBUTTON_BY_STATUS')[$reserve->status]; ?>
                <button class="<?php echo e($nextStatusButton['CSS']); ?>" type="submit" onclick="return doubleClick()" name="status" value="<?php echo e($nextStatusButton['VALUE']); ?>"><?php echo e($nextStatusButton['TEXT']); ?></button>
              </form>
            </div>
            <?php endif; ?>
          </div>

          <div class="a_remind">
            <div class="console_remind">
                <form method="POST" action="<?php echo e(route('reserve.remind.send',['reserve'=>$reserve->id])); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_method" value="PUT">
                    <?php if($reserve->email && $reserve->status != -1): ?>
                      <?php if($reserve->send_remind == 0): ?>
                      <button class="btn_remind" type="submit" name="send" onclick="return doubleClick()" value="send">送信</button>
                      <?php else: ?>
                      送信済
                      <?php endif; ?>
                    <?php endif; ?>
                </form>
            </div>
          </div>

          <div class="a_reserve_time"><?php echo e($reserve->formattedCallTime()); ?></div>
          <div class="a_patient_no"><?php echo e($reserve->medical_card_no); ?></div>
          <div class="a_name"><span><?php echo e($reserve->name); ?></span>
            <div class="modal">
              <form method="POST" action="<?php echo e(route('reserve.update.name',['reserve'=>$reserve->id])); ?>" onsubmit="getScroll()">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="_method" value="PUT">
              <input type="text" name="name" value="<?php echo e(old('name', $reserve->name)); ?>">
              <input class="close_button" type="button" value="閉じる" name="close">
                  <input class="submit_button"type="submit" onclick="return doubleClick()" value="更新">
              </form>
            </div>
          </div>
          <div class="a_age"><span><?php echo e($reserve->age); ?></span></div>
          <div class="a_gender"><span><?php if(isset($reserve->gender)): ?> <?php echo e(config('const.GENDER')[$reserve->gender]); ?> <?php endif; ?></span></div>
          <div class="a_button_area"><a href="<?php echo e(route('reserve.edit',['reserve'=>$reserve->id])); ?>" onClick="aTagGetScroll()">詳細</a></div>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php else: ?>
        <li><div>本日の待ち患者は、まだいません。</div></li>
      <?php endif; ?>
      </ul>
      <div class="console_closed">
        <a href="<?php echo e(route('closed.index')); ?>">
          <button class="btn_closed" type="submit" value="true">休診日</button>
        </a>
      </div>
    </section>
  </div>
</main>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
div.console button {margin:5px;background:honeydew;color:black;}
</style>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script>
    $(function(){
      $('.a_name span').on('click',function(e){
          $('.modal').fadeOut();
          $(this).next('.modal').fadeIn(200);
      });
      $('input[type="submit"]').on('click',function(){
          $(this).parent('.modal').fadeOut(200);
      });
    $('.close_button').on('click',function(){
      $('.modal').fadeOut(200);
    });

    $('.btn_reserve_repeat').on('click',function(){
      $('.btn_reserve_repeat').hide();
      $(this).next('.reserve_repeat_modal').fadeIn(200);

    });
    $('.reserve_repeat_modal input[type="submit"]').on('click',function(){
      $(this).parent('.reserve_repeat_modal').fadeOut(200);
    });
    $('.reserve_repeat_modal .close_button').on('click',function(){
      $('.reserve_repeat_modal').hide();
      $('.btn_reserve_repeat').fadeIn(200);
    });

    $('.btn_reserve_etc').on('click',function(){
      $('.btn_reserve_etc').hide();
      $(this).next('.reserve_etc_modal').fadeIn(200);

    });
    $('.reserve_etc_modal input[type="submit"]').on('click',function(){
      $(this).parent('.reserve_etc_modal').fadeOut(200);
    });
    $('.reserve_etc_modal .close_button').on('click',function(){
      $('.reserve_etc_modal').hide();
      $('.btn_reserve_etc').fadeIn(200);
    });

    $('.status').change(function() {
      var val = $(this).val();
      var target01 = $(this).parent().next().children('.a_timer_nocount');
      var target02 = $(this).parent().next().children('.a_timer_count'); 
      if(val == 20){
        $(target01).hide(); 
        $(target02).fadeIn(400);
        console.log(target);
      } else {
        $(target01).show();
        $(target02).hide();
      }
    });
  });
  function aTagGetScroll() {
    event.target.href += "?scroll=" + window.pageYOffset;
  }
  function getScroll() {
          event.target.action += "?scroll=" + window.pageYOffset;
      };
  window.onload = function() {
    var scroll = 0;
    <?php if(session('scroll') != null): ?>
      scroll = <?php echo e(session('scroll')); ?>;
    <?php elseif($scroll != 0): ?>
      scroll = <?php echo e($scroll); ?>;
    <?php endif; ?>
    window.scrollTo(0, scroll);
  };
  function doubleClick() {
    if(typeof pressed != "undefined"){
      return false;
    }
    pressed=1;
  }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.common', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itc-dev/laravel_project/Clinic_Reservel_demo/reservel-admin/resources/views/reserve/index.blade.php ENDPATH**/ ?>