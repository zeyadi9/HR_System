@extends('layout')

@section('title', 'بروفايلي الشخصي')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <div class="text-center mb-4">
                <div class="d-inline-block p-3 bg-white shadow-sm mb-3" style="border-radius: 50%; width: 100px; height: 100px;">
                    <span style="font-size: 3.5rem;">👤</span>
                </div>
                <h2 class="fw-bold text-slate-800 mb-1" style="color: #1e293b;">{{ $user->name }}</h2>
                <p class="text-muted small">{{ $user->email }}</p>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="mb-0 fw-bold text-slate-700">📋 معلومات الوظيفة والراتب</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        
                        <div class="list-group-item px-4 py-4 border-0 bg-light-subtle">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="bg-primary text-white p-3 rounded-circle shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        🏢
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="text-muted small mb-1 d-block fw-semibold">المسمى الوظيفي</label>
                                    <span class="fs-5 fw-bold text-slate-800">
                                        {{ $user->job_title ?? 'لم يتم تحديده بعد' }}
                                    </span>
                                </div>
                            </div>
                        </div>



                        <div class="list-group-item px-4 py-4 border-0 bg-light-subtle">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="bg-info text-white p-3 rounded-circle shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        ⏱️
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="text-muted small mb-1 d-block fw-semibold">قيمة الساعة</label>
                                    <span class="fs-4 fw-bold text-info">
                                        {{ number_format($user->hourly_rate, 2) }} <small class="fs-6">ج.م</small>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4 text-center">
                    <div class="p-3 bg-warning-subtle border border-warning-subtle rounded-3 text-warning-emphasis small">
                        ⚠️ هذه المعلومات رسمية ومسجلة في النظام. في حال وجود أي خطأ، يرجى مراجعة إدارة الموارد البشرية.
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4 overflow-hidden" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-slate-700">📊 إحصائيات الدورة الحالية</h5>
                    <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.7rem;">{{ $stats['period_label'] }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Leaves -->
                        <div class="col-12 col-md-4">
                            <div class="p-3 bg-light rounded-3 text-center h-100 border border-light-subtle">
                                <div class="fs-2 mb-1">🌴</div>
                                <label class="text-muted small d-block mb-1 fw-semibold">رصيد الإجازات</label>
                                <div class="d-flex justify-content-center align-items-baseline gap-1">
                                    <span class="fs-4 fw-bold text-slate-800">{{ $stats['leaves']['month'] }}</span>
                                    <small class="text-muted">/ {{ $stats['leaves']['month_limit'] }} يوم (ش)</small>
                                </div>
                                <div class="mt-1 small text-muted">
                                    السنوي: {{ $stats['leaves']['year'] }} / {{ $stats['leaves']['year_limit'] }}
                                </div>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="col-12 col-md-4">
                            <div class="p-3 bg-light rounded-3 text-center h-100 border border-light-subtle">
                                <div class="fs-2 mb-1">🚪</div>
                                <label class="text-muted small d-block mb-1 fw-semibold">الأذونات</label>
                                <div class="d-flex justify-content-center align-items-baseline gap-1">
                                    <span class="fs-4 fw-bold text-slate-800">{{ $stats['permissions']['hours'] }}</span>
                                    <small class="text-muted">ساعة</small>
                                </div>
                                <div class="mt-1 small text-muted">
                                    {{ $stats['permissions']['minutes'] }} / {{ $stats['permissions']['limit_minutes'] }} دقيقة
                                </div>
                            </div>
                        </div>

                        <!-- Overtime -->
                        <div class="col-12 col-md-4">
                            <div class="p-3 bg-primary-subtle rounded-3 text-center h-100 border border-primary-subtle">
                                <div class="fs-2 mb-1">⚡</div>
                                <label class="text-primary small d-block mb-1 fw-semibold">ساعات الإضافي</label>
                                <div class="d-flex justify-content-center align-items-baseline gap-1">
                                    <span class="fs-4 fw-bold text-primary">{{ $stats['overtime']['hours'] }}</span>
                                    <small class="text-primary-emphasis small">ساعة</small>
                                </div>
                                <div class="mt-1 fw-bold text-success">
                                    {{ number_format($stats['overtime']['value'], 2) }} <small>ج.م</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                @if(!empty($overtimeHistory))
                    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-slate-700">📜 سجل الشهور السابقة</h5>
                            <span class="badge bg-primary-subtle text-primary border-0 px-2 py-1">آخر 6 شهور</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 text-muted small fw-bold border-0">الفترة</th>
                                            <th class="text-center py-3 text-muted small fw-bold border-0">الساعات</th>
                                            <th class="text-end px-4 py-3 text-muted small fw-bold border-0">القيمة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($overtimeHistory as $item)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="text-primary">📅</span>
                                                        <span class="text-slate-700 small fw-semibold">{{ $item['label'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center py-3">
                                                    <span class="badge bg-primary-subtle text-primary border-0 fw-bold">{{ $item['hours'] }} س</span>
                                                </td>
                                                <td class="text-end px-4 py-3">
                                                    <span class="fw-bold text-success">{{ number_format($item['value'], 2) }}</span>
                                                    <small class="text-muted small">ج.م</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 text-center bg-white rounded-4 shadow-sm border border-dashed">
                        <span class="fs-1 d-block mb-2">📥</span>
                        <p class="text-muted small mb-0">لا يوجد سجل ساعات إضافية للشهور السابقة حتى الآن.</p>
                    </div>
                @endif
            </div>

            <div class="mt-4 text-center">
                @if($isFirstOfMonth && $previousCycleData)
                    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; background: #f0f9ff; border: 1px solid #bae6fd !important;">
                        <div class="card-body p-4 text-end">
                            <h6 class="fw-bold text-primary mb-3 d-flex align-items-center justify-content-end gap-2">
                                📊 ملخص الدورة السابقة المستحق
                                <span>📅</span>
                            </h6>
                            <p class="text-muted small mb-3">{{ $previousCycleData['label'] }}</p>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-white rounded-3 shadow-xs">
                                        <span class="text-muted small d-block mb-1">إجمالي الساعات</span>
                                        <span class="fs-4 fw-bold text-slate-800">{{ $previousCycleData['hours'] }}</span>
                                        <small class="text-muted">ساعة</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-white rounded-3 shadow-xs">
                                        <span class="text-muted small d-block mb-1">القيمة المستحقة</span>
                                        <span class="fs-4 fw-bold text-success">{{ number_format($previousCycleData['value'], 2) }}</span>
                                        <small class="text-muted">ج.م</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<style>
    .bg-light-subtle { background-color: #f8fafc !important; }
    .bg-warning-subtle { background-color: #fefce8 !important; }
    .text-warning-emphasis { color: #854d0e !important; }
    .bg-primary { background: #2563eb !important; }
    .bg-success { background: #16a34a !important; }
    .bg-info { background: #0ea5e9 !important; }
    .shadow-xs { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
</style>
@endsection
