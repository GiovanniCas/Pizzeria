<x-layout>

    <h1>Il tuo Ordine:</h1>
    
    <form  action="{{route('orderForm')}}">
       
        @foreach($prodottiSelezionati as $prodottoSelezionato)
        @if($prodottoSelezionato->quantity !== 0)
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"> {{$prodottoSelezionato->products->name}}</h5>
                    <p class="card-text"> Prezzo al pezzo: {{$prodottoSelezionato->price_uni}} $</p>
                    <label for="inputQuantity">Quantita : {{$prodottoSelezionato->quantity}}</label>
                    <div class="d-flex justify-content-end">
                        <a href="{{route('updateQuantity' , compact('prodottoSelezionato'))}}" class="btn btn-info">Modifica</a>
                    </div>
                </div>           
            </div>
        @endif    
        @endforeach
        
  
        <h3>Totale : $ {{$totale}} </h3>
        
        
        <button type="submit" class="btn btn-warning">Conferma ordine</button>
    </form>
</x-layout>