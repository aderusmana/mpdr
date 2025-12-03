<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view permission', ['only' => ['index']]);
        $this->middleware('permission:create permission', ['only' => ['create','store']]);
        $this->middleware('permission:update permission', ['only' => ['update','edit']]);
        $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Permission::query();
            return DataTables::of($query)->toJson();
        }

        $permissions = Permission::get();

        // Dialog Sweet Alert (client handles confirmation)
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('page.master.permissions.index', ['permissions' => $permissions]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:permissions,name']
        ]);

        if ($v->fails()) {
            return response()->json(["status" => "error", "message" => "Validation failed", "errors" => $v->errors()], 422);
        }

        try {
            Permission::create(['name' => $request->name]);
            return response()->json(["status" => "success", "message" => "Successfully Create Permission"]);
        } catch (\Exception $e) {
            Log::error('Permission store error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to create permission"], 500);
        }
    }


    public function update(Request $request, Permission $permission)
    {
        $v = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:permissions,name,'.$permission->id]
        ]);

        if ($v->fails()) {
            return response()->json(["status" => "error", "message" => "Validation failed", "errors" => $v->errors()], 422);
        }

        try {
            $permission->update(['name' => $request->name]);
            return response()->json(["status" => "success", "message" => "Successfully Update Permission"]);
        } catch (\Exception $e) {
            Log::error('Permission update error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to update permission"], 500);
        }
    }

    public function destroy($permissionId)
    {
        try {
            $permission = Permission::find($permissionId);
            if ($permission) {
                $permission->delete();
                return response()->json(["status" => "success", "message" => "Successfully Delete Permission"]);
            }
            return response()->json(["status" => "error", "message" => "Permission not found"], 404);
        } catch (\Exception $e) {
            Log::error('Permission destroy error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to delete permission"], 500);
        }
    }

    // ==============================================================Notification===========================================================
    public function fetchNotifications()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil 15 notifikasi terbaru
        $notifications = $user->notifications()->limit(15)->get();
        $unreadCount = $user->unreadNotifications()->count();

        $formattedNotifications = $notifications->map(function ($notification) {
            $notificationData = is_array($notification->data) ? $notification->data : [];

            // [PERBAIKAN] Ambil 'message' dan 'title' dari dalam sub-array 'data'
            $messageContent = $notificationData['data']['message'] ?? 'No message content.';
            $titleContent = $notificationData['data']['title'] ?? 'Notification';

            // [PERBAIKAN] Ambil 'notification_type' dari level atas
            $notificationType = $notificationData['notification_type'] ?? 'general';

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

    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        $currentUser = Auth::user();

        if ($notificationId) {
            // Tandai satu notifikasi sebagai dibaca
            $notification = $currentUser->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                $notification->update(['read_at' => now()]);
                return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
            }
        } else {
            // Tandai semua notifikasi sebagai dibaca
            $currentUser->unreadNotifications->markAsRead();
            $currentUser->unreadNotifications()->update(['read_at' => now()]);
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        try {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Hapus hanya notifikasi milik user tersebut
            $user->notifications()->delete();

            return response()->json(['message' => 'All your notifications have been deleted.'], 200);
        } catch (\Exception $e) {
            // Kirim pesan error jika gagal
            return response()->json(['message' => 'Failed to delete notifications.'], 500);
        }
    }
}
