<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ConditionQueryTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    use ConditionQueryTrait;

    /**
     * Display notification listing or handle Yajra DataTables AJAX request.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            
            // Build base query for user's notifications
            $query = DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('notifiable_type', User::class)
                ->select(['id', 'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at', 'created_at', 'updated_at']);

            // Apply SearchBuilder if present in request
            if ($request->has('searchBuilder')) {
                $this->applySearchBuilder($query, $request->input('searchBuilder'));
            }

            return DataTables::of($query)
                ->addColumn('status', function ($row) {
                    if (is_null($row->read_at)) {
                        return '<span class="badge bg-warning text-dark"><i class="fa fa-envelope me-1"></i> Unread</span>';
                    }
                    return '<span class="badge bg-secondary"><i class="fa fa-envelope-open me-1"></i> Read</span>';
                })
                ->addColumn('title', function ($row) {
                    $data = json_decode($row->data, true);
                    return $data['data']['title'] ?? ($data['title'] ?? 'Notification');
                })
                ->addColumn('message', function ($row) {
                    $data = json_decode($row->data, true);
                    return $data['data']['message'] ?? ($data['message'] ?? '-');
                })
                ->addColumn('type_label', function ($row) {
                    $data = json_decode($row->data, true);
                    $type = $data['notification_type'] ?? ($data['type'] ?? 'general');
                    return '<span class="badge bg-light-primary text-primary text-uppercase fw-bold">' . e($type) . '</span>';
                })
                ->addColumn('created_at_formatted', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s') . ' <br><small class="text-muted">(' . Carbon::parse($row->created_at)->diffForHumans() . ')</small>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-1">';
                    if (is_null($row->read_at)) {
                        $btn .= '<button type="button" class="btn btn-sm btn-outline-success mark-single-read" data-id="' . $row->id . '" title="Mark as Read"><i class="fa fa-check"></i></button>';
                    }
                    $btn .= '<button type="button" class="btn btn-sm btn-outline-danger delete-single-notification" data-id="' . $row->id . '" title="Delete"><i class="fa fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'type_label', 'created_at_formatted', 'action'])
                ->make(true);
        }

        return view('page.notification.index');
    }

    /**
     * Fetch recent notifications for header offcanvas dropdown.
     */
    public function fetchNotifications()
    {
        if (!Auth::check()) {
            return response()->json([
                'notifications' => [],
                'unread_count' => 0
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notifications = $user->notifications()->limit(15)->get();
        $unreadCount = $user->unreadNotifications()->count();

        $formattedNotifications = $notifications->map(function ($notification) {
            $notificationData = is_array($notification->data) ? $notification->data : [];

            $messageContent = $notificationData['data']['message'] ?? ($notificationData['message'] ?? 'No message content.');
            $titleContent = $notificationData['data']['title'] ?? ($notificationData['title'] ?? 'Notification');
            $notificationType = $notificationData['notification_type'] ?? ($notificationData['type'] ?? 'general');

            return [
                'id' => $notification->id,
                'message' => $messageContent,
                'title' => $titleContent,
                'notification_type' => $notificationType,
                'read_at' => $notification->read_at,
                'created_at_human' => Carbon::parse($notification->created_at)->diffForHumans(),
            ];
        });

        return response()->json([
            'notifications' => $formattedNotifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark single notification or all notifications as read.
     */
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        $user = Auth::user();

        if ($notificationId) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
            }
            return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
        }

        $user->unreadNotifications->markAsRead();
        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }

    /**
     * Clear all notifications for current user.
     */
    public function clearAll()
    {
        try {
            Auth::user()->notifications()->delete();
            return response()->json(['success' => true, 'message' => 'All notifications cleared.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to clear notifications.'], 500);
        }
    }

    /**
     * Delete a single notification.
     */
    public function destroy($id)
    {
        try {
            $notification = Auth::user()->notifications()->find($id);
            if ($notification) {
                $notification->delete();
                return response()->json(['success' => true, 'message' => 'Notification deleted successfully.']);
            }
            return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete notification.'], 500);
        }
    }
}
