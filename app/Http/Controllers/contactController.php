<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\contactMessage;

// Contact Controller to handle contact page and messages
class contactController extends Controller
{
    public function show()
    {
        $pageTitle = 'Contact Us';
        return view('pages.contact', compact('pageTitle'));
    }

    public function adminShow()
    {
        $messages = contactMessage::orderBy('created_at', 'desc')->paginate(20);
        $pageTitle = 'Contact Messages';
        return view('dashboard.contact.index', compact('pageTitle', 'messages'));
    }

    public function submit(Request $request)
    {
      if ($request->filled('website')){
        // Bot detected
        return redirect()->back()->with('status', 'Thanks ;)');
      }

      $data = $request->validate([
          'name' => ['required', 'string', 'max:255'],
          'email' => ['required', 'string', 'email:rfc,dns', 'max:255'],
          'phone' => ['nullable', 'string', 'max:50'],
          'subject' => ['nullable', 'string', 'max:255'],
          'message' => ['required', 'string'],
          'website' => ['nullable', 'size:0'], // honeypot field
      ]);

      $data['page'] = url()->previous();
      $data['ip'] = $request->ip();

      contactMessage::create($data);

      return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }

    public function destroy(ContactMessage $message)
    {
      $message->delete();

      return redirect()->back()->with('status', 'Message deleted.');
    }

}
