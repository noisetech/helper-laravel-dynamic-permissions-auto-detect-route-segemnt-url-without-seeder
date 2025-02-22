Cara pakai 

laravel 10
install juga laravel ui auth
install laravel spatie 


set up database kalian 
kemudian jalankan migration seperti biasanya.

seletah terpenuhi

kalian buatkan folder helper didalamnya isikan file helper.php
lalu silahkan copas koding yang di file tersebut.


edit bagian composer.json

 "autoload": {
            "psr-4": {
                "App\\": "app/",
                "Database\\Factories\\": "database/factories/",
                "Database\\Seeders\\": "database/seeders/"
            },
            "files": [
                "app/Helpers/helpers.php"
             ]
        },


kemudian buka terminal jalankan composer  dump-autoload

untuk menggunakan pengecekan button yang terdapat params ataupun tidak bisa sebagai berikut

contoh yang button ada params nya (bisa lebih dari satu params):

     {!! getActionButtons([
    [
    'action' => 'edit',
    'class' => 'badge bg-warning text-white',
    'params' => ['id']
    ],
    [
    'action' => 'hapus',
    'class' => 'badge mx-1 bg-danger text-white',
    'params' => ['id']
    ],
    [
    'action' => 'detail',
    'class' => 'badge mx-1 bg-info text-white',
    'params' => ['id', 'type', 'semester']
    ],
    ], [
    'id' => $p->id,
    ]) !!}




    contoh yang button tidak ada params:
 
     {!! getActionButtons([
    [
    'action' => 'tambah',
    'class' => 'badge bg-primary text-white'
    ]
    ])!!}
