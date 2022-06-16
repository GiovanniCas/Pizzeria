<x-layout>
    <h1>Ordini Pronti :</h1>
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
            
            @foreach($headers as $header)
              @if($header->accettazione == 2 && $header->user_id == 1)
                <tr class="my-btn">
                    
                    <td>{{$header->name}}</td>
                    <td>{{$header->surname}}</td>
                    <td>{{$header->citta}}</td>
                    <td>{{$header->indirizzo}}</td>
                    <td>
                        <div class="d-flex">
                            <form action="{{route('acceptOrder' , compact('header'))}}" method="post">
                                @method('put')
                                @csrf
                                    <input class="btn btn-success" type="submit" value="Accetta" />
                            </form>
                        </div>
                    </td>
                </tr>
              @endif
            @endforeach
        </tbody>
    </table>  
</x-layout>