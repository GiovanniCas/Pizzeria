<x-layout> 
    <h1>Aggiungi immagini</h1>
        
        <form action="{{route('uploadImages')}}" class="form-horizontal" enctype="multipart/form-data" method="post" action="/details">
        @csrf
            <input required type="file" class="form-control" name="images[]" placeholder="address" multiple>
            <button type="submit" class="btn btn-primary mt-3">Aggiungi</button>
        </form>

</x-layout>