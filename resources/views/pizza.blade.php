@php
    use App\Models\Image;
@endphp
<x-layout>
    <div class="container-fluid d-flex">
        <div class="mt-3">
            <form method="post" action="{{route('search')}}"class="d-flex">
                @csrf
                <div>
                    @if(session('searchProduct'))
                        <input class="form-control me-2" type="search" placeholder="{{session()->get('searchProduct')}}" aria-label="Search" name="search">
                        @else
                        <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" style="height: 40px; width: 400px;" name="search">
                    @endif 
                </div>
                <div class="d-flex">
                    <label for="exampleInputCategory" class="form-label"><h3>Categoria</h3></label>
                    <select name="category_id[]" class="my-cat-id text-dark my-select" multiple >
                        <option value="Tutte" class="text-dark">Tutte</option>   
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" class="text-black">{{$category->name}}</option>   
                        @endforeach     
                    </select>
                </div>
                <button class="btn btn-outline-light my-btn" style="height: 40px;" type="submit"><i class="fa-solid fa-magnifying-glass text-white"></i></button>
            </form>
        </div>
    </div>
    <h1 class="mt-3">I nostri prodotti :</h1>
    @if(session('category_id'))
        <div class="d-flex mt-3" >
            @foreach($categories as $category)
                @if($category->id === session('category_id'))
                    <h5 class="mx-3">{{$category->name}}</h5> 
                @endif
            @endforeach
        </div>
    @endif     
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
    @guest
    <div class="container text-black mt-5">
        <div class="row ">
            <form method="POST" action="{{route('addCart')}}" class="d-flex">
            @csrf
                @foreach($products as $product)
                    <div class="col-md-3">
                        <div class="card " style="width: 90%; height: 450px; background-image: url('/sfondo4.jpg')">
                            <div class="card-body text-light">
                                <div class="swiper mySwiper" style="height: 200px">
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
                                <h5 class="card-title mt-3">{{$product->name}}</h5>
                                <p class="card-text" style="height: 50px;">{{$product->description}}</p>
                                <p class="card-text">{{$product->price}} $</p>
                                <label for="inputQuantity">Quantita :</label>
                                <input type="number" min="0" name="quantity[{{$product->id}}]" >
                            </div>
                        </div>
                    </div>
                @endforeach
               
               <div class="row d-flex" style="margin-top: 530px; margin-left: -1309px;">
                   {{$products->links()}} 
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-3"> Aggiungi al carrello</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
        @else
       
        <table class="table">
            <thead class="text-light">
                <tr>
                <th scope="col">Nome </th>
                <th scope="col">Categoria</th>
                <th scope="col">Descrizione</th>
                <th scope="col">Num Immagini</th>
                <th scope="col">Prezzo</th>
                @can('Gestore')
                    <th scope="col">Azioni</th>
                @endcan
                </tr>
            </thead>
            <tbody class="table-holder text-light">
                
                @foreach($products as $product)
                    <tr data-id="{{$product->id}}">
                        <td data-value="{{$product->name}}">{{$product->name}}</td>
                        @foreach($categories as $category)
                            @if($category->id === $product->category_id)
                                <td data-value="{{$product->category_id}}">{{$category->name}}</td>
                            @endif
                        @endforeach
                        <td data-value="{{$product->description}}">{{$product->description}}</td>
                        <td data-value=""> {{count(Image::all()->where('product_id' , $product->id))}}</td>
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