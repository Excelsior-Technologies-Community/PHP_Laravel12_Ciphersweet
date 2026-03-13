<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecureContact;

class SecureContactController extends Controller
{
    // Display contact list
    public function index()
    {
        $contacts = SecureContact::latest()->get();

        return view('contacts.index', compact('contacts'));
    }

    // Show create form
    public function create()
    {
        return view('contacts.create');
    }

    // Store new contact
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        // Save data
        SecureContact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Contact added successfully!');
    }
}