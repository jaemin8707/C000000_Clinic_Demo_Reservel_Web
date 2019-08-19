<?php

return [
    'PLACE_NAME' => [
        '1'               => '院内',
        '2'               => '院外',
    ],
    'PLACE' => [
        'IN_HOSPITAL'     => 1,
        'OUT_HOSPITAL'    => 2,
    ],
    'CARE_TYPE_NAME' => [
        '1' => [
            'name'        => '初診',
            'class_name'  => 'clinicFirst'
               ],
        '2' => [
            'name'        => '再診',
            'class_name'  => 'clinicRepeat'
               ]
    ],
    'CARE_TYPE' => [
        'FIRST'           => 1,
        'REGULAR'         => 2,
    ],

    'RESERVE_STATUS' => [
        'WAITING'               => 10,
        'CALLED'                => 20,
        'EXAMINE'               => 30,
        'DONE'                  => 40,
        'CANCEL_BY_PATIANT'     => -1,
        'CANCEL_BY_HOSPITAL'    => -2,
        'CALLED_TIMEUP_CANCEL'  => -3,
        'EXAMINE_TIMEUP_CANCEL' => -4,
    ],
    'NEXTBUTTON_BY_STATUS' => [
        10 => [
            'TEXT'    => '呼出済みに変更',
            'CSS'     => 'btn_status_call',
            'VALUE'   => 20,
        ],
        20 => [
            'TEXT'    => '診察中に変更',
            'CSS'     => 'btn_status_examination',
            'VALUE'   => 30,
        ],
        30 => [
            'TEXT'    => '完了に変更',
            'CSS'     => 'btn_status',
            'VALUE'   => 40,
        ],

    ],
    'CURRENTSTATUS_STRING' => [
        10 => '待ち' ,
        20 => '呼出済み' ,
        30 => '診察中' ,
        40 => '完了' ,
        -1 => 'キャンセル(患者)' ,
        -2 => 'キャンセル(病院)' ,
        -3 => 'キャンセル(自動)' ,
        -4 => 'キャンセル(自動)',
    ],
    'PET_TYPE_NAME' => [
        1 => '犬',
        2 => '猫',
    ],
    'PET_TYPE' => [
        'DOG' => 1,
        'CAT' => 2,
        'RABBIT' => 3,
        'OTHER' => 99,
    ],
    'PURPOSE' => [
        1 => '診察',
        2 => '予防薬',
        3 => 'フード',
        99 => 'その他'
    ],
    'SETTINGBUTTON_BY_TABTICKETABLE' => [
        'true'  => [
            'TEXT'    => '院内予約受付を一時中断する',
            'CSS'     => 'btn_able',
            'VALUE'   => 'false',
        ],
        'false' =>  [
            'TEXT'    => '院内予約受付を再開する',
            'CSS'     => 'btn_disable',
            'VALUE'   => 'true',
        ],
    ],
    'SETTINGBUTTON_BY_WEBTICKETABLE' => [
        'true'  => [
            'TEXT'    => 'WEB予約受付を一時中断する',
            'CSS'     => 'btn_able',
            'VALUE'   => 'false',
        ],
        'false' =>  [
            'TEXT'    => 'WEB予約受付を再開する',
            'CSS'     => 'btn_disable',
            'VALUE'   => 'true',
        ],
    ],
    'RESERVE_MAIL_TITLE' => env('RESERVE_MAIL_TITLE', '[サンプル動物病院]ご予約を受付けしました'),
    'REMIND_MAIL_TITLE'  => env('REMIND_MAIL_TITLE' , '[サンプル動物病院]まもなく診察の予定です'),

    'USER_ID' => [
      'api.reserve.numbering' => '-101',
      'reserve.create' => '-102',
      'reserve.store' => '-102',
      'reserve.cancel_complete' => '-102',
    ],
    'CLOSED_TYPE' => [
        'MORNING' => '1',
            'AFTERNOON' => '2',
            'ALL_DAY' => '3'
        ],
        'CLOSED_TYPE_NAME' => [
            '1' => '午前',
            '2' => '午後',
            '3' => '全日'
        ],
        'DAY_OF_WEEK' => [
            '1' => '月曜日',
            '2' => '火曜日',
            '3' => '水曜日',
            '4' => '木曜日',
            '5' => '金曜日',
            '6' => '土曜日',
            '7' => '日曜日',
        ],
        'DAY_OF_WEEK_DATE' => [
            '0' => '日',
            '1' => '月',
            '2' => '火',
            '3' => '水',
            '4' => '木',
            '5' => '金',
            '6' => '土',
        ],
        'DAY_OF_WEEK_NAME' => [
            'MONDAY'   => '1',
            'TUESDAY'  => '2',
            'WEDNESDAY'=> '3',
            'THURSDAY' => '4',
            'FRIDAY'   => '5',
            'SATURDAY' => '6',
            'SUNDAY'   => '7',
        ],
        'CLOSED_CREATE_TYPE' => [
            'WEEK' => '1',
            'DAY' => '2'
        ],
        'RESERVE_START_TIME_WEB' => [
            '1' => '08:50',
            '2' => '15:50',
        ],
        'RESERVE_START_TIME_TAB' => [
            '1' => '08:50',
            '2' => '15:50',
        ],
];
