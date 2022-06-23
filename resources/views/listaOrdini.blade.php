<x-layout>

<!-- tabella per gestore -->
@can('Gestore')
<h1>Lista Ordini</h1>
<div class="container-fluid d-flex">
            <div>
                <form method="post" action="{{route('searchOrder')}}"class="d-flex">
                    @csrf
                    <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" name="search">

                    <label for="exampleInputCategory" class="form-label">Stato Accettazione</label>
                    <select name="accettazione[]" multiple>
                        <!-- c era il foreach -->
                        <option value="Tutti"> Tutti </option>  
                        <option value="1"> 1 </option>  
                        <option value="2"> 2 </option>  
                        <option value="3"> 3 </option>  
                    </select>
                    <button class="btn btn-outline-success my-btn" type="submit">Search</button>
                </form>
            </div>
        </div>
        <table class="table">
        <thead>
            <tr>
            <th scope="col">Fattorino</th>
            <th scope="col">Nome</th>
            <th scope="col">Cognome</th>
            <th scope="col">Citta</th>
            <th scope="col">Accettazione</th>
            </tr>
        </thead>
        <tbody class="table-holder">
            
            @foreach($orders as $order)
               
                    <tr>
                        <td>{{$order->user_id}}</td>
                        <td>{{$order->name}}</td>
                        <td>{{$order->surname}}</td>
                        <td>{{$order->citta}}</td>
                        <td>{{$order->accettazione}}</td>
                    </tr>
                
            @endforeach
        </tbody>
    </table>
    @endcan

    <!-- tabella per cuoco -->
    @can('Cuoco')
    <h1>Lista Ordini</h1>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">Nome</th>
            <th scope="col">Cognome</th>
            <th scope="col">Data</th>
            <th scope="col">Ora</th>
            <th scope="col">Azioni</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($orders as $header)
              @if($header->accettazione == 1)
                <tr class="my-btn">
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
                            <div class="d-flex">
                                <form action="{{route('updateOrder' , compact('header'))}}" method="post">
                                    @method('put')
                                    @csrf
                                        <input class="btn btn-success" type="submit" value="Conferma" />
                                </form>
                                <form action="{{route('destroyOrder' , compact('header'))}}" method="post">
                                    @method('delete')
                                    @csrf
                                        <input class="btn btn-danger" type="submit" value="Elimina" />
                                </form>
                            </div>
                        </td>
                </tr>
              @endif
            @endforeach
        </tbody>
    </table>
    @endcan

    @can('Fattorino')
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
            
            @foreach($orders as $header)
              @if($header->accettazione === 2 )
               
                <!-- || $header->user_id == Auth::user()->id -->
                    <tr class="my-btn">
                        
                        <td>{{$header->name}}</td>
                        <td>{{$header->surname}}</td>
                        <td>{{$header->citta}}</td>
                        <td>{{$header->indirizzo}}</td>
                        <td>
                            <div class="d-flex">
                                @if($header->user_id == 1 ) 
                                    <form action="{{route('acceptOrder' , compact('header'))}}" method="post">
                                        @method('put')
                                        @csrf
                                            <input class="btn btn-success" type="submit" value="Accetta" />
                                    </form>
                                @endif
                                @if($header->user_id == Auth::user()->id )
                                    <form action="{{route('deliveredOrder' , compact('header'))}}" method="post">
                                    @method('put')
                                    @csrf
                                        <input class="btn btn-success" type="submit" value="Consegnato!" />
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endif    
             
            @endforeach
        </tbody>
    </table>  
    @endcan
   
</x-layout>