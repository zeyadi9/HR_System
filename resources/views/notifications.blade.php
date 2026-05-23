@extends('layout')

@section('title', 'مركز الإشعارات')

@section('content')
<style>
    .notifications-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .notification-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .notification-card.unread {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .notification-card.unread::before {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #22c55e;
        border-radius: 0 12px 12px 0;
    }

    .notif-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .notif-content {
        flex: 1;
        text-align: right;
    }

    .notif-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .notif-time {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
        direction: rtl;
    }

    .notif-message {
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.6;
        margin: 0 0 0.5rem 0;
    }
    
    .notif-actioned-by {
        display: inline-block;
        font-size: 0.8rem;
        color: #0ea5e9;
        background: #e0f2fe;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        margin-top: 2rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #94a3b8;
        margin-bottom: 1.5rem;
        display: block;
    }

    .empty-state h3 {
        font-size: 1.25rem;
        color: #334155;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
    }

    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .notification-card {
            padding: 1rem;
            gap: 1rem;
        }

        .notif-icon {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }

        .notif-title {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
    }
</style>

<div class="notifications-container animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #1e293b;">
            <i class="fa-solid fa-bell me-2" style="color: #3b82f6;"></i> مركز الإشعارات
        </h2>
    </div>

    @if($notifications->count() > 0)
        @foreach($notifications as $notification)
            @php
                $icon = 'fa-bell';
                $iconColor = '#64748b';
                $title = $notification->data['title'] ?? 'إشعار جديد';
                $message = $notification->data['message'] ?? '';
                $actionedBy = $notification->data['actioned_by'] ?? null;
                
                if (str_contains($title, 'قبول') || str_contains($message, 'قبول')) {
                    $icon = 'fa-check-circle';
                    $iconColor = '#10b981';
                } elseif (str_contains($title, 'رفض') || str_contains($message, 'رفض')) {
                    $icon = 'fa-times-circle';
                    $iconColor = '#ef4444';
                } elseif (str_contains($title, 'جزاء') || str_contains($message, 'خصم')) {
                    $icon = 'fa-triangle-exclamation';
                    $iconColor = '#f59e0b';
                }
                
                // تنظيف الـ Emoji من العنوان لكي لا يتعارض مع أيقونة الـ FontAwesome
                $title = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $title);
            @endphp

            <div class="notification-card {{ is_null($notification->read_at) ? 'unread' : '' }}">
                <div class="notif-icon" style="color: {{ $iconColor }}; border-color: {{ $iconColor }}40; background-color: {{ $iconColor }}10;">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title">
                        <span>{{ trim($title) }}</span>
                        <span class="notif-time" dir="ltr">
                            {{ $notification->created_at->diffForHumans() }} <i class="fa-regular fa-clock ms-1"></i>
                        </span>
                    </div>
                    <p class="notif-message">{{ $message }}</p>
                    @if($actionedBy)
                        <div class="notif-actioned-by mt-2">
                            <i class="fa-solid fa-user-pen me-1"></i> الإجراء بواسطة: {{ $actionedBy }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="pagination-wrapper">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="empty-state">
            <i class="fa-regular fa-bell-slash"></i>
            <h3>لا توجد إشعارات حتى الآن</h3>
            <p>عندما يقوم المدير بقبول أو رفض طلباتك، ستظهر الإشعارات هنا.</p>
        </div>
    @endif
</div>
@endsection
