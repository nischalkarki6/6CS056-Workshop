@props(['action'])
<form class="inline-form" action="{{ $action }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?')">
    @csrf
    @method('DELETE')
    <button class="btn" type="submit">Delete</button>
</form>
