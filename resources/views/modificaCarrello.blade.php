<x-layout>
   <form method="POST" action="{{route('modificaQuantita', compact('prodottoSelezionato'))}}">
      @csrf 
      @method('put')
        <div class="card" style="width: 18rem;  background-image: url('/sfondo4.jpg')">
            <div class="card-body">
                <h5 class="card-title"> {{$prodottoSelezionato->products->name}}</h5>
                <p class="card-text"> Prezzo al pezzo: {{$prodottoSelezionato->price_uni}} $</p>
                <label for="inputQuantity">Quantita :</label>
                <input type="number" min="0" name="quantity" value="{{$prodottoSelezionato->quantity}}">
                <button type="submit" href="{{route('modificaQuantita' , compact('prodottoSelezionato'))}}" class=" btn-info">Modifica</button>
            </div>           
        </div>

    </form>
</x-layout>