<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Contacts</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f5f7fb;
            padding: 30px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        h1 {
            font-size: 22px;
            color: #1a1a2e;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn-add, .btn-export {
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-add {
            background: #1967d2;
        }

        .btn-add:hover {
            background: #1557b0;
        }

        .btn-export {
            background: #28a745;
        }

        .btn-export:hover {
            background: #218838;
        }

        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .search-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }

        .search-group {
            flex: 1;
            min-width: 180px;
        }

        .search-group label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .search-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-group input:focus {
            outline: none;
            border-color: #1967d2;
        }

        .search-group button {
            background: #1967d2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .search-group button:hover {
            background: #1557b0;
        }

        .clear-btn {
            color: #666;
            text-decoration: none;
            font-size: 13px;
            margin-top: 10px;
            display: inline-block;
        }

        .stats {
            margin-bottom: 15px;
            font-size: 14px;
            color: #666;
        }

        .table-wrapper {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 14px 16px;
            background: #f8f9fa;
            font-weight: 600;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        tr:hover {
            background: #fafbfc;
        }

        .empty-row td {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .reveal-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .reveal-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .reveal-btn:hover {
            opacity: 1;
        }

        .btn-edit {
            background: #ffc107;
            color: #333;
            padding: 5px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            margin-right: 8px;
            display: inline-block;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .pagination {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .pagination a, .pagination span {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: #1967d2;
            font-size: 13px;
        }

        .pagination a:hover {
            background: #e8f0fe;
        }

        .pagination .active {
            background: #1967d2;
            color: white;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
        }

        .modal-box h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .modal-box input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .modal-actions button {
            flex: 1;
            padding: 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .modal-cancel {
            background: #eee;
            color: #333;
        }

        .modal-confirm {
            background: #28a745;
            color: white;
        }

        @media (max-width: 700px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            th, td {
                padding: 10px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container" x-data="{ exportModalOpen: false, exportPassword: '' }">
        <div class="header">
            <div>
                <h1>Secure Contacts</h1>
            </div>
            <div class="header-actions">
                <button class="btn-export" @click="exportModalOpen = true">⬇ Export</button>
                <a href="{{ route('contacts.create') }}" class="btn-add">+ New Contact</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="search-box">
            <form method="GET" action="{{ route('contacts.index') }}" class="search-form">
                <div class="search-group">
                    <label>Name</label>
                    <input type="text" name="search_name" placeholder="Search by name..." value="{{ request('search_name') }}">
                </div>
                <div class="search-group">
                    <label>Email (encrypted, prefix search)</label>
                    <input type="text" name="search_email" placeholder="Search by email..." value="{{ request('search_email') }}">
                </div>
                <div class="search-group">
                    <label>Phone (encrypted, prefix search)</label>
                    <input type="text" name="search_phone" placeholder="Search by phone..." value="{{ request('search_phone') }}">
                </div>
                <div class="search-group">
                    <button type="submit">Search</button>
                </div>
            </form>
            @if(request('search_name') || request('search_email') || request('search_phone'))
                <a href="{{ route('contacts.index') }}" class="clear-btn">✕ Clear filters</a>
            @endif
        </div>

        <div class="stats">
            Total: {{ $contacts->total() }} contacts
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr x-data="{ emailRevealed: false, phoneRevealed: false }">
                            <td>{{ $contact->id }}</td>
                            <td>{{ $contact->name }}</td>
                            <td>
                                <div class="reveal-cell">
                                    <span x-show="!emailRevealed">{{ \App\Models\SecureContact::maskEmail($contact->email) }}</span>
                                    <span x-show="emailRevealed" x-cloak>{{ $contact->email }}</span>
                                    <button type="button" class="reveal-btn" @click="emailRevealed = !emailRevealed" x-text="emailRevealed ? '🙈' : '👁'"></button>
                                </div>
                            </td>
                            <td>
                                <div class="reveal-cell">
                                    <span x-show="!phoneRevealed">{{ \App\Models\SecureContact::maskPhone($contact->phone) }}</span>
                                    <span x-show="phoneRevealed" x-cloak>{{ $contact->phone }}</span>
                                    <button type="button" class="reveal-btn" @click="phoneRevealed = !phoneRevealed" x-text="phoneRevealed ? '🙈' : '👁'"></button>
                                </div>
                            </td>
                            <td>{{ $contact->created_at ? $contact->created_at->format('Y-m-d') : '-' }}</td>
                            <td>
                                <a href="{{ route('contacts.edit', $contact->id) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('contacts.delete', $contact->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this contact?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="6">No contacts found. <a href="{{ route('contacts.create') }}">Add your first contact</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($contacts->hasPages())
                <div class="pagination">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>

        <div class="modal-overlay" x-show="exportModalOpen" x-cloak @click.self="exportModalOpen = false">
            <div class="modal-box">
                <h3>Export Contacts</h3>
                <form method="GET" action="{{ route('contacts.export') }}">
                    <input type="password" name="password" x-model="exportPassword" placeholder="Set a password for the zip file" minlength="6" required>
                    <div class="modal-actions">
                        <button type="button" class="modal-cancel" @click="exportModalOpen = false">Cancel</button>
                        <button type="submit" class="modal-confirm">Download</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>