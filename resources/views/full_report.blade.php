@extends('layout')
@section('title', 'تقارير مجمعه')
@section('content')

<div class="max-w-7xl mx-auto px-4 mt-8">
    <div class="bg-white border border-default rounded-base shadow-xs p-5 mb-6 text-center">
        <h1 class="text-2xl font-bold text-heading mb-2">التقارير المجمعه</h1>
        <p class="text-sm text-body mb-6"> تصدير كافة بيانات الدورة (إضافي، إجازات، أذونات، جزاءات) في ملف إكسيل واحد.</p>
        
        <div class="flex flex-col items-center gap-4 bg-neutral-secondary-soft p-6 rounded-lg border border-default max-w-2xl mx-auto">
            <div class="text-lg font-medium text-heading">
                الدورة المختارة: {{ $periodLabel }}
            </div>
            
            <div class="flex flex-wrap justify-center gap-3 mt-2">
                <a href="{{ route('full_report.index', ['period_start' => $previousPeriodStart]) }}"
                   class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    الدورة السابقة
                </a>
                
                @unless($isCurrentPeriod)
                    <a href="{{ route('full_report.index', ['period_start' => $nextPeriodStart]) }}"
                       class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        الدورة التالية
                    </a>
                @endunless
                
                <a href="{{ route('full_report.index') }}"
                   class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    العودة للدورة الحالية
                </a>
            </div>

            <hr class="w-full my-2 border-default">

            <a href="{{ route('full_report.export', ['period_start' => $selectedPeriodStart]) }}"
               class="inline-flex items-center gap-3 bg-green-600 hover:bg-green-700 text-white text-lg font-bold px-8 py-4 rounded-xl shadow-lg transition-transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                </svg>
                تحميل التقرير الشامل (Excel)
            </a>
            
            <p class="text-xs text-body mt-2">
                *  تبويبات منفصلة لكل نوع .
            </p>
        </div>
    </div>
</div>

@endsection
