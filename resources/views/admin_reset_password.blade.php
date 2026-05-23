@extends('layout')
@section('title', 'Reset User Password')
@section('content')

<div class="max-w-lg mx-auto mt-20">
    <div class="bg-white border border-default rounded-base shadow-xs p-6">
        <h2 class="text-xl font-semibold text-heading mb-6">Reset User Password</h2>

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

        <form action="{{ route('admin.reset_password.post') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            {{-- Dropdown اليوزرات --}}
            <div>
                <label class="block text-sm font-medium text-heading mb-1">Select Employee</label>
                <select name="user_id" id="user_id" required
                    class="user-search-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="" disabled selected>-- Choose Employee --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new TomSelect(".user-search-select", {
                        create: false,
                        sortField: { field: "text", direction: "asc" },
                        placeholder: "ابحث عن الموظف هنا...",
                        allowEmptyOption: false,
                    });
                });
            </script>

            {{-- الباسورد الجديد --}}
            <div>
                <label class="block text-sm font-medium text-heading mb-1">New Password</label>
                <input type="password" name="password" required minlength="6"
                    placeholder="Enter new password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- تأكيد الباسورد --}}
            <div>
                <label class="block text-sm font-medium text-heading mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required minlength="6"
                    placeholder="Confirm new password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg">
                Reset Password
            </button>
        </form>
    </div>
</div>

@endsection