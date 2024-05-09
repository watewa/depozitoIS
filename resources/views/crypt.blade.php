<form action="{{ route('cr') }}" method="post">
@csrf
@method('post')
<button type="submit">Submit</button>
</form>