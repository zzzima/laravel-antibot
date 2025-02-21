<?php

declare(strict_types=1);

use Zima\Antibot\View\Components\Antibot;

return [
    // List of antibot fields which will be added to form
    'fields' => [
        'telephone' => Antibot::TYPE_FIELD_INPUT,
        'verify' => Antibot::TYPE_FIELD_CHECKBOX,
    ],

    // Whether to allow links in form fields
    'allow_links' => false,

    // List of words for detecting bot
    'stop_list' => [
        'погиб', 'плен', 'украин', 'акци', 'скидк', 'всу', 'вооружен',
        'вооруж', 'силы', 'сил', 'солдат', 'биткоин', 'помощь', 'помощ', 'боев',
        'деньг', 'денег', 'free', 'sale', 'porn',
    ],
];
