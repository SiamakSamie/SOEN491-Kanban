
# SOEN491-Kanban

A Kanban package that integrates with any Laravel project

<br>
<hr>

## Installation the package with composer

Not implemented but soon we can run a command such as:

```bash
composer require SiamakSamie/Kanban
```

And then immediately have it running. I will first have to upload the package on packagist.com. 

<br>
<hr>

## Development Installation Method


**1:** First create a directory called package, and clone the project inside of it. 


**2:** Then, add line of code in the psr-4 of your root composer.json
```
"psr-4": {
    //...
    "SiamakSamie\\Kanban\\": "package/kanban/src/"
},
```
**3:** Add the Kanban Service provider to the config/app.php

```php
return [
    //...
    "providers" => [
        //...
        SiamakSamie\Kanban\KanbanServiceProvider::class,
    ]
];

```

**4:** run this command to allow laravel to find the new package
```bash
composer dump-autoload
```

**5:** Inside the Kanban project, run (as we would with any laravel application)
```bash
composer install
npm install
npm run dev
```

**6:** This is important! Any changes to the frontend (vue or scss) needs to be "published". 
Laravel will look inside the vendor folder for the frontend data so any time we make a change we have to run the following command in the root of the project. 
```bash
php artisan vendor:publish --provider="SiamakSamie\Kanban\KanbanServiceProvider" --force
```

**7:** Migrate and seed the database!

```bash
php artisan migrate:fresh --seed
```
<br>
