<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendQueryReply;
use App\Models\CustomerQuery;
use App\Models\QueryReply;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CustomerQuery::with('assignedUser')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by search term (name or email)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $queries = $query->paginate(20);
        $stats = [
            'total' => CustomerQuery::count(),
            'new' => CustomerQuery::where('status', 'new')->count(),
            'in_progress' => CustomerQuery::where('status', 'in_progress')->count(),
            'resolved' => CustomerQuery::where('status', 'resolved')->count(),
            'unread' => CustomerQuery::whereNull('read_at')->count(),
        ];

        return view('admin.queries.index', compact('queries', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerQuery $query)
    {
        // Mark as read when viewed
        $query->markAsRead();

        // Load replies relationship
        $query->load('replies.user');

        $users = User::all();

        return view('admin.queries.show', compact('query', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerQuery $query)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $query->update($validated);

        // Mark as resolved if status is changed to resolved
        if ($validated['status'] === 'resolved' && !$query->resolved_at) {
            $query->markAsResolved();
        }

        return back()->with('success', 'Query updated successfully!');
    }

    /**
     * Reply to a customer query
     */
    public function reply(Request $request, CustomerQuery $query)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:10|max:5000',
        ]);

        // Create the reply record
        $reply = QueryReply::create([
            'customer_query_id' => $query->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        // Dispatch job to send email
        SendQueryReply::dispatch($query, $reply);

        // Auto-update query status to in_progress if it's new
        if ($query->status === 'new') {
            $query->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Reply sent successfully! The customer will receive your message via email.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerQuery $query)
    {
        $query->delete();

        return redirect()->route('admin.queries.index')
            ->with('success', 'Query deleted successfully!');
    }
}
