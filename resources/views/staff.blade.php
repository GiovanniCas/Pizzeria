<x-layout>
    
    <div class="container mt-5">
        <h2>Aggiungi Personale</h2>
        <div class="row">
            <div class="col-12 col-md-6 mt-5">
                <form method="POST" action="{{route('addStaff')}}">
                @csrf
                    <div class="mb-3">
                        <label for="exampleInputName" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputSurname" class="form-label">Cognome</label>
                        <input type="text" class="form-control" name="surname" aria-describedby="emailHelp" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" aria-describedby="emailHelp" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="exampleInputPassword" class="form-label">Password</label>
                        <input type="text" class="form-control" name="password" aria-describedby="emailHelp" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputMansione" class="form-label">Mansione</label>
                        <input type="text" class="form-control" name="mansione" aria-describedby="emailHelp" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Aggiungi</button>
                </form>
            </div>
        </div>
    </div>

</x-layout>