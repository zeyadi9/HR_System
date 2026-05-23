@extends('layout')
@section('title', 'Add Penalty')
@section('content')

<div class="max-w-lg mx-auto mt-20">
    <div class="bg-white border border-default rounded-base shadow-xs p-6">
        <h2 class="text-xl font-semibold text-heading mb-6">➕ إضافة جزاء</h2>

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

        <form action="{{ route('admin.penalties.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-heading mb-1">اختر الموظف</label>
                <select name="user_id" id="penalty_user_select" required
                    class="penalty-user-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">-- اختر موظف --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-heading mb-1">سبب الجزاء</label>
                <input type="text" name="reason" value="{{ old('reason') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-heading mb-1">قيمة الجزاء </label>
                <input type="text" name="amount" value="{{ old('amount') }}" required min="0" step="0.01"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-heading mb-1">ملاحظات إضافية (اختياري)</label>
                <textarea name="notes" rows="3" placeholder="أي تفاصيل إضافية..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('notes') }}</textarea>
            </div>

            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 rounded-lg">
                اضافه </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.penalty-user-select')) {
            new TomSelect(".penalty-user-select", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "اكتب اسم الموظف للبحث...",
                allowEmptyOption: true,
            });
        }
    });
</script>

@endsection