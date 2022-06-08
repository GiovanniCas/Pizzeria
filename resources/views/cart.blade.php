<x-layout>
    <h1>Bevande:</h1>

    @foreach($idsPizze as $pizza)
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">{{$pizza->product->id}}</h5>
                <p class="card-text"></p>
                <p class="card-text"></p>
                <p class="card-text">Quantita : </p>
            </div>           
        </div>
    @endforeach
</x-layout>