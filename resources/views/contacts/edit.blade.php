<!DOCTYPE html>
<html>

<head>

    <title>Edit Secure Contact</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            background:linear-gradient(135deg,#667eea,#764ba2);
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .card{
            background:white;
            width:420px;
            padding:35px;
            border-radius:12px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
        }

        h2{
            text-align:center;
            margin-bottom:20px;
            color:#333;
        }

        label{
            font-size:14px;
            font-weight:500;
            color:#444;
        }

        input{
            width:100%;
            padding:12px;
            margin-top:6px;
            margin-bottom:18px;
            border:1px solid #ddd;
            border-radius:6px;
        }

        button{
            width:100%;
            background:#667eea;
            border:none;
            padding:12px;
            color:white;
            border-radius:6px;
            cursor:pointer;
            font-size:15px;
        }

        button:hover{
            background:#5563d6;
        }

        .back{
            display:block;
            margin-top:15px;
            text-align:center;
            text-decoration:none;
            color:#667eea;
        }

    </style>

</head>

<body>

<div class="card">

    <h2>✏ Edit Secure Contact</h2>

    <form
        method="POST"
        action="{{ route('contacts.update',$contact->id) }}"
    >

        @csrf
        @method('PUT')

        <label>Name</label>

        <input
            type="text"
            name="name"
            value="{{ $contact->name }}"
        >

        <label>Email</label>

        <input
            type="email"
            name="email"
            value="{{ $contact->email }}"
        >

        <label>Phone</label>

        <input
            type="text"
            name="phone"
            value="{{ $contact->phone }}"
        >

        <button type="submit">
            Update Contact
        </button>

    </form>

    <a href="{{ route('contacts.index') }}" class="back">
        ← Back to Contacts
    </a>

</div>

</body>
</html>