<x-layout>

    <h1>I nostri prodotti :</h1>
    
    @guest
    <form method="POST" action="{{route('addCart')}}">
        @csrf
        @foreach($products as $product)
        <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">{{$product->name}}</h5>
                        <p class="card-text">{{$product->description}}</p>
                        <p class="card-text">{{$product->price}} $</p>
                        <label for="inputQuantity">Quantita :</label>
                        <input type="number" min="0" name="quantity[{{$product->id}}]" >
                    </div>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary mt-3">Aggiungi al carrello</button>
        </form>
        @endguest

        @can('Gestore')
        <div class="container-fluid d-flex">
            <div>
                <form method="post" action="{{route('search')}}"class="d-flex">
                    @csrf
                    <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" name="search">

                    <label for="exampleInputCategory" class="form-label">Categoria</label>
                    <select name="category_id">
                        @foreach($categories as $category)
                        <option value="{{$category->id}}">
                            {{$category->name}}
                        </option>   
                        @endforeach
                    </select>
                    <button class="btn btn-outline-success my-btn" type="submit">Search</button>
                </form>
            </div>
        </div>
        <table class="table">
        <thead>
            <tr>
            <th scope="col">Nome </th>
            <th scope="col">Categoria</th>
            <th scope="col">Descrizione</th>
            <th scope="col">Prezzo</th>
            <th scope="col">Azioni</th>
            </tr>
        </thead>
        <tbody class="table-holder">
            
            @foreach($products as $product)
                <tr data-id="{{$product->id}}">
                    <td data-value="{{$product->name}}">{{$product->name}}</td>
                    <td data-value="{{$product->category_id}}">{{$product->category_id}}</td>
                    <td data-value="{{$product->description}}">{{$product->description}}</td>
                    <td data-value="{{$product->price}}">$ {{$product->price}}</td>
                    <td>
                        <div class="d-flex">
                            <form action="{{route('formModify' , compact('product'))}}" method="get">
                                <button  class="btn btn-info" >Modifica</button>
                            </form>
                            <form action="{{route('deleteProduct' , compact('product'))}}" method="post">
                                @method('DELETE')
                                @csrf
                                <button  class="btn btn-danger" >Elimina</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if($products >= "4")
        {{$products->links()}} 
    @endif    
    <a href="{{route('newProduct')}}" class="btn btn-danger" >Aggiungi Prodotto</a>
    @endcan
    
</x-layout>