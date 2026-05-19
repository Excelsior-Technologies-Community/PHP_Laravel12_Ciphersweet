<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f5f7fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .card {
            background: white;
            max-width: 500px;
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: #ffc107;
            color: #333;
            padding: 25px;
            text-align: center;
        }
        
        .card-header h2 {
            font-size: 22px;
            margin-bottom: 5px;
        }
        
        .card-header p {
            font-size: 13px;
            opacity: 0.8;
        }
        
        .encrypt-note {
            background: #fff3cd;
            padding: 12px 20px;
            font-size: 12px;
            color: #856404;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid #eee;
        }
        
        form {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 6px;
        }
        
        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 2px rgba(255,193,7,0.1);
        }
        
        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .btn-submit {
            width: 100%;
            background: #ffc107;
            color: #333;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn-submit:hover {
            background: #e0a800;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link a:hover {
            color: #ffc107;
        }
        
        .hint {
            font-size: 11px;
            color: #888;
            margin-top: 4px;
        }
        
        .info {
            background: #e8f0fe;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #1967d2;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h2> Edit Contact</h2>
            <p>Update encrypted information</p>
        </div>
        
       
        
        <form method="POST" action="{{ route('contacts.update', $contact->id) }}">
            @csrf
            @method('PUT')
            
           
            
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" placeholder="John Doe" value="{{ old('name', $contact->name) }}">
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" placeholder="john@example.com" value="{{ old('email', $contact->email) }}">
              
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="phone" placeholder="+1 234 567 8900" value="{{ old('phone', $contact->phone) }}">
                
                @error('phone') <div class="error">{{ $message }}</div> @enderror
            </div>
            
            <button type="submit" class="btn-submit">Update Contact</button>
            
            <div class="back-link">
                <a href="{{ route('contacts.index') }}">← Back to Contacts</a>
            </div>
        </form>
    </div>
</body>
</html>