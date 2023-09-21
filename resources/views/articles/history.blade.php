<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantines - Historiques</title>
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
                <h3 class="mt-3">Historique</h3>
                <a href="{{ route('articles.index') }}" class="btn btn-link my-3 btn-sm">Retour</a>
                <div class="row" style="position: sticky; top: 0;z-index: 1;background-color: rgb(247, 184, 184);">
                    <div class="col-lg-6">
                        <div class="row my-3">
                            <form action="{{route('cantine-date-posi')}}" method="get" class="text-right"
                                enctype="multipart/form-data">
                                @csrf
                                @method('get')
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="da">Date debut</label>
                                        <input type="date" id="da" class="form-control" name="dateA"
                                            onchange="getValue(this.value)">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="dateB">Date fin</label>
                                        <input type="date" class="form-control" name="dateB" id="dateB" readonly>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="l">Cantine</label>
                                        <select name="cadre" id="c" class="form-control text-center">
                                            <option value="1">Cantine 1</option>
                                            <option value="0">Cantine 2</option>
                                            <option value="2" selected>Tous</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <div class="my-3">
                                            <button type="submit" class="btn btn-info mt-2">Afficher</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col">
                        <form action="{{route('export.excel')}}" class="row my-3" method="post" id="formExport"
                            enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="col-lg-8">
                                <div id="alert"></div>
                            </div>
                            <div class="col">
                                <input type="hidden" name="dateA" value="{{$dateD}}" id="dateAE">
                                <input type="hidden" name="dateB" value="{{$dateF}}" id="dateBE">
                                <input type="hidden" name="cadre" value="{{$cadre}}" id="cadreGroupe">
                                <button type="submit" class="btn btn-link mt-4"
                                    style="float: right; margin-right: 30px; background-color: rgb(245, 239, 239);"
                                    title="Exporter en Pdf">Exporter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table">
                    <thead style="  background: lightcoral;position: sticky; top: 13.5%;z-index: 1;">
                        <tr>
                            <th class="text-center p-2">Date</th>
                            <th class="text-center p-2">Matricule</th>
                            <th class="text-center p-2">Societe</th>
                            <th class="text-center p-2">Nom</th>
                            <th class="text-center p-2">Prenom</th>
                            <th class="text-center p-2">Cadre</th>
                            <th class="text-center p-2">Dejeuner</th>
                            <th class="text-center p-2">Diner</th>
                            <th class="text-center p-2">Collation</th>
                            <th class="text-center p-2">Yaourt jour</th>
                            <th class="text-center p-2">Yaourt nuit</th>
                            <th class="text-center p-2">Café</th>
                            <th class="text-center p-2">Thé</th>
                            <!-- <th class="text-center p-2">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $article)
                        <tr>
                            <td>{{ $article->date }}</td>
                            <th>{{ $article->matricule }}</th>
                            <td>{{ $article->societe }}</td>
                            <td>{{ $article->nom }}</td>
                            <td>{{ $article->prenom }}</td>
                            <td>{{ ($article->cadre==1 ? "Cantine 1":"Cantine 2") }}</td>
                            <td class="text-center">{{ $article->dejeuner }}</td>
                            <td class="text-center">{{ $article->diner }}</td>
                            <td class="text-center">{{ $article->collation }}</td>
                            <td class="text-center">{{ $article->yaourtj }}</td>
                            <td class="text-center">{{ $article->yaourtn }}</td>
                            <td class="text-center">{{ $article->cafe }}</td>
                            <td class="text-center">{{ $article->the }}</td>
                            <!-- <td style="text-align: center">
                                <form action="{{ route('articles.destroy', $article->matricule) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mt-1"
                                        title="Supprimer">Supprimer</button>
                                </form>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script>
        function getValue(date) {
            var dateForm = document.getElementById("datePDF");
            var dateB = document.getElementById("dateB");
            dateB.min = date;
            dateB.removeAttribute("readonly");
        }
        function getValueMatri(matric) {
            var select = document.getElementById("c");
            if (matric != "") {
                select.disabled = true;
            } else {
                select.disabled = false;
            }
        }

        function getValueSelect(select) {
            if (select != "tous") {
                document.getElementById("matrix").disabled = "true";
            } else {
                document.getElementById("matrix").disabled = "false";
            }
        }

        // Récupérez le formulaire par son ID
        var formulaire = document.getElementById('formExport');
        var c = document.getElementById("cadreGroupe").value;
        var dae = document.getElementById("dateAE").value;
        var dbe = document.getElementById("dateBE").value;
        // Ajoutez un gestionnaire d'événements pour l'événement de soumission du formulaire
        formulaire.addEventListener('submit', function (event) {            
            if (c == "2" || dbe == "") {
                // Annulez la soumission du formulaire
                event.preventDefault();
                document.getElementById("alert").innerHTML = "Grouper par cantine et mettez une intervalle de date avant d'exporter s'il vous plait."
                setTimeout(function () {
                    document.getElementById("alert").innerHTML = ""
                }, 3000);
            }
        });

    </script>
</body>

</html>