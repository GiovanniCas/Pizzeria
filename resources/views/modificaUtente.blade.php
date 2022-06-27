@php
    use App\Models\User;
@endphp
<x-layout>
<div class="container mt-5">
        <h2>Modifica Utenti</h2>
        <div class="row">
            <div class="col-12 col-md-6 mt-5">
                <form method="POST" action="{{route('updateUser' , compact('user'))}}">
                @method('put')    
                @csrf
                    <div class="mb-3">
                        <label for="exampleInputName" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp" value="{{$user->name}}" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputSurname" class="form-label">Cognome</label>
                        <input type="text" class="form-control" name="surname" aria-describedby="emailHelp" value="{{$user->surname}}" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" aria-describedby="emailHelp" value="{{$user->email}}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="exampleInputPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" aria-describedby="emailHelp" value="{{$user->password}}" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputRuolo" class="form-label">Mansione</label>
                        <select name="ruolo" value="{{$user->ruolo}}" required>
                            <option value="{{User::CUOCO}}"> Cuoco </option>   
                            <option value="{{User::FATTORINO}}"> Fattorino </option>   
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Modifica</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>