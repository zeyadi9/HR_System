@extends('layout')
@section('title', 'إضافة تسوية')
@section('content')

<div class="max-w-lg mx-auto mt-20">
    <div class="bg-white border border-default rounded-base shadow-xs p-6">
        <h2 class="text-xl font-semibold text-heading mb-6">⚙️ طلب تسوية جديدة</h2>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('settlements.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-heading mb-1">وصف المشكلة / الطلب</label>
                <textarea name="note" rows="5" required placeholder="اشرح تفاصيل التسوية المطلوبة هنا..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('note') }}</textarea>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 rounded-lg">
                إرسال طلب التسوية
            </button>
        </form>
    </div>
</div>

@endsection
