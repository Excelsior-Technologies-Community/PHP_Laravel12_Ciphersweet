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