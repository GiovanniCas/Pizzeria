<x-layout>
<div class="container-fluid d-flex">
            <div>
                <form method="post" action="{{route('search')}}"class="d-flex">
                    @csrf
                    <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" name="search">

                    <label for="exampleInputCategory" class="form-label">Categoria</label>
                    <select name="mansione">
                        @foreach($users as $user)
                        <option value="{{$user->mansione}}">
                            {{$user->mansione}}
                        </option>   
                        @endforeach
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
                            <form action="" method="get">
                                <button  class="btn btn-info" >Modifica</button>
                            </form>
                            <form action="" method="post">
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
</x-layout>