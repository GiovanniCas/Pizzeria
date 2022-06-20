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
    @else
        <div class="container-fluid d-flex">
            <div>
                <form method="post" action="{{route('search')}}"class="d-flex">
                    @csrf
                    <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" name="search">

                    <label for="exampleInputCategory" class="form-label">Categoria</label>
                    <select name="category_id">
                       <option value="Tutte">Tutte</option>   
                       <option value="1">Pizze</option>   
                       <option value="2">Bevenade</option>   
                       <option value="3">Dessert</option>   
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
                @can('Gestore')
                    <th scope="col">Azioni</th>
                @endcan
                </tr>
            </thead>
            <tbody class="table-holder">
                
                @foreach($products as $product)
                    <tr data-id="{{$product->id}}">
                        <td data-value="{{$product->name}}">{{$product->name}}</td>
                        <td data-value="{{$product->category_id}}">{{$product->category_id}}</td>
                        <td data-value="{{$product->description}}">{{$product->description}}</td>
                        <td data-value="{{$product->price}}">$ {{$product->price}}</td>
                        @can('Gestore')
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
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
      
        <!-- rianggiungere i links -->

        @can('Gestore')   
            <a href="{{route('newProduct')}}" class="btn btn-danger" >Aggiungi Prodotto</a>
        @endcan
    @endguest
    
</x-layout>