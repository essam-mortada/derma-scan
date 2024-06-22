<?php

namespace App\Http\Controllers;

use App\Models\notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class notificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                     ->unread()
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return view('notifications.index', compact('notifications'));
    }

 /**
 * Mark a notification as read
 *
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function markAsRead(Request $request)
{
    // Validate the request
    

    // Retrieve the notification by ID and user ID
    $notification = Notification::where('id', $request->id)
                                ->where('user_id', Auth::id())
                                ->first();

    // Check if the notification exists
    if ($notification) {
        try {
            // Update the notification to mark it as read
            $notification->update(['is_read' => 1]);
            return redirect()->back()->with('success', 'Notification marked as read successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to mark notification as read']);
        }
    } else {
        return redirect()->back()->withErrors(['error' => 'Notification not found']);
    }
}
}
