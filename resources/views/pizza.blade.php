<x-layout>

    <h1>Le nostre pizze:</h1>
    
    <form method="POST" action="{{route('addCart')}}">
        @csrf
            @foreach($products as $product)
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">{{$product->name}}</h5>
                        <p class="card-text">{{$product->description}}</p>
                        <p class="card-text">{{$product->price}}</p>
                        <label for="inputQuantity">Quantita :</label>
                        <input type="number"  name="quantity[{{$product->id}}]" >
                    </div>           
                </div>
            @endforeach
        <button type="submit" class="btn btn-primary mt-3">Aggiungi al carrello</button>
    </form>
                     
</x-layout>