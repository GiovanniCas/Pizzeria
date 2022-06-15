<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{route('welcome')}}">Gio</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{route('welcome')}}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('pizza')}}">Prodotti</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('newProduct')}}">Aggiungi Prodotto</a>
        </li>     
        <li class="nav-item">
          <a class="nav-link" href="{{route('revisor')}}">Revisore</a>
        </li>  
        <li class="nav-item">
          <a class="nav-link" href="{{route('staff')}}">Personale</a>
        </li>  
        <li class="nav-item">
          <a class="nav-link" href="{{route('cart')}}"><i class="fa-solid fa-cart-shopping"></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>