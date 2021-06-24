@extends('admin.layout')
@section('heads')
    
@endsection
@section('contect')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Slider/administración</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 card p-5">
                <div class="row">
                @for ($i = 0; $i < 5; $i++)
                    @if (!empty($gallery[$i]))
                        <div class="col-xl-3 mb-3">
                            <div class="card">
                                <img class="height-image-card card-img-top" src="{{asset('media/sliderIndex/'.$gallery[$i]->image)}}" id="image{{$i}}">
                                <div class="card-body">
                                    <h5 class="card-title" id="title{{$i}}">{{$gallery[$i]->title}}</h5>
                                    <p class="card-text" id="date{{$i}}">{{$gallery[$i]->date}}</p>
                                    <span class="btn btn-success pointer" data-toggle="tooltip" data-placement="top" title="Editar información" onclick="openModal({{$i}}, {{$gallery[$i]->id}}, '{{$gallery[$i]->title}}', '{{$gallery[$i]->date}}')" id="save{{$i}}"><i class="far fa-edit"></i></span>
                                    <span class="btn btn-danger pointer" data-toggle="tooltip" data-placement="top" title="Eliminar información" onclick="deleteInfo({{$i}}, {{$gallery[$i]->id}})" id="delete{{$i}}"><i class="fas fa-trash-alt"></i></span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-xl-3 mb-3">
                            <div class="card">
                                <img class="height-image-card card-img-top" src="{{asset('media/general/not_image.png')}}" id="image{{$i}}">
                                <div class="card-body">
                                    <h5 class="card-title" id="title{{$i}}">Imagen {{$i + 1}}</h5>
                                    <p class="card-text text-white" id="date{{$i}}">..</p>
                                    <span class="btn btn-success pointer" data-toggle="tooltip" data-placement="top" title="Editar información" onclick="openModal({{$i}})" id="save{{$i}}"><i class="far fa-edit"></i></span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endfor
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalSlider" tabindex="-1" aria-labelledby="modalSliderLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formModalSlider">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalSliderLabel">Editar información</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <label>Imágen:</label><br>
                            <input class="mb-3" type="file" id="image" required><br>
                            <label>Título:</label>
                            <input class="form-control mb-3" type="text" id="title" required>
                            <label>Fecha de inicio:</label>
                            <input class="form-control" type="date" id="initial_date" required>
                            <input class="form-control" type="hidden" id="idSlider">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{asset('js/admin/slider.js')}}"></script>
@endsection