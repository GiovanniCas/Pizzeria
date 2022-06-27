@php
    use App\Models\Header;
    use App\Models\User;
@endphp

<x-layout>

<!-- tabella per gestore -->
@can('Gestore')
<h1>Lista Ordini</h1>
<div class="container-fluid d-flex">
            <div>
                <form method="post" action="{{route('searchOrder')}}"class="d-flex">
                    @csrf
                    @if(session('searchOrder'))
                        <input class="form-control me-2" type="search" placeholder="{{session()->get('searchOrder')}}" aria-label="Search" name="search">
                        @else
                        <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" name="search">
                    @endif 

                    <label for="exampleInputCategory" class="form-label">Stato Accettazione</label>
                    <select name="accettazione[]" multiple class="my-accettazione" style="width: 100%;">

                        <option value="Tutte"> Tutti </option>  
                        <option value="{{Header::PREPARAZIONE}}"> In Preparazione</option>  
                        <option value="{{Header::IN_CONSEGNA}}"> In Consegna </option>  
                        <option value="{{Header::CONSEGNATO}}"> Consegnato </option>  
                    </select>
                    <button class="btn btn-outline-success my-btn" type="submit">Search</button>
                </form>
            </div>
        </div>
        @if(session('accettazione'))
            <div class="d-flex mt-3" >
                <h5> Filtri Attivi : </h5>
                @foreach(session('accettazione') as $m)
                    @if($m == Header::PREPARAZIONE)
                        <h6 class="mx-3"> In Preparazione</h6>
                    @elseif($m == Header::IN_CONSEGNA)
                        <h6 class="mx-3">In Consegna</h6>
                    @elseif($m == Header::CONSEGNATO)    
                        <h6 class="mx-3">Consegnato</h6>
                    @endif
                @endforeach
            </div>
        @endif
        <table class="table">
        <thead>
            <tr>
            <th scope="col">Nome</th>
            <th scope="col">Cognome</th>
            <th scope="col">Citta</th>
            <th scope="col">Accettazione</th>
            </tr>
        </thead>
        <tbody class="table-holder">
            
            @foreach($orders as $order)
               
                    <tr>
                        <td>{{$order->name}}</td>
                        <td>{{$order->surname}}</td>
                        <td>{{$order->citta}}</td>
                        @if($order->accettazione == Header::PREPARAZIONE)
                            <td>In Preparazione</td>
                        @elseif($order->accettazione == Header::IN_CONSEGNA)
                            <td>In Consegna</td>
                        @elseif($order->accettazione == Header::CONSEGNATO)    
                            <td>Consegnato</td>
                        @endif
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
              @if($header->accettazione == Header::PREPARAZIONE)
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
    <h1>Ordini Da Consegnare :</h1>
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
               
            @endforeach
        </tbody>
    </table>  
    @endcan
    {{$orders->links()}}
    <script type="text/javascript">
       $('.my-accettazione').select2();
    </script>
</x-layout>