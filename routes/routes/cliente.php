<?php

Route::resource('clientes', 'ClienteController')->parameters([
    'clientes' => 'cliente'
])->only(['index','store','update']);

 ?>
