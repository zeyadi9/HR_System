@extends('layout')
@section('title', 'Coming Soon')
@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
    <div class="bg-blue-50 p-6 rounded-full mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-600 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
    </div>
    <h1 class="text-3xl font-black text-slate-800 mb-4">Coming Soon ....⏳</h1>
    <p class="text-slate-600 max-w-md mx-auto leading-relaxed">
        <br>
 انتظرونا
  </p>
    <div class="mt-8">
        <a href="/" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-blue-700 transition-all">
            العودة للرئيسية
        </a>
    </div>
</div>
@endsection
