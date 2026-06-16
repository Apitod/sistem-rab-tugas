<?php
namespace App\Http\Controllers;
use App\Models\Notification;

class NotificationController extends Controller {
    public function index() {
        $notifications = Notification::where('user_id', auth()->id())->latest('created_at')->paginate(20);
        return view('notifications.index', compact('notifications'));
    }
    public function markRead(string $id) {
        Notification::where('id', $id)->where('user_id', auth()->id())->update(['is_read' => true]);
        return back();
    }
}
