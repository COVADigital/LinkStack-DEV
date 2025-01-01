@php
use App\Models\UserData;
use App\Helpers\SecurityHelper;

$GLOBALS['activenotify'] = true;

// Call the helper function to check if files are compromised
$compromised = checkFileCompromise();

// Notification user ID
$notifyID = Auth::user()->id;
@endphp

{{-- Notification Cards --}}
@php
    $notifications = [
        [
            'id' => 'modal-1',
            'icon' => 'bi bi-exclamation-triangle-fill text-danger',
            'title' => __('messages.Your security is at risk!'),
            'message' => __('messages.Immediate action is required!'),
            'condition' => $compromised,
            'dismiss' => '',
            'adminonly' => true,
        ],
        [
            'id' => 'modal-star',
            'icon' => 'bi bi-heart-fill',
            'title' => __('messages.Enjoying Linkstack?'),
            'message' => __('messages.Help Us Out'),
            'condition' => UserData::getData($notifyID, 'hide-star-notification') !== true,
            'dismiss' => __('messages.Hide this notification'),
            'adminonly' => true,
        ],
    ];

    $shownNotifications = array_filter($notifications, function($notification) {
        return $notification['condition'] && (!$notification['adminonly'] || (auth()->user()->role == 'admin'));
    });
@endphp

@if(count($shownNotifications) > 0)
    @foreach($shownNotifications as $notification)
    @push('notifications')
        {{ notificationCard($notification['id'], $notification['icon'], $notification['title'], $notification['message'], $notification['dismiss']) }}
    @endpush
    @endforeach
@else
    @php $GLOBALS['activenotify'] = false; @endphp
    @push('notifications')
    <center class='p-2'><i>{{ __('messages.No notifications') }}</i></center>
    @endpush
@endif

{{-- Notification Modals --}}
@push('sidebar-scripts') 
@php
    notification('modal-1', __('messages.Your security is at risk!'), '<b>' . __('messages.security.msg1') . '</b> ' . __('messages.security.msg2') . '<br><br>' . __('messages.security.msg3') . '<br><a href="' . url('admin/config#5') . '">' . __('messages.security.msg3') . '</a>.');
    notification('hide-star-notification', 'modal-star', __('messages.Support Linkstack'), '' . __('messages.support.msg1') . ' <a target="_blank" href="https://github.com/linkstackorg/linkstack">' . __('messages.support.msg2') . '</a>. ' . __('messages.support.msg3') . '<br><br>' . __('messages.support.msg4') . ' <a target="_blank" href="https://linkstack.org/donate">' . __('messages.support.msg5') . '</a><br><br>' . __('messages.support.msg6') . '');
@endphp 
@endpush

@php 
if (isset($_GET['dismiss'])) {
    $dismiss = $_GET['dismiss'];
    $param = str_replace('dismiss=', '', $dismiss);
    UserData::saveData($notifyID, $param, true);
    exit(header("Location: " . url()->current()));
}
@endphp
