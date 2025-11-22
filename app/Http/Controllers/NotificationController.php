<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function index()
    {
        return view('Notifications.index');
    }


    public function getNotifications()
    {
        // Get notifications without ordering - let DataTables handle it
        $notifications = auth()->user()->notifications()->get();

        return DataTables::collection($notifications)
            ->addColumn('title', function ($notification) {
                return $notification->data['title'] ?? 'Notification';
            })
            ->addColumn('message', function ($notification) {
                $message = $notification->data['message'] ?? 'You have a new notification';
                // Truncate long messages
                if (strlen($message) > 40) {
                    return substr($message, 0, 40) . '...';
                }
                return $message;
            })
            ->addColumn('created_at', function ($notification) {
                return Carbon::parse($notification->created_at)->format('d/m/Y H:i:s');
            })
            ->addColumn('status', function ($notification) {
                if ($notification->read_at) {
                    return '<span class="badge bg-secondary">Read</span>';
                } else {
                    return '<span class="badge bg-primary">Unread</span>';
                }
            })
            ->addColumn('actions', function ($notification) {
                $viewUrl = route('notifications.view', $notification->id);
                return '<div style="text-align:center">
                            <a class="btn btn-sm btn-primary" href="' . $viewUrl . '">
                                <i class="ri-eye-line me-1"></i>View
                            </a>
                        </div>';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function view($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return view('Notifications.view', compact('notification')); // Pass the variable here
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
