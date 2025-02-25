<?php

declare(strict_types=1);

use Zima\Antibot\View\Components\Antibot;

return [
    /**
     * List of antibot fields which will be added to form
     * Bot is detected if these fields are not empty.
     */
    'fields' => [
        'telephone' => Antibot::TYPE_FIELD_INPUT,
        'verify' => Antibot::TYPE_FIELD_CHECKBOX,
    ],

    /**
     * Whether to allow links in form fields.
     */
    'allow_links' => false,

    /**
     * List of words for detecting bot.
     */
    'stop_list' => [
        'погиб', 'плен', 'украин', 'акци', 'скидк', 'всу', 'вооружен',
        'вооруж', 'силы', 'сил', 'солдат', 'биткоин', 'боев',
        'деньг', 'денег', 'free', 'sale', 'porn',
    ],

    /** Presets
    * Please configure your preset here
    * You can specify 'required_fields', 'content_fields' or 'allow_links' (both are optional for preset)
    * See example of preset below.
    */
    //    'feedback' => [
    //        'required_fields' => ['name', 'email'], // bot is detected if these fields are empty
    //        'content_fields' => ['description'],    // fields in which spam is searched (ignored if empty or not specified)
    //        'allow_links' => true,                  // use it if you need to overwrite global 'allow_links
    //    ],
];
