<x-layout>
 
    <table class="table">
        <thead>
            <tr>
            <th scope="col">id</th>
            <th scope="col">Nome</th>
            <th scope="col">Cognome</th>
            <th scope="col">Data</th>
            <th scope="col">Ora</th>
            <th scope="col">Azioni</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($headers as $header)
              @if($header->accettazione == 1)
                <tr class="my-btn">
                    <th scope="row">{{$header->id}}</th>
                        <td>{{$header->name}}</td>
                        <td>{{$header->surname}}</td>
                        <td>{{$header->data}}</td>
                        <td>{{$header->time}}</td>
                        
                        <td>
                            <button data-bs-toggle="modal" data-bs-target="#exampleModal{{$header->id}}">Show</button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$header->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" >
                                        <h5 class="modal-title" id="exampleModalLabel" data-name="{{$header->name}}">Ordine di {{ $header->name}}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            @foreach($prodottiSelezionati as $prodottoSelezionato)
                                                @if($prodottoSelezionato->header_id === $header->id)
                                                    <li>{{$prodottoSelezionato->products->name}} , x {{$prodottoSelezionato->quantity}}</li>
                                                @endif
                                            @endforeach                                    
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                  
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                        
                                    </div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- <button class="">Pronto</button> -->
                        </td>
                        <td>
                            <form action="{{route('destroyOrder' , compact('header'))}}" method="post">
                                @method('DELETE')
                                @csrf
                                    <input class="btn btn-danger" type="submit" value="Delete" />
                            </form>
                        </td>
                </tr>
              @endif
            @endforeach
        </tbody>
    </table>

<script src="{{asset("js/app.js")}}"></script> 
</x-layout>
