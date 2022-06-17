<x-layout>
<div class="container mt-5">
        <h2>Inserisci La Nuova Categoria:</h2>
        <div class="row">
            <div class="col-12 col-md-6 mt-5">
                <form method="POST" action="{{route('addNewCategory')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputName" class="form-label">Nome :</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputSurname" class="form-label">Descrizione :</label>
                        <input type="text" class="form-control" name="description" aria-describedby="emailHelp" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Aggiungi</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>