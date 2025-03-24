@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto p-6 bg-white shadow rounded mt-8">
        <h1 class="text-2xl font-bold mb-4">User Info</h1>
        <a href="{{ route('users.index') }}" 
        class="inline-block px-4 py-2 bg-cyan-400 text-white rounded-md shadow hover:bg-cyan-500 transition mb-4">
        Back</a>
        
        <p><strong>Name:</strong> {{ $user->name }} </p>
        <p><strong>Email:</strong> {{ $user->email }} </p>
        
        
    </div>
@endsection
