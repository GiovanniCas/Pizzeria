@php
    use App\Models\Header;
    use App\Models\User;
@endphp

<x-layout>

    <div class="mt-3">
        <form method="post" action="{{route('searchOrder')}}"class="d-flex">
            @csrf
                <div>
                    @if(session('searchOrder'))
                        <input class="form-control me-2" type="search" placeholder="{{session()->get('searchOrder')}}" aria-label="Search" name="search">
                        @else
                        <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" style="height: 40px; width: 400px;" name="search">
                    @endif 
                </div>
                <div class="d-flex">
                    <label for="exampleInputCategory" class="form-label"> <h3>Stato Accettazione:</h3></label>
                    <select name="stato[]" multiple class="my-stato" >
                        <option value="Tutte"> Tutti </option>  
                        <option value="{{Header::STATO_IN_PREPARAZIONE}}"> In Preparazione</option>  
                        <option value="{{Header::STATO_IN_CONSEGNA}}"> In Consegna </option>  
                        <option value="{{Header::STATO_CONSEGNATO}}"> Consegnato </option>  
                    </select>
                </div>
                <button class="btn btn-outline-light my-btn" style="height: 40px;" type="submit"><i class="fa-solid fa-magnifying-glass text-white"></i></button>
            </form>
        </div>
    </div>
    <h1>Lista Ordini</h1>
    @if(session('stato'))
        <div class="d-flex mt-3" >
            <h5> Filtri Attivi : </h5>
            @foreach(session('stato') as $m)
                @if($m == Header::STATO_IN_PREPARAZIONE)
                    <h6 class="mx-3"> In Preparazione</h6>
                @elseif($m == Header::STATO_IN_CONSEGNA)
                    <h6 class="mx-3">In Consegna</h6>
                @elseif($m == Header::STATO_CONSEGNATO)    
                    <h6 class="mx-3">Consegnato</h6>
                @endif
            @endforeach
        </div>
    @endif
    <!-- tabella per gestore -->
    @can('Gestore')
    <!-- <div class="container-fluid d-flex "> -->
        
        <table class="table">
            <thead class="text-white">
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Cognome</th>
                    <th scope="col">Citta</th>
                    <th scope="col">Stato</th>
                </tr>
            </thead>
            <tbody class="table-holder text-white">
                
                @foreach($orders as $order)
                
                    <tr>
                        <td>{{$order->name}}</td>
                        <td>{{$order->surname}}</td>
                        <td>{{$order->citta}}</td>
                        @if($order->stato == Header::STATO_IN_PREPARAZIONE)
                            <td>In Preparazione</td>
                        @elseif($order->stato == Header::STATO_IN_CONSEGNA)
                            <td>In Consegna</td>
                        @elseif($order->stato == Header::STATO_CONSEGNATO)    
                            <td>Consegnato</td>
                        @endif
                    </tr>    
                @endforeach
            </tbody>
        </table>
        <div>
            {{$orders->links()}}
        </div>
        @endcan

        <!-- tabella per cuoco -->
        @can('Cuoco')
        <table class="table">
            <thead class="text-white">
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Cognome</th>
                    <th scope="col">Data</th>
                    <th scope="col">Ora</th>
                    <th scope="col">Ordine</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody class="text-white">
                
                @foreach($orders as $header)
                
                    <tr>
                        <td>{{$header->name}}</td>
                        <td>{{$header->surname}}</td>
                        <td>{{$header->data}}</td>
                        <td>{{$header->time}}</td>
                        
                        <td>
                            <input data-bs-toggle="modal" class="btn btn-info" value="Mostra" style="height: 38px"data-bs-target="#exampleModal{{$header->id}}"></input>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$header->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" >
                                        <h5 class="modal-title" id="exampleModalLabel" data-name="{{$header->name}}">Ordine di {{ $header->name}}</h5>
                                        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            @foreach($prodottiSelezionati as $prodottoSelezionato)
                                                @if($prodottoSelezionato->header_id === $header->id)
                                                    <li class="text-dark">{{$prodottoSelezionato->products->name}} , x {{$prodottoSelezionato->quantity}}</li>
                                                @endif
                                            @endforeach                                    
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                    
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                                       
                                        
                                    </div>
                                    </div>
                                </div>
                            </div>
                        
                         
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
                
                @endforeach
            </tbody>
        </table>
        <div>
            {{$orders->links()}}
        </div>
        @endcan

        @can('Fattorino')
        <table class="table">
            <thead class="text-white">
                <tr>
                    <!-- <th scope="col">Nome Fattorino</th> -->
                    <th scope="col">Nome</th>
                    <th scope="col">Cognome</th>
                    <th scope="col">Città</th>
                    <th scope="col">Indirizzo</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody class="text-white">
                
                @foreach($orders as $header)
                    @if($header->stato === 2)
                        <tr class="hidden">
                        
                            <td>{{$header->name}}</td>
                            <td>{{$header->surname}}</td>
                            <td>{{$header->citta}}</td>
                            <td>{{$header->indirizzo}}</td>
                            <td>
                                <div class="d-flex">
                                    @if($header->user_id === 1 ) 
                                        <form action="{{route('acceptOrder' , compact('header'))}}" method="post">
                                            @method('put')
                                            @csrf
                                                <input class="btn btn-success" type="submit" value="Accetta" />
                                        </form>
                                    @else($header->user_id === Auth::user()->id )
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
        <div>
            {{$orders->links()}}
        </div> 
        @endcan
    
   

    <script type="text/javascript">
    $('.my-stato').select2();
    </script>
</x-layout>