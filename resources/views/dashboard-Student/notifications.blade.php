@extends('dashboard-Student.layouts.app')

@section('title', 'الإشعارات')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-bell" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                    إشعارات الطالب
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
            <div class="card-body">
                <h4 style="color: #333;">إشعارات الطالب</h4>

                @if($notifications->isEmpty())
                    <div class="notification" style="background: #fee; padding: 15px; margin: 10px 0; border-right: 5px solid red;">
                        <p><strong>لا توجد إشعارات حالياً.</strong></p>
                    </div>
                @else
                    @foreach($notifications as $notification)
                        <div class="notification" style="background: #eef; padding: 15px; margin: 10px 0; border-right: 5px solid #007bff; border-radius: 5px;">
                            <p><strong>{{ $notification->note }}</strong></p>
                            <p class="date" style="color: #555; font-size: 14px;">
                                التاريخ: {{ \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d') }}
                            </p>
                        </div>
                    @endforeach
                @endif
                
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #f0f8ff, #e6e6fa);
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        direction: rtl;
    }

    .card {
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #333;
        margin: 10px 0;
    }

    .notification:hover {
        transform: scale(1.05);
        background: #dbe9ff;
    }

    .notification:active {
        transform: scale(1);
        background: #c7e0ff;
    }

    .table th {
        background-color: #196098;
        color: white;
        text-align: center;
    }

    .table td {
        text-align: center;
    }

    .page-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #196098;
    }
</style>

@endsection
