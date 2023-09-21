<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantines - Listes</title>
    <style>
        body {
            background: rgb(245, 239, 239);

        }
    </style>
</head>

<body>
    @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="row" style="position: sticky; top: 0;z-index: 1; background-color: rgb(250, 250, 250)">
                    <hr>
                    <div class="col-lg-8 mb-3">
                        <a href="{{ route('articles.history') }}" class="btn btn-link my-3 ">Historique</a>
                        <a href="{{ route('articles.cantine') }}" class="btn btn-link my-3 " data-bs-toggle="modal"
                            data-bs-target="#verticalycentered">Importer les choix</a>
                        <a href="{{ route('articles.archives') }}" class="btn btn-link my-3 ">Archives</a>
                        <a href="{{ route('articles.create') }}" class="btn btn-link my-3 ">Nouvelle employée</a>
                        <a href="{{ route('articles.cantine') }}" class="btn btn-link my-3 ">Cantine</a>
                        @error('file')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <form action="{{route('cantines.cherche-liste')}}" method="get" class="row"
                            enctype="multipart/form-data">
                            @csrf
                            @method('get')
                            <div class="col-lg-4">
                                <label for="l">Cantine</label>
                                <select name="cadre" id="c" class="form-control text-center my-2"
                                    onchange="getValueSelect(this.value)">
                                    <option value="1">Cantine-1</option>
                                    <option value="0">Cantine-2</option>
                                    <option value="2" selected>Tous</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="l">Matricule</label>
                                <input type="text" name="matricule" id="matricule" class="form-control my-2"
                                    placeholder="Matricule" onkeyup="getValue(this.value)">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-info" style="margin-top: 0.81cm">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row" style="position: relative;">
                    <table class="table">
                        <thead>
                            <tr style="position: sticky; top: 12%;z-index: 1; background-color: lightcoral">
                                <th>Matricule</th>
                                <th>Societe</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Cantine</th>
                                <th class="text-center">Photo</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articles as $article)
                            <tr>
                                <th style="vertical-align: middle">{{ $article->matricule }}</th>
                                <td style="vertical-align: middle">{{ $article->societe }}</td>
                                <td style="vertical-align: middle">{{ $article->nom }}</td>
                                <td style="vertical-align: middle">{{ $article->prenom }}</td>
                                <td style="vertical-align: middle">{{ ($article->cadre=="1" ? "Cantine 1" : "Cantine 2")
                                    }}
                                </td>
                                <td class="justify-content-center d-flex"><img
                                        src="{{asset('images/' . trim($article->matricule) . '.png')}}"
                                        class="img-fluid" alt="{{$article->photo}}" width="30%" height="20%"
                                        style="float: right"></td>
                                <td class="text-center">
                                    @if($article->cadre == 1)
                                    <div>
                                        <a class="btn btn-primary btn-sm" style="width: 87.09px"
                                            href="{{route('articles.choisir',['matricule'=>$article->matricule])}}"
                                            title="Choisir entre les menues">Choisir</a>
                                    </div>
                                    <form action="{{ route('cantines.affiche-choix') }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="matricule" value="{{$article->matricule}}">
                                        <button type="submit" class="btn btn-sm btn-info mt-1"
                                            title="Afficher les choix effectuer"
                                            style="width: 87.09px">Afficher</button>
                                    </form><br>
                                    @endif
                                    <form action="{{ route('articles.destroy') }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="idEtat" value="{{$article->id}}">
                                        <button type="submit" class="btn btn-sm btn-danger mt-1">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- modal importation choix --}}
            <div class="modal fade" id="verticalycentered" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Importation choix</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('import.process')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <label for="prix_normale" class="col-md-4 col-lg-3 col-form-label">
                                        Fichier (xlsx)</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input class="form-control" type="file" min="0" name="file" id="prix_normale"
                                            accept=".xlsx" required />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="id_spectacle" id="id_spec" />
                                <button type="submit" class="btn btn-warning">Importer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script>
        var datePicker2 = document.getElementById('datepicker2');
        var datePicker = document.getElementById('datepicker');
        var currentDate = new Date().toISOString().split('T')[0];
        datePicker.min = currentDate;

        function getValueDate(date) {
            datePicker2.min = date;
        }

        function getValue(matric) {
            var select = document.getElementById("c");
            if (matric != "") {
                select.disabled = true;
            } else {
                select.disabled = false;
            }
        }
        function getValueSelect(value) {
            var select = document.getElementById("matricule");
            if (value != "2") {
                select.disabled = true;
            } else {
                select.disabled = false;
            }
        }
    </script>
</body>

</html>