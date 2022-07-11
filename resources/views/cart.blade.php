<x-layout>

    <h1>Il tuo Ordine:</h1>
    
    @foreach($prodottiSelezionati as $prodottoSelezionato)
    <form  action="{{route('modificaQuantita' , $prodottoSelezionato)}}" method="post">
    @csrf 
    @method('put')
        @if($prodottoSelezionato->quantity !== 0)
            <div class="card" style="width: 18rem; background-image: url('/sfondo4.jpg')">
                <div class="card-body">
                    <h5 class="card-title"value="{{$prodottoSelezionato->products->name}}"> {{$prodottoSelezionato->products->name}}</h5>
                    <p class="card-text" value="{{$prodottoSelezionato->price_uni}}"> Prezzo al pezzo: {{$prodottoSelezionato->price_uni}} $</p>
                    <label for="inputQuantity">Quantita : </label>
                    <input type="number" min="0" name="quantity[{{$prodottoSelezionato->id}}]" value="{{$prodottoSelezionato->quantity}}">
                </div>           
            </div>
        @endif    
        @endforeach
        
  
        <h3>Totale : $ {{$totale}} </h3>
        
        
        <button type="submit" class="btn btn-warning">Conferma ordine</button>
    </form>
</x-layout>