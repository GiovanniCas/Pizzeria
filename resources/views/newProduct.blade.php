<x-layout>
    <div class="container mt-5">
        <h2>Inserisci il nuovo prodotto</h2>
        <div class="row">
            <div class="col-12 col-md-6 mt-5">
                <form method="POST" action="{{route('submitProduct')}}" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label for="exampleInputName" class="form-label">Nome Prodotto</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp">
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputDescription" class="form-label">Descrizione</label>
                        <input type="text" class="form-control" name="description" aria-describedby="emailHelp">
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPrice" class="form-label">Prezzo</label>
                        <input type="text" class="form-control" name="price" aria-describedby="emailHelp">
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputCategory" class="form-label">Categoria</label>
                        <select name="category_id">
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">
                                    {{$category->name}}
                                </option>   
                            
                            @endforeach
                        </select>
                    </div>


                    <!-- <div class="mb-3">
                        <label for="exampleInputCategory" class="form-label">Seleziona un immagine</label>
                        <input type="file" class="form-control" name="category" aria-describedby="emailHelp">
                    </div> -->

                    <button type="submit" class="btn btn-primary mt-3">Aggiungi</button>
                </form>
            </div>
        </div>
    </div>


</x-layout>