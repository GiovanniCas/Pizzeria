<x-layout>
<div class="container mt-5">
        <h2>Modifica il Prodotto</h2>
        <div class="row">
            <div class="col-12 col-md-6 mt-5">
                <form method="POST" action="{{route('modifyProduct', compact('product'))}}" enctype="multipart/form-data">
                @method('put')    
                @csrf
                    <div class="mb-3">
                        <label for="exampleInputName" class="form-label">Nome Prodotto</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp" value="{{$product->name}}">
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputDescription" class="form-label">Descrizione</label>
                        <input type="text" class="form-control" name="description" aria-describedby="emailHelp"value="{{$product->description}}">
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPrice" class="form-label">Prezzo</label>
                        <input type="text" class="form-control" name="price" aria-describedby="emailHelp" value="{{$product->price}}">
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
                    <div>
                        <label for="exampleInputCategory" class="form-label">Seleziona un immagine</label>
                        <input type="file" class="form-control" name="images[]" placeholder="address" multiple >
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-3">Modifica</button>
                </form>
                
                <p class="mt-3">Immagini presenti:</p>
                <div class="d-flex">
                    @foreach($images as $img)
                    <form action="{{route('deleteImg', compact('img'))}}" method="post">
                        <button class="btn text-danger">X</button>
                        @csrf
                        @method('delete')
                        <img src="/storage/img/{{$img->img}}" style="height:150px; width: 200px;" alt="">
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</x-layout>