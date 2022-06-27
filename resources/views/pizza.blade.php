<x-layout>

    <h1>I nostri prodotti :</h1>
    @guest
        <form method="POST" action="{{route('addCart')}}">
        @csrf
        @foreach($products as $product)
        <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                    @foreach($images as $img)
                                        @if($img->product_id === $product->id)
                                        <div class="swiper-slide"><img src="/storage/img/{{$img->img}}" class="d-block w-100" alt="..."></div>
                                        
                                        @endif
                                    @endforeach   
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <h5 class="card-title">{{$product->name}}</h5>
                        <p class="card-text">{{$product->description}}</p>
                        <p class="card-text">{{$product->price}} $</p>
                        <label for="inputQuantity">Quantita :</label>
                        <input type="number" min="0" name="quantity[{{$product->id}}]" >
                    </div>
                </div>
            @endforeach
            {{$products->links()}}
            <button type="submit" class="btn btn-primary mt-3">Aggiungi al carrello</button>
        </form>
        @else
        <div class="container-fluid d-flex">
            <div>
                <form method="post" action="{{route('search')}}"class="d-flex">
                    @csrf
                    @if(session('searchProduct'))
                        <input class="form-control me-2" type="search" placeholder="{{session()->get('searchProduct')}}" aria-label="Search" name="search">
                        @else
                        <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" name="search">
                    @endif 

                    <label for="exampleInputCategory" class="form-label">Categoria</label>
                    <select name="category_id[]" class="my-cat-id" multiple style="width: 100%;">
                        <option value="Tutte">Tutte</option>   
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>   
                        @endforeach     
                    </select>
                    <button class="btn btn-outline-success my-btn" type="submit">Search</button>
                </form>
            </div>
        </div>
        @if(session('category'))
            <div class="d-flex mt-3" >
                <h5> Filtra per : </h5>
                @foreach(session('category') as $m)
                    @foreach($categories as $category)
                        @if($category->id == $m)
                            <h5 class="mx-3">{{$category->name}}</h5> 
                        @endif
                    @endforeach
                    @if($m == "Tutte")
                        <h5 class="mx-3">Tutte</h5>
                    @endif
                @endforeach
            </div>
        @endif
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
                        @foreach($categories as $category)
                            @if($category->id === $product->category_id)
                                <td data-value="{{$product->category_id}}">{{$category->name}}</td>
                            @endif
                        @endforeach
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
   
        @can('Gestore')   
        
        {{$products->links() }}
        <a href="{{route('newProduct')}}" class="btn btn-danger" >Aggiungi Prodotto</a>
        @endcan
    @endguest
   
   
   
    <script type="text/javascript">
       $('.my-cat-id').select2();
    </script>
</x-layout>