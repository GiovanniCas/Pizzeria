<x-layout>

    <h1>I nostri prodotti :</h1>
    <!-- <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    @foreach($images as $img)
        @if($img->product_id === 11)
            <div class="carousel-item active"> -->
        
            <!-- <img src="{{ asset('storage/$img->img') }}" class="d-block w-100" alt="..."> -->
            <!-- <img src="/storage/img/{{$img->img}}" class="d-block w-100" alt="...">
        
            </div>
        @endif
    @endforeach   
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div> -->

    
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