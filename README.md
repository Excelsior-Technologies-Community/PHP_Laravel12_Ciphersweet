# PHP_Laravel12_Ciphersweet


## Project Description

PHP_Laravel12_Ciphersweet is a simple Laravel 12 web application that demonstrates how to securely store sensitive information in a database using field-level encryption.

The project uses the Laravel CipherSweet package to automatically encrypt sensitive fields such as email and phone numbers before saving them to the database. When the data is retrieved from the database, it is automatically decrypted and displayed in readable format.

This project helps developers understand how to implement secure data encryption in Laravel applications to protect confidential user information.



## Features

- Secure encryption of sensitive data before storing it in the database

- Automatic decryption when retrieving data from the database

- Use of Blind Indexes for searchable encrypted fields

- Simple form to add secure contacts

- Display encrypted records in a user-friendly table

- Clean and simple Laravel MVC structure

- Demonstrates best practices for protecting sensitive data



## Technology Stack

1. PHP 8.2 – Server-side language

2. Laravel 12 – Web framework

3. MySQL – Database

4. CipherSweet – Field-level encryption

5. HTML / CSS / Blade – Frontend



---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Ciphersweet "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Ciphersweet

```

#### Explanation:

This command installs a fresh Laravel 12 application using Composer and creates a new project folder named PHP_Laravel12_Ciphersweet.

The cd command moves into the project directory so development can begin.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Ciphersweet
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Ciphersweet

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

The .env file is updated with MySQL database credentials so the Laravel application can connect to the database.

Running php artisan migrate creates the default Laravel tables required by the framework.





## STEP 3: Install Laravel CipherSweet Package

### Install package:

```
composer require spatie/laravel-ciphersweet

```

### Publish migration

```
php artisan vendor:publish --tag="ciphersweet-migrations"

```

### Then Run:

```
php artisan migrate

```


### Optional config publish

```
php artisan vendor:publish --tag="ciphersweet-config"

```

#### Explanation:

This command installs the CipherSweet encryption package which allows sensitive database fields to be securely encrypted.

Publishing the migrations and config files prepares the database and settings needed for encrypted data storage.





## STEP 4: Generate Encryption Key

### Run:

```
php artisan ciphersweet:generate-key

```

### Example output:

```
Generated key: abc123xyz...

```

### Add to .env

```
CIPHERSWEET_KEY=abc123xyz...

```

#### Explanation:

This command generates a secure encryption key used by CipherSweet to encrypt and decrypt database fields.

The generated key must be stored in the .env file as CIPHERSWEET_KEY.





## STEP 5: Create Model, Migration and Controller

### We will create secure_contacts table:

```
php artisan make:model SecureContact -mcr

```

### This creates

```
app/Models/SecureContact.php
database/migrations/create_secure_contacts_table.php
app/Http/Controllers/SecureContactController.php

```

### Explanation:

This command creates a Model, Migration, and Controller for the SecureContact resource.

These files will handle the database structure, business logic, and HTTP requests.





## STEP 6: Set Migration and Model

### Edit migration: database/migrations/create_secure_contacts_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('secure_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // encrypted columns must be TEXT
            $table->text('email');
            $table->text('phone');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secure_contacts');
    }
};

```

### Then Run:

```
php artisan migrate

```



### Edit: app/Models/SecureContact.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use ParagonIE\CipherSweet\EncryptedRow;
use ParagonIE\CipherSweet\BlindIndex;

class SecureContact extends Model implements CipherSweetEncrypted
{
    use UsesCipherSweet;

    protected $fillable = [
        'name',
        'email',
        'phone'
    ];

    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow
            ->addField('email')
            ->addBlindIndex('email', new BlindIndex('email_index'))

            ->addField('phone')
            ->addBlindIndex('phone', new BlindIndex('phone_index'));
    }
}

```

#### Explanation:

The migration defines the secure_contacts table structure where encrypted data will be stored.

The model configures CipherSweet encryption so fields like email and phone are automatically encrypted and decrypted.





## STEP 7: Encrypt Existing Data

### Run:

```
php artisan ciphersweet:encrypt "App\Models\SecureContact" YOUR_KEY

```

### Example

```
php artisan ciphersweet:encrypt "App\Models\SecureContact" abc123xyz

