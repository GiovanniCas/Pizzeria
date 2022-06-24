<x-layout> 
    <h1>Vuoi aggiungere una o più immagini?</h1>
        
        <form action="{{route('uploadImages')}}" class="form-horizontal" enctype="multipart/form-data" method="post" action="/details">
        @csrf
            <input type="file" class="form-control" name="images[]" placeholder="address" multiple>
            <button type="submit" class="btn btn-primary mt-3">Avanti!</button>
        </form>

</x-layout>