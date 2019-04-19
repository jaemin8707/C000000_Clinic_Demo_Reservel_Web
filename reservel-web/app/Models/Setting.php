<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
		protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    //
    public $opTime1Start;
    public $opTime1End;
    public $opTime2Start;
    public $opTime2End;

    /**
     * コンストラクタ
     *
     * @param array $attributes
     *
     */
    public function __construct(array $attributes = []) 
    {
        parent::__construct($attributes);
        $this->opTime1Start = strtotime(env('OP_TIME1_START'));
        $this->opTime1End = strtotime(env('OP_TIME1_END'));
        $this->opTime2Start = strtotime(env('OP_TIME2_START'));
				$this->opTime2End = strtotime(env('OP_TIME2_END'));
		}
    /**
     * 診察時間判定
     *
     * @return bool
     *
     */
    public function inExamineTime() 
    {
				$now = Carbon::now();
				$time = strtotime($now);
        return (
            ($this->opTime1Start < $time && $time < $this->opTime1End)
            || ($this->opTime2Start < $time && $time < $this->opTime2End));
    }
}
