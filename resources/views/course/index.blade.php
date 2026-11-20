<x-layouts.app title="Courses">
    <h1>Courses</h1>
    <a class="btn" href="{{ route('courses.create') }}">Create Course</a>

    @if($courses->isNotEmpty())
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Duration</th><th>Fee</th><th>Difficulty</th><th>Active</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->duration }} weeks</td>
                        <td>{{ number_format((float) $course->fee, 2) }}</td>
                        <td>{{ $course->difficulty }}</td>
                        <td>{{ $course->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a class="btn" href="{{ route('courses.show', $course) }}">View</a>
                            <a class="btn" href="{{ route('courses.edit', $course) }}">Edit</a>
                            <x-delete-form :action="route('courses.destroy', $course)" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No courses found.</p>
    @endif
</x-layouts.app>
