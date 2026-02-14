<?php

namespace App\Http\Controllers\bookingwebsite;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Mail\ContactMail;
use App\Mail\ThankYouMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::paginate(10);
        return view('backends.contact.index', compact('contacts'));
    }


    public function contact()
    {
        $contacts = Contact::paginate(10);
        return view('bookingwebsite.contact.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'phone' => 'required',
        //     'message' => 'required',
        // ]);

        

        // return redirect()->route('front.home')
        //                  ->with('success', 'Contact created successfully.');
       
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ];

        Mail::to('cholnacholna2222@gmail.com')->send(new ContactMail($details));


 // Send the thank you email to the user
 Mail::to($request->email)->send(new ThankYouMail($details));
 Contact::create($request->all());
      

        return back()->with('success', 'Your message has been sent successfully!');
    
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $contact->update($request->all());

        return redirect()->route('contacts.index')
                         ->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        $contacts = Contact::paginate(10);

        return view('backends.contact.index', compact('contacts'));
    }
}
