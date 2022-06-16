<x-layout>
    Le Tue Consegne:
    <table class="table">
        <thead>
            <tr>
            
            <th scope="col">Nome</th>
            <th scope="col">Cognome</th>
            <th scope="col">Città</th>
            <th scope="col">Indirizzo</th>
            <th scope="col">Azioni</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($orders as $order)
              @if($order->accettazione == 2)
                <tr class="my-btn">
                    
                    <td>{{$order->name}}</td>
                    <td>{{$order->surname}}</td>
                    <td>{{$order->citta}}</td>
                    <td>{{$order->indirizzo}}</td>
                    <td>
                        <div class="d-flex">
                            <form action="" method="post">
                                @csrf
                                    <input class="btn btn-success" type="submit" value="Consegnato!" />
                            </form>
                            <!-- <form action="" method="post">
                                @method('DELETE')
                                @csrf
                                    <input class="btn btn-danger" type="submit" value="Rifiuta" />
                            </form> -->
                        </div>
                    </td>
                </tr>
              @endif
            @endforeach
        </tbody>
    </table>  
</x-layout>