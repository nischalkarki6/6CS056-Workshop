<nav class="navbar">
    <a class="brand" href="{{ url('/') }}">Training Institute</a>
    <div>
        <a class="{{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">Students</a>
        <a class="{{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">Courses</a>
    </div>
</nav>
