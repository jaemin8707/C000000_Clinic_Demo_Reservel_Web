<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformationController extends Controller {
    //

    /**
      * メール案内ページ
      * 
      * @return view
      * 
      **/
    public function mail() {
			return view('information.mail');
		}
}
