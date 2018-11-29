<?php 
return [
    'module_init'=> [
        'shangtao\\home\\behavior\\InitConfig'
    ],
    'action_begin'=> [
        'shangtao\\home\\behavior\\ListenProtectedUrl'
    ]
]
?>