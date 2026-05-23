@extends('layout')
@section('title', 'Register')
@section('content')

@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endforeach
@endif
<form class="max-w-sm mx-auto" action="{{ route('register_post') }}" method="POST">
    @csrf
    <div class="mb-5 mt-40">
        <label for="name" class="block mb-2.5 text-sm font-medium text-heading">Your Name</label>
        <input type="text" id="name" name="name"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder-gray-400"            placeholder="Your Name" required />
    </div>
    <div class="mb-5 ">
        <label for="email" class="block mb-2.5 text-sm font-medium text-heading">Your email</label>
        <input type="email" id="email" name="email"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder-gray-400"            placeholder="name@gamma.scan" required />
    </div>
    <div class="mb-5">
        <label for="job_title" class="block mb-2.5 text-sm font-medium text-heading">Your Job Title</label>
        <select id="job_title" name="job_title" 
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder-gray-400" required>
            <option value="" disabled selected>Select your job title</option>
            <option value="Rec">استقبال</option>
            <option value="Nur">تمريض</option>
            <option value="Tec">فني</option>
            <option value="Acc">حسابات</option>
            <option value="Super_V">اداري</option>
            <option value="IT">IT</option>
            <option value="Call_Center">كول سنتر</option>
        </select>
    </div>
    <div class="mb-5">
        <label for="password" class="block mb-2.5 text-sm font-medium text-heading">Your password</label>
        <input type="password" id="password" name="password"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder-gray-400"            placeholder="••••••••" required />
    </div>

<button type="submit" class="text-white bg-gradient-to-l from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:ring-4 focus:ring-blue-300 font-semibold rounded-xl text-sm px-5 py-2.5 shadow-md transition-all duration-200 border border-black">
    Submit
</button>

</form>
@endsection
