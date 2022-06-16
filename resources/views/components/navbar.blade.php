<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{route('welcome')}}">Gio</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        @guest
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{route('welcome')}}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('pizza')}}">Prodotti</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('cart')}}"><i class="fa-solid fa-cart-shopping"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('login')}}">LogIn</a>
        </li>
        @else
        @can('Gestore')
        <li class="nav-item">
          <a class="nav-link" href="{{route('pizza')}}">Prodotti</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('newProduct')}}">Aggiungi Prodotto</a>
        </li>   
        <li class="nav-item">
          <a class="nav-link" href="{{route('staff')}}">Personale</a>
        </li>
        @endcan  
        @can('Gestore' , 'Cuoco')
        <li class="nav-item">
          <a class="nav-link" href="{{route('revisor')}}">Cuoco</a>
        </li>
        @endcan 
        @can('Gestore' , 'Fattorino')
        <li class="nav-item">
          <a class="nav-link" href="{{route('consegne')}}">Consegne</a>
        </li>   
        <li class="nav-item">
          <a class="nav-link" href="{{route('fattorino')}}">Le Tue Consegne</a>
        </li> 
        @endcan
        <li>
          <a class="nav-link" href="{{ route('logout') }}"onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
        </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
  
      </ul>
      
    </div>
  </div>
  @endguest
</nav>