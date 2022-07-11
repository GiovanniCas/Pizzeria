<x-layout>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <h1 class="display-1 fw-bold text-center">Pizzeria Gio</h1>
    <div class="container ">
        <div class="container d-flex justify-content-between   my-5">
            @foreach($categories as $category)
                <div class="card mt-5 sfondo-categoria" style="width: 20rem; height:13rem ;border: none; background-image: url({{Storage::url($category->img)}})">
                    <!-- <img src="{{Storage::url($category->img)}}" class="card-img-top" style="height: 191px;"> -->
                    <div class="card-body d-flex flex-direction-column justify-content-around" style="width: 100%;">
                        
                        <div class="my-category" style="color-white; text-decoration:none;">
                            <form href="{{route('categoryView' , compact('category'))}}" method="post" > 
                                    <h2 class="text-center" ><a href="{{route('categoryView' , compact('category'))}}" class="card-title" style="color: white; text-decoration: none;">{{$category->name}}</a></h2>
                                </form> 
                                <!-- <h5>{{$category->name}}</h5> -->
                                <h5 class="card-text fw-bold mt-3">{{$category->description}}</h5>
                            @can('Gestore')
                            
                            <div class="d-flex justify-content-around mt-5">
                                <form href="{{route('modifyCategory' , compact('category'))}}" method="get"> 
                                    <a href="{{route('modifyCategory' , compact('category'))}}" class="btn btn-primary">Modifica</a>
                                </form>
                                <form action="{{route('deleteCategory' , compact('category'))}}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <button  class="btn btn-danger">Elimina</button>
                                </form>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @can('Gestore')
            <div class="d-flex justify-content-end">
                <a href="{{route('addCategory')}}" class="btn btn-danger mt-5">Nuova Categoria </a>
            </div>
        @endcan
    </div>
    <div class="container">
        <h2 class="display-1 text-center my-3">Scegli, Ordina e Gusta</h2>
        <div class= "d-flex justify-content-around mt-5">
            <div>
                <img width="200" height="200" src="https://www.donnatinapizza.com/wp-content/uploads/2020/04/pizza.png" class="vc_single_image-img attachment-full" alt="" srcset="https://www.donnatinapizza.com/wp-content/uploads/2020/04/pizza.png 200w, https://www.donnatinapizza.com/wp-content/uploads/2020/04/pizza-150x150.png 150w, https://www.donnatinapizza.com/wp-content/uploads/2020/04/pizza-100x100.png 100w, https://www.donnatinapizza.com/wp-content/uploads/2020/04/pizza-60x60.png 60w" sizes="(max-width: 200px) 100vw, 200px">
                <p style="text-align: center; font-size: 18px;">Scegli la tua pizzetta,<br>calzone o focaccia preferita.</p>
            </div>
            <div>
                <img width="200" height="200" src="https://www.donnatinapizza.com/wp-content/uploads/2020/04/online.png" class="vc_single_image-img attachment-full" alt="" srcset="https://www.donnatinapizza.com/wp-content/uploads/2020/04/online.png 200w, https://www.donnatinapizza.com/wp-content/uploads/2020/04/online-150x150.png 150w, https://www.donnatinapizza.com/wp-content/uploads/2020/04/online-100x100.png 100w, https://www.donnatinapizza.com/wp-content/uploads/2020/04/online-60x60.png 60w" sizes="(max-width: 200px) 100vw, 200px">
                <p style="text-align: center; font-size: 18px;">Ordina<br>in completa autonomia.</p>
            </div>
            <div>
                <img width="200" height="200" src="https://www.donnatinapizza.com/wp-content/uploads/2020/04/asporto.png" class="vc_single_image-img attachment-full" alt="" srcset="https://www.donnatinapizza.com/wp-content/uploads/2020/04/asporto.png 200w, https://www.donnatinapizza.com/wp-content/uploads/2020/04/asporto-150x150.png 150w, https://www.donnatinapizza.com/wp-content/uploads/2020/04/asporto-100x100.png 100w, https://www.donnatinapizza.com/wp-content/uploads/2020/04/asporto-60x60.png 60w" sizes="(max-width: 200px) 100vw, 200px">
                <p style="text-align: center; font-size: 18px;">Asporto e consegna a domicilio<br>dove e quando vuoi!</p>
            </div>
        </div>
    </div>
</x-layout>