<x-layout>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <h1 class="bg-danger">Pizzeria Gio</h1>
    <div class="container d-flex justify-content-around my-5">
        @foreach($categories as $category)
            <div class="card mt-5" style="width: 18rem;">
                <img src="{{Storage::url($category->img)}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <form href="{{route('categoryView' , compact('category'))}}" method="post"> 
                        <h5><a href="{{route('categoryView' , compact('category'))}}" class="card-title">{{$category->name}}</a></h5>
                    </form>
                    <!-- <h5>{{$category->name}}</h5> -->
                    <p class="card-text">{{$category->description}}</p>
                    <div>
                        @can('Gestore')
                            <form href="{{route('modifyCategory' , compact('category'))}}" method="get"> 
                                <a href="{{route('modifyCategory' , compact('category'))}}" class="btn btn-primary">Modifica</a>
                            </form>
                            <form action="{{route('deleteCategory' , compact('category'))}}" method="post">
                                @method('DELETE')
                                @csrf
                                <button  class="btn btn-danger">Elimina</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @can('Gestore')
        
        <a href="{{route('addCategory')}}" class="btn btn-danger mt-5">Nuova Categoria </a>
    @endcan

    
</x-layout>