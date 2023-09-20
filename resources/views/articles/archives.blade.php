<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantines - Archives</title>
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
                    <div class="col-lg-6 mb-3">
                        <a href="{{ route('articles.index') }}" class="btn btn-primary my-3 btn-sm">Retour</a>
                    </div>

                    <div class="col">
                        <form action="{{route('cantines.cherche-liste')}}" method="get" class="row"
                            enctype="multipart/form-data">
                            @csrf
                            @method('get')
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4">
                                <label for="l">Cantine</label>
                                <select name="cadre" id="c" class="form-control text-center my-2"
                                    onchange="getValueSelect(this.value)">
                                    <option value="1">Cantine 1</option>
                                    <option value="0">Cantine 2</option>
                                    <option value="2" selected>Tous</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="l">Matricule</label>
                                <input type="text" name="matricule" id="matricule" class="form-control my-2"
                                    placeholder="Matricule" onkeyup="getValue(this.value)">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-sm btn-info mt-4">Afficher</button>
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
                                <th>Cadre</th>
                                <th>Photo</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articles as $article)
                            <tr>
                                <th>{{ $article->matricule }}</th>
                                <td>{{ $article->societe }}</td>
                                <td>{{ $article->nom }}</td>
                                <td>{{ $article->prenom }}</td>
                                <td>{{ ($article->cadre=="1" ? "Cantine 1" : "Cantine 2") }}</td>
                                <td><img src="{{asset('images/' . $article->matricule . '.png')}}"
                                        class="img-fluid" alt="" width="30%" height="25%" style="float: right"></td>
                                <td class="text-center">
                                    <form action="{{ route('archves.restaurer') }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="idEtat" value="{{$article->id}}">
                                        <button type="submit" class="btn btn-sm btn-danger mt-1">Restaurer</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script>
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