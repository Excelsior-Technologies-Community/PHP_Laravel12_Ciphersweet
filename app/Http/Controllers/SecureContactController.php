<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecureContact;

class SecureContactController extends Controller
{
    public function index(Request $request)
    {
        $query = SecureContact::query();

        // SEARCH ENCRYPTED EMAIL USING BLIND INDEX
        if ($request->search) {

            $query->whereBlind(
                'email',
                'email_index',
                $request->search
            );
        }

        $contacts = $query->orderBy('id', 'asc')->get();

        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        SecureContact::create([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Contact Added Successfully');
    }

    public function edit($id)
    {
        $contact = SecureContact::findOrFail($id);

        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        $contact = SecureContact::findOrFail($id);

        $contact->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Contact Updated Successfully');
    }

    public function destroy($id)
    {
        $contact = SecureContact::findOrFail($id);

        $contact->delete();

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Contact Deleted Successfully');
    }
}