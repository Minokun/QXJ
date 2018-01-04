<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
//         'urlManager' => [
//             'enablePrettyUrl' => true,
//             'showScriptName' => false,
//             'suffix' => "",
//             'rules' => [
// //                  "<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>" => "<module>/<controller>/<action>",
// //                  "<controller:\w+>/<action:\w+>/<id:\d+>" => "<controller>/<action>",
// //                  "<controller:\w+>/<action:\w+>" => "<controller>/<action>"
//             ],
//         ],
    ],
];
