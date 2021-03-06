<x-layout>
<div class="container mt-5">
        <h2>Procedi con il tuo ordine:</h2>
        <div class="row">
            <div class="col-12 col-md-6 mt-5">
                <form method="POST" action="{{route('orderSubmit')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputName" class="form-label">Nome :</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputSurname" class="form-label">Cognome :</label>
                        <input type="text" class="form-control" name="surname" aria-describedby="emailHelp" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputCitta" class="form-label">Città :</label>
                        <input type="text" class="form-control" name="citta" aria-describedby="emailHelp" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="exampleInputIndirizzo" class="form-label">Indirizzo :</label>
                        <input type="text" class="form-control" name="indirizzo" aria-describedby="emailHelp" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputCap" class="form-label">C.A.P. :</label>
                        <input type="text" class="form-control" name="cap" aria-describedby="emailHelp" required>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail" class="form-label">Email :</label>
                        <input type="email" class="form-control" name="email" aria-describedby="emailHelp" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="active" for="expireDate">Data Consegna :</label>
                        <input type="date" id="datepicker"  class="form-control" name="data" required>
                    </div>
                    <div class="form-group">
                        <label class="active" for="timeStandard">Ora :</label>
                        <input class="form-control" id="timeStandard" name="time" type="time" required>
                    </div>
                  

                    <button type="submit" class="btn btn-primary mt-3">Ordina</button>
                </form>
            </div>
        </div>
    </div>

</x-layaout>