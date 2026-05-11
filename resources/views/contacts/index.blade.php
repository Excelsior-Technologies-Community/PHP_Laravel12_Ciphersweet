<!DOCTYPE html>
<html>

<head>

    <title>Secure Contacts</title>

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
            padding:40px;
        }

        .container{
            max-width:1100px;
            margin:auto;
            background:white;
            padding:30px;
            border-radius:12px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
        }

        .header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        h2{
            color:#333;
        }

        .add-btn{
            background:#4CAF50;
            color:white;
            padding:10px 18px;
            border-radius:6px;
            text-decoration:none;
            transition:0.3s;
        }

        .add-btn:hover{
            background:#3f9443;
        }

        .search-box{
            margin-bottom:20px;
            display:flex;
            gap:10px;
            align-items:center;
        }

        .search-box input{
            width:320px;
            padding:10px;
            border:1px solid #ccc;
            border-radius:6px;
            outline:none;
        }

        .search-box input:focus{
            border-color:#667eea;
        }

        .search-box button{
            padding:10px 18px;
            border:none;
            background:#667eea;
            color:white;
            border-radius:6px;
            cursor:pointer;
            transition:0.3s;
        }

        .search-box button:hover{
            background:#5563d6;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        th{
            background:#667eea;
            color:white;
            padding:14px;
            text-align:center;
        }

        td{
            padding:14px;
            text-align:center;
            border-bottom:1px solid #ddd;
        }

        tr:hover{
            background:#f5f6ff;
            transition:0.2s;
        }

        .action-buttons{
            display:flex;
            justify-content:center;
            align-items:center;
            gap:10px;
        }

        .edit-btn{
            background:orange;
            color:white;
            padding:8px 14px;
            border-radius:5px;
            text-decoration:none;
            transition:0.3s;
            font-size:14px;
        }

        .edit-btn:hover{
            background:#d98b00;
        }

        .delete-btn{
            background:red;
            color:white;
            border:none;
            padding:8px 14px;
            border-radius:5px;
            cursor:pointer;
            transition:0.3s;
            font-size:14px;
        }

        .delete-btn:hover{
            background:#d10000;
        }

        .success{
            background:#d4edda;
            color:#155724;
            padding:12px;
            border-radius:6px;
            margin-bottom:20px;
        }

        .empty{
            padding:20px;
            text-align:center;
            color:#666;
        }

        .badge{
            background:#222;
            color:#00ff88;
            padding:6px 12px;
            border-radius:20px;
            font-size:12px;
            display:inline-block;
            margin-bottom:20px;
        }

    </style>

</head>

<body>

<div class="container">

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif

    <div class="header">

        <h2>
            🔐 Secure Contacts
        </h2>

        <a href="{{ route('contacts.create') }}" class="add-btn">
            + Add Contact
        </a>

    </div>

    <div class="badge">
        AES Encrypted Storage
    </div>

    <form
        method="GET"
        action="{{ route('contacts.index') }}"
        class="search-box"
    >

        <input
            type="text"
            name="search"
            placeholder="Search encrypted email..."
            value="{{ request('search') }}"
        >

        <button type="submit">
            Search
        </button>

    </form>

    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>

        @forelse($contacts as $contact)

            <tr>

                <td>{{ $contact->id }}</td>

                <td>{{ $contact->name }}</td>

                <td>{{ $contact->email }}</td>

                <td>{{ $contact->phone }}</td>

                <td>

                    <div class="action-buttons">

                        <a
                            href="{{ route('contacts.edit',$contact->id) }}"
                            class="edit-btn"
                        >
                            Edit
                        </a>

                        <form
                            action="{{ route('contacts.delete',$contact->id) }}"
                            method="POST"
                            style="margin:0;"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="delete-btn"
                                onclick="return confirm('Delete this contact?')"
                            >
                                Delete
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5" class="empty">
                    No Contacts Found
                </td>

            </tr>

        @endforelse

    </table>

</div>

</body>
</html>