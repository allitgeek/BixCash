<?php

namespace App\Http\Controllers;

use App\Models\CustomerQuery;
use App\Jobs\SendCustomerQueryNotification;
use App\Jobs\SendCustomerQueryConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    /**
     * Store a new customer query
     */
    public function store(Request $request)
    {
        // Rate limiting: max 3 queries per email per day
        $key = 'contact-form:' . $request->input('email');

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $hours = ceil($seconds / 3600);

            throw ValidationException::withMessages([
                'email' => ["You have reached the maximum number of queries. Please try again in {$hours} hours."],
            ]);
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Create the customer query
        $query = CustomerQuery::create($validated);

        // Dispatch email jobs to queue
        SendCustomerQueryNotification::dispatch($query);
        SendCustomerQueryConfirmation::dispatch($query);

        // Increment rate limiter (expires in 24 hours)
        RateLimiter::hit($key, 86400);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for contacting us! We will get back to you soon.',
        ]);
    }
}
