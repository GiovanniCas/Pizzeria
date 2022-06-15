<x-layout>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <h1 class="bg-danger">Pizzeria Gio</h1>
</x-layout>