<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\contactMessage;

class contactController extends Controller
{
    public function show()
    {
        $pageTitle = 'Contact Us';
        return view('pages.contact', compact('pageTitle'));
    }

    public function submit(Request $request)
    {
      if ($request->filled('website')){
        // Bot detected
        return redirect()->back()->with('status', 'Thanks ;)');
      }

      $data = $request->validate([
          'name' => ['required', 'string', 'max:255'],
          'email' => ['required', 'string', 'email', 'max:255'],
          'phone' => ['nullable', 'string', 'max:50'],
          'subject' => ['nullable', 'string', 'max:255'],
          'message' => ['required', 'string'],
          'website' => ['nullable', 'size:0'], // honeypot field
      ]);

      $data['page'] = url()->previous();
      $data['ip'] = $request->ip();

      contactMessage::create($data);

      return redirect()->back()->with('status', 'Your message has been sent successfully!');
    }
}
