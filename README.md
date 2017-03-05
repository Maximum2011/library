Library Project powered by Yii2
============================

INSTALLATION
------------
You can get the source files from:

    $ git clone https://github.com/Maximum2011/library.git
    
And install via composer:
   
    $ composer install
 
    
CONFIGURATION
-------------

### Database
Create the file `config/db-local.php`
Edit the file with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=library',
    'username' => 'root',
    'password' => '12345',
    'charset' => 'utf8',
];
```
Run the migrations:

    $ php yii migrate/up
