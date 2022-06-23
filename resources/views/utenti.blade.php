<x-layout>

<div class="container-fluid d-flex">
            <div>
                <form method="post" action="{{route('searchUser')}}"class="d-flex">
                    @csrf
                    <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" name="search">

                    <!-- <label for="exampleInputCategory" class="form-label">Mansione</label> -->
                   
                  
                    
                        

                            <select  name="mansione[]" class="js-example-basic-multiple" multiple="multiple" style="width: 100%;" placeholder="ciao">
                                <option value="Tutte">Tutte</option>   
                                <option value="Gestore">Gestore</option>   
                                <option value="Cuoco">Cuoco</option>   
                                <option value="Fattorino">Fattorino</option>   
                            </select> 
                            
                    
                
                    <button class="btn btn-outline-success my-btn" type="submit">Search</button>
                </form>
            </div>
        </div>
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
                    <td>{{$user->mansione}}</td>
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
    <!-- <script src="{{Config::get('app.url')}}/node_modules/select2/dist/js/select2.min.js"></script> -->
    <script type="text/javascript">
       $('.js-example-basic-multiple').select2();
    </script>
  
</x-layout>

