<?php

namespace App\Http\Controllers;

use App\Models\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * 発券可否制御処理(tab)
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateTabTicketable(Request $request) {

        Log::Debug('発券可否制御処理(tab) Start');

        Setting::where("code","=","tabTicketable")
               ->update(["value" => $request->tabTicketable]);

        Log::Debug('発券可否制御処理(tab) End');

        return redirect(route('reserve.index'));
    }

    /**
     * 発券可否制御処理(web)
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateWebTicketable(Request $request) {

        Log::Debug('発券可否制御処理(web) Start');

        Setting::where("code","=","webTicketable")
               ->update(["value" => $request->webTicketable]);

        Log::Debug('発券可否制御処理(web) End');

        return redirect(route('reserve.index'));
    }
}
