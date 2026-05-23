@extends('layout')
@section('title', 'إضافة ملاحظة إدارية')
@section('content')

<div class="max-w-lg mx-auto mt-20">
    <div class="bg-white border border-default rounded-base shadow-xs p-6">
        <h2 class="text-xl font-semibold text-heading mb-6">📝 إضافة ملاحظة إدارية</h2>

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

        <form action="{{ route('admin_notes.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-heading mb-1">الملاحظة</label>
                <textarea name="note" rows="5" required placeholder="اكتب الملاحظة هنا..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('note') }}</textarea>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg">
                إضافة الملاحظة
            </button>
        </form>
    </div>
</div>

@endsection
