@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("You're logged in!") }}

                <!-- Button Container with Flexbox for alignment -->
                <div class="mt-6 space-y-4 sm:flex sm:space-y-0 sm:space-x-4 sm:items-center">
                    <!-- Manage Roles Button -->
                    @can('role-list')
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <a class="nav-link" href="{{ route('roles.index') }}">Manage Roles</a>
                        </button>
                    @endcan

                    <!-- Manage Users Button (Visible only for Admin) -->
                    @if(auth()->user()->hasRole('Admin'))
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <a class="nav-link" href="{{ route('users.index') }}">Manage Users</a>
                        </button>
                    @endif

                    <!-- Manage Incidents Button -->
                    @can('incident-list')
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <a class="nav-link" href="{{ route('incidents.index') }}">Manage Incidents</a>
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


