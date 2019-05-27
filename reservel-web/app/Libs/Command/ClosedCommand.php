<?php

namespace App\Libs\Command;

use App\Models\Setting;
use App\Models\Closed;

use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClosedCommand extends Command
{

	/**
	 * 休診日・休診区分取得
	 * 
	 * 
	 * @return boolean|array
	 */
	public static function checkClosedDate($terminalTime)
	{
			$date = Carbon::now();
			$today = $date->format('Y-m-d');
			DB::enableQueryLog();
			$closed = Closed::where(['closed_day'=> date($today)])
												->select(['closed_day', 'closed_type'])
												->first();
												
			if ( isset($closed) && 
			($closed->closed_type == 3 
			|| (Carbon::parse($terminalTime[$closed->closed_type]) <= $date
					&& Carbon::parse($terminalTime[$closed->closed_type])->addMinutes(5) >= $date))){
				return $closed;
			}
			return false;
	}
}