```

#### Explanation:

This command encrypts any existing plain-text data in the database using the CipherSweet encryption key.

It ensures that previously stored records are converted into secure encrypted values.





## STEP 8: Set Controller

### Open: app/Http/Controllers/SecureContactController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecureContact;

class SecureContactController extends Controller
{
    public function index()
    {
        $contacts = SecureContact::all();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        SecureContact::create($request->all());

        return redirect()->route('contacts.index');
    }
}

```

#### Explanation:

The controller handles application logic such as displaying contacts, showing the create form, and storing new records.

When data is saved through the model, CipherSweet automatically encrypts the configured fields.






## STEP 9: Set Routes

### routes/web.php

```
use App\Http\Controllers\SecureContactController;

Route::get('/', [SecureContactController::class,'index'])->name('contacts.index');
Route::get('/create',[SecureContactController::class,'create'])->name('contacts.create');
Route::post('/store',[SecureContactController::class,'store'])->name('contacts.store');

```

#### Explanation:

Routes define the URLs that users can access in the application.

These routes connect browser requests to the appropriate controller methods.




## STEP 10: Create Views

### Create folder

```
resources/views/contacts

```

### resources/views/contacts/create.blade.php

```
<!DOCTYPE html>
<html>

<head>

    <title>Add Secure Contact</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            color: #444;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            background: #667eea;
            border: none;
            padding: 12px;
            color: white;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #5563d6;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #667eea;
            font-size: 14px;
        }

        .back:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>

    <div class="card">

        <h2>Add Secure Contact</h2>

        <form method="POST" action="{{ route('contacts.store') }}">

            @csrf

            <label>Name</label>
            <input type="text" name="name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Phone</label>
            <input type="text" name="phone" required>

            <button type="submit">
                Save Contact
            </button>

        </form>

        <a href="{{ route('contacts.index') }}" class="back">
            ← Back to Contacts
        </a>

    </div>

</body>

</html>

```


### resources/views/contacts/index.blade.php

```
<!DOCTYPE html>
<html>

<head>

    <title>Secure Contacts</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h2 {
            color: #333;
        }

        .add-btn {
            background: #4CAF50;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .add-btn:hover {
            background: #3d8b40;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: center;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f5f6ff;
            transition: 0.2s;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="header">
            <h2>🔐 Secure Contacts</h2>

            <a href="{{ route('contacts.create') }}" class="add-btn">
                + Add Contact
            </a>
        </div>

        <table>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>

            @if($contacts->count() > 0)

                @foreach($contacts as $contact)

                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone }}</td>
                    </tr>

                @endforeach

            @else

                <tr>
                    <td colspan="4" class="empty">No Contacts Found</td>
                </tr>

            @endif

        </table>

    </div>

</body>

</html>

```

#### Explanation:

Views are the frontend pages where users can add and view secure contacts.

Blade templates are used to display decrypted data retrieved from the database.






## STEP 11: Test the Application

### Start Laravel dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

The php artisan serve command starts the local Laravel development server.

Opening the provided URL in a browser allows testing the secure contact creation and encrypted storage.





## Expected Output:


### Home Page:


<img src="screenshots/Screenshot 2026-03-13 143439.png" width="900">


### Add Secure Contact Page:


<img src="screenshots/Screenshot 2026-03-13 144619.png" width="900">


### After Add Secure Contact:


<img src="screenshots/Screenshot 2026-03-13 144638.png" width="900">



---

# Project Folder Structure:

```
PHP_Laravel12_Ciphersweet
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── SecureContactController.php
│   │
│   ├── Models
│   │   └── SecureContact.php
│   │
│   └── Providers
│
├── bootstrap
│   └── app.php
│
├── config
│   ├── app.php
│   ├── database.php
│   └── ciphersweet.php
│
├── database
│   ├── factories
│   │
│   ├── migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   └── xxxx_xx_xx_create_secure_contacts_table.php
│   │
│   └── seeders
│
├── public
│   └── index.php
│
├── resources
│   ├── views
│   │   └── contacts
│   │       ├── index.blade.php
│   │       └── create.blade.php
│   │
│   ├── css
│   └── js
│
├── routes
│   └── web.php
│
├── storage
│
├── tests
│
├── vendor
│
├── .env
├── artisan
├── composer.json
├── composer.lock
└── README.md

```
