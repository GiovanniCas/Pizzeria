@php
    use App\Models\User;
@endphp
<x-layout>
<div class="container-fluid d-flex">
            <div>
                <form method="post" action="{{route('searchUser')}}"class="d-flex">
                    @csrf
                        @if(session('searchUser'))
                            <input class="form-control me-2" type="search" placeholder="{{session()->get('searchUser')}}" aria-label="Search" name="search">
                            @else
                            <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" name="search">
                        @endif 
                    <div class="d-flex">
                        <h5>Mansione:</h5>
                    </div>
                    <select  name="ruolo[]" class="js-example-basic-multiple" multiple="multiple" style="width: 100%;">
                        <option value="0">Tutte</option>   
                        <option value="{{User::GESTORE}}">Gestore</option>   
                        <option value="{{User::CUOCO}}">Cuoco</option>   
                        <option value="{{User::FATTORINO}}">Fattorino</option>   
                    </select> 
                    
                    
                    
                    <button class="btn btn-outline-success my-btn" type="submit">Cerca</button>
                </form>
            </div>
        </div>   
        @if(session('ruolo'))
            <div class="d-flex mt-3" >
                <h5> Filtri Attivi : </h5>
                @foreach(session('ruolo') as $m)
                    @if($m == User::GESTORE)
                        <h6 class="mx-3">Gestore</h6>
                    @elseif($m == User::CUOCO)
                        <h6 class="mx-3">Cuoco</h6>
                    @elseif($m == User::FATTORINO)    
                        <h6 class="mx-3">Fattorino</h6>
                    @endif
                @endforeach
            </div>
        @endif
        <table class="table">
        <thead>
            <tr>
            <th scope="col">Nome </th>
            <th scope="col">Cognome</th>
            <th scope="col">Email</th>
            <th scope="col">Mansione</th>
            <th scope="col">Azioni</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->surname}}</td>
                    <td>{{$user->email}}</td>
                    @if($user->ruolo == User::GESTORE)
                        <td>Gestore</td>
                    @elseif($user->ruolo == User::CUOCO)
                        <td>Cuoco</td>
                    @elseif($user->ruolo == User::FATTORINO)   
                        <td>Fattorino</td>
                    @endif

                    <td>
                        <div class="d-flex">
                            <form action="{{route('updateUtente' , compact('user'))}}" method="get">
                                <button  class="btn btn-info" >Modifica</button>
                            </form>
                            <form action="{{route('deleteUser' , compact('user'))}}" method="post">
                                @method('DELETE')
                                @csrf
                                <button  class="btn btn-danger" >Elimina</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
     
    <a href="{{route('staff')}}" class="btn btn-danger" >Aggiungi Utente</a>
    <a href="{{route('aggiorna')}}" class="btn btn-success" >Aggiorna</a>
  

    <!-- <script src="{{Config::get('app.url')}}/node_modules/select2/dist/js/select2.min.js"></script> -->
    <script type="text/javascript">
       $('.js-example-basic-multiple').select2();
    </script>
  
</x-layout>

