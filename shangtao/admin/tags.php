<?php 
return [
    'module_init'=> [
        'shangtao\\admin\\behavior\\InitConfig'
    ],
    'action_begin'=> [
        'shangtao\\admin\\behavior\\ListenLoginStatus',
        'shangtao\\admin\\behavior\\ListenPrivilege',
        'shangtao\\admin\\behavior\\ListenOperate'
    ]
]
?>