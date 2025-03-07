<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3>Welcome, Student!</h3>
                    <ul>
                        {{-- <li><a href="{{ route('classes.my') }}">My Classes</a></li> --}}
                        {{-- <li><a href="{{ route('assignments.my') }}">My Assignments</a></li> --}}
                        {{-- <li><a href="{{ route('grades.my') }}">My Grades</a></li> --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
