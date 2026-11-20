<x-layouts.app title="Edit Course">
    <h1>Edit Course</h1>
    <form action="{{ route('courses.update', $course) }}" method="POST">
        @csrf
        @method('PUT')
        @include('course._form', ['course' => $course])
        <button class="btn" type="submit">Update Course</button>
        <a class="btn" href="{{ route('courses.show', $course) }}">Cancel</a>
    </form>
</x-layouts.app>
