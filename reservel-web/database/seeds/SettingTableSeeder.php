<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        App\Models\Setting::create(
                            [
                                'id' => '1',
                                'code' => 'tabTicketable',
                                'value' => 'false',
                                'description' => 'タブレット発番可否(営業時間内のみ発番可能)',
                            ]);
        App\Models\Setting::create(
            [
                'id' => '2',
                'code' => 'webTicketable',
                'value' => 'false',
                'description' => 'WEB発番可否(営業時間内のみ発番可能)',
            ]);
    }
}
