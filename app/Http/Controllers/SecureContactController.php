<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecureContact;

class SecureContactController extends Controller
{
    public function index(Request $request)
    {
        $query = SecureContact::query();

        // Search by name (plain text)
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        // Search by email (using hash for exact match)
        if ($request->filled('search_email')) {
            $query->searchByEmail($request->search_email);
        }

        // Search by phone (using hash for exact match)
        if ($request->filled('search_phone')) {
            $query->searchByPhone($request->search_phone);
        }

        $contacts = $query->orderBy('id', 'desc')->paginate(10);
        $contacts->appends($request->all());

        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20|min:10'
        ]);

        try {
            SecureContact::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            return redirect()
                ->route('contacts.index')
                ->with('success', 'Contact added successfully! Data is encrypted with AES-256.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to save contact: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $contact = SecureContact::findOrFail($id);
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20|min:10'
        ]);

        try {
            $contact = SecureContact::findOrFail($id);
            
            $contact->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            return redirect()
                ->route('contacts.index')
                ->with('success', 'Contact updated successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update contact: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $contact = SecureContact::findOrFail($id);
            $contact->delete();

            return redirect()
                ->route('contacts.index')
                ->with('success', 'Contact deleted successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete contact: ' . $e->getMessage());
        }
    }
    
    public function export()
    {
        $contacts = SecureContact::all();
        
        return response()->json([
            'exported_at' => now(),
            'total_contacts' => $contacts->count(),
            'contacts' => $contacts->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                    'created_at' => $contact->created_at
                ];
            })
        ]);
    }
}