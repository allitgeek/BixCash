@extends('layouts.admin')

@section('title', 'Query Details - BixCash Admin')
@section('page-title', 'Query Details')

@section('content')
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('admin.queries.index') }}" class="btn btn-secondary">
            ← Back to Queries
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        <!-- Query Details -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Query Information</h3>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #dee2e6;">
                    <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <strong style="color: #666;">Status:</strong>
                        <div>
                            @if($query->status == 'new')
                                <span style="background: #e74c3c; color: white; padding: 0.25rem 0.75rem; border-radius: 3px; font-size: 0.9rem;">
                                    New
                                </span>
                            @elseif($query->status == 'in_progress')
                                <span style="background: #f39c12; color: white; padding: 0.25rem 0.75rem; border-radius: 3px; font-size: 0.9rem;">
                                    In Progress
                                </span>
                            @elseif($query->status == 'resolved')
                                <span style="background: #27ae60; color: white; padding: 0.25rem 0.75rem; border-radius: 3px; font-size: 0.9rem;">
                                    Resolved
                                </span>
                            @else
                                <span style="background: #95a5a6; color: white; padding: 0.25rem 0.75rem; border-radius: 3px; font-size: 0.9rem;">
                                    Closed
                                </span>
                            @endif
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <strong style="color: #666;">Customer Name:</strong>
                        <div>{{ $query->name }}</div>
                    </div>

                    <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <strong style="color: #666;">Email:</strong>
                        <div><a href="mailto:{{ $query->email }}" style="color: #3498db;">{{ $query->email }}</a></div>
                    </div>

                    <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <strong style="color: #666;">Submitted On:</strong>
                        <div>{{ $query->created_at->format('F d, Y \a\t h:i A') }}</div>
                    </div>

                    @if($query->read_at)
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <strong style="color: #666;">Read On:</strong>
                            <div>{{ $query->read_at->format('F d, Y \a\t h:i A') }}</div>
                        </div>
                    @endif

                    @if($query->resolved_at)
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <strong style="color: #666;">Resolved On:</strong>
                            <div>{{ $query->resolved_at->format('F d, Y \a\t h:i A') }}</div>
                        </div>
                    @endif

                    @if($query->assignedUser)
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <strong style="color: #666;">Assigned To:</strong>
                            <div>{{ $query->assignedUser->name }}</div>
                        </div>
                    @endif
                </div>

                <div>
                    <strong style="display: block; margin-bottom: 0.75rem; color: #666;">Message:</strong>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #3498db; white-space: pre-wrap; line-height: 1.6;">{{ $query->message }}</div>
                </div>

                @if($query->admin_notes)
                    <div style="margin-top: 1.5rem;">
                        <strong style="display: block; margin-bottom: 0.75rem; color: #666;">Admin Notes:</strong>
                        <div style="background: #fff3cd; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #f39c12; white-space: pre-wrap; line-height: 1.6;">{{ $query->admin_notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Update Form -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Update Query</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.queries.update', $query) }}">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Status</label>
                        <select name="status" required style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <option value="new" {{ $query->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="in_progress" {{ $query->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $query->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $query->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Assign To</label>
                        <select name="assigned_to" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $query->assigned_to == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Admin Notes</label>
                        <textarea name="admin_notes" rows="6" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px; resize: vertical;" placeholder="Add notes about this query...">{{ $query->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Update Query
                    </button>
                </form>

                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #dee2e6;">
                    <form method="POST" action="{{ route('admin.queries.destroy', $query) }}" onsubmit="return confirm('Are you sure you want to delete this query? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width: 100%;">
                            Delete Query
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Section -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Reply to Customer</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.queries.reply', $query) }}">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Your Reply</label>
                    <textarea name="message" rows="6" required style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px; resize: vertical; font-family: inherit;" placeholder="Type your reply to {{ $query->name }}..."></textarea>
                    @error('message')
                        <small style="color: #e74c3c; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                    @enderror
                </div>
                <div style="background: #e3f2fd; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; border-left: 4px solid #2196f3;">
                    <small style="color: #1565c0;">
                        <strong>Note:</strong> This reply will be sent via email to <strong>{{ $query->email }}</strong> and will be saved in the reply history below.
                    </small>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                    Send Reply
                </button>
            </form>
        </div>
    </div>

    <!-- Reply History -->
    @if($query->replies->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reply History ({{ $query->replies->count() }})</h3>
            </div>
            <div class="card-body">
                @foreach($query->replies()->with('user')->orderBy('created_at', 'desc')->get() as $reply)
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #76d37a;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <strong style="color: #021c47;">{{ $reply->user->name }}</strong>
                                <span style="color: #666; font-size: 0.9rem;"> replied</span>
                            </div>
                            <div style="text-align: right;">
                                <div style="color: #666; font-size: 0.85rem;">
                                    {{ $reply->created_at->format('M j, Y \a\t h:i A') }}
                                </div>
                                @if($reply->sent_at)
                                    <div style="color: #27ae60; font-size: 0.8rem; margin-top: 0.25rem;">
                                        ✓ Email sent
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div style="white-space: pre-wrap; line-height: 1.6; color: #333;">{{ $reply->message }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(session('success'))
        <div style="position: fixed; top: 20px; right: 20px; background: #27ae60; color: white; padding: 1rem 1.5rem; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 9999;" id="successMessage">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('successMessage');
                if (msg) msg.remove();
            }, 3000);
        </script>
    @endif
@endsection
