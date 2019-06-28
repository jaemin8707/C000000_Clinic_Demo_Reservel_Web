<?php

namespace App\Models;

use App\Mail\RemindSend;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Traits\UserBy;
use Illuminate\Support\Facades\Route;

class Reserve extends Model
{
    use UserBy;
    protected $table = 'reserves';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];

    //動物種類
    public function petType() {
        return $this->hasMany(PetType::class);
    }

    //来院目的
    public function purpose() {
        return $this->hasMany(Purpose::class);
    }

    public function formattedCallTime() {
        return (isset($this->call_time) && $this->status==config('const.RESERVE_STATUS.CALLED'))
                    ? date('H:i', strtotime($this->call_time))
                    : '--:--';
    }

    protected static function boot() {
        parent::boot();

        Reserve::updating(function ($reserve) {
            Log::Debug('Reserve::updatingイベント Start');

            if(Route::currentRouteName() == "reserve.update.status") {
                if($reserve->status == config('const.RESERVE_STATUS.CALLED')){
                  $reserve->call_time = Carbon::now();
                }

                Log::Debug('元の値：'.json_encode($reserve->getOriginal()));
                Log::Debug('更新値：'.$reserve);
            }
            Log::Debug('Reserve::updatingイベント End');

            return true; 
        });
        Reserve::updated(function ($reserve) {
            Log::Debug('Reserve::updatedイベント Start');
            if(Route::currentRouteName() == "reserve.update.status") {
                Log::Debug('元のステータス：'.$reserve->getOriginal('status'));

                if ($reserve->getOriginal('status') === config('const.RESERVE_STATUS.WAITING')){
                    Log::Debug('元の待ち受け番号：'.$reserve->getOriginal('reception_no'));

                    $remindTarget =
                        Reserve::where('status', '=', config('const.RESERVE_STATUS.WAITING'))
                                ->orderBy('reception_no')
                                ->whereDate('created_at', Carbon::today())
                                ->limit(1)
                                ->offset(2)
                                ->first();
                    Log::Debug('リマインドメール対象候補：'.$remindTarget);
                    if(isset($remindTarget->email) && $remindTarget->send_remind == false){
                        Log::Debug('リマインドメール送信');
                        Mail::to($remindTarget->email)
                            ->send(new RemindSend($remindTarget));
                        if (Mail::failures()) {
                            Log::Debug('リマインドメール送信失敗');
                        } else {
                            $remindTarget->send_remind = true;
                            $remindTarget->save();
                            Log::Debug('リマインドメール送信成功');
                        }
                    }
                }
                Log::Debug('元の値：'.json_encode($reserve->getOriginal()));
                Log::Debug('更新値：'.$reserve);
            }
            Log::Debug('Reserve::updatedイベント End');
            return true; 
        });

    }
}
