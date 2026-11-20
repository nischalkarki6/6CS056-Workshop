<x-layouts.app title="Create Course">
    <h1>Create Course</h1>
    <form action="{{ route('courses.store') }}" method="POST">
        @csrf
        @include('course._form', ['course' => new \App\Models\Course(['is_active' => true])])
        <button class="btn" type="submit">Create Course</button>
        <a class="btn" href="{{ route('courses.index') }}">Cancel</a>
    </form>
</x-layouts.app>
