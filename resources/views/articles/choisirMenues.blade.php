<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantines - Menues</title>
    <style>
        body {
            background: rgb(245, 239, 239);
        }
    </style>
</head>

<body>
    @section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="mt-5">Choix menues</h4>
                <a href="{{ route('articles.index') }}" class="btn btn-link my-3 btn-sm">Retour</a><br>
                <h3><span>{{$emp->nom}} {{$emp->prenom}}</span><br></h3>
                <span>{{($emp->cadre==1 ? "Cantine 1":"Cantine 2")}}</span>
                <hr>
                <form action="{{route('articles.choix')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('post')
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="date" class="form-control" name="dateA" id="datepicker" required
                                onchange="getValue(this.value)">
                        </div>
                        à
                        <div class="col-lg-4">
                            <input type="date" class="form-control" name="dateB" id="datepicker2" required
                                onchange="afficheForm(this.value, '{{$emp->cadre}}')">
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <input type="hidden" name="nombreJour" id="nombreJour">
                                    <input type="hidden" name="matricule" value="{{$emp->matricule}}">
                                    <input type="hidden" name="cadre" value="{{$emp->cadre}}">
                                    <button type="submit" class="btn btn-success btn-sm" id="btnTerminer"
                                        style="align-content: right; visibility: hidden;">Terminer</button>
                                </div>
                                <div class="col">
                                    <textarea name="message" id="messageT" cols="10" rows="1" class="form-control"
                                        placeholder="Petit message" style="visibility: hidden;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <table class="table">
                        <thead style="background-color: lightcoral;">
                            <tr>
                                <th class="text-center p-2">Date</th>
                                <th class="text-center p-2">Dejeuner</th>
                                <th class="text-center p-2">Diner</th>
                                <th class="text-center p-2">Collation</th>
                                <th class="text-center p-2">Yaourt jour</th>
                                <th class="text-center p-2">Yaourt nuit</th>
                                <th class="text-center p-2">Café</th>
                                <th class="text-center p-2">Thé</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script>
        var datePicker2 = document.getElementById('datepicker2');
        var datePicker = document.getElementById('datepicker');
        var currentDate = new Date().toISOString().split('T')[0];
        datePicker.min = currentDate;

        function getValue(date) {
            datePicker2.min = date;
        }

        function afficheForm(dateB, cadre) {
            dateA = new Date(datePicker.value);
            dateB = new Date(dateB);

            document.getElementById("btnTerminer").style.visibility = "visible";
            document.getElementById("messageT").style.visibility = "visible";

            // Calculer la différence en millisecondes
            var differenceEnMilliseconds = dateB - dateA;

            // Convertir la différence en jours
            var differenceEnJours = differenceEnMilliseconds / (1000 * 60 * 60 * 24);

            document.getElementById("nombreJour").value = differenceEnJours;

            var tbody = document.getElementById("tableBody");
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }
            for (var i = 0; i < differenceEnJours + 1; i++) {
                // Créez une nouvelle ligne
                var newRow = document.createElement("tr");

                // Créez un champ date pour la nouvelle ligne
                var newDateInput = dateInput(i);
                var dejInput = dejeunerInput(i);

                var newCell = newRow.insertCell(0);
                var newCell1 = newRow.insertCell(1);

                newCell.appendChild(newDateInput);
                newCell1.appendChild(dejInput);

                if (cadre == '0') {
                    var dinnerInpt = dinerInput(i);
                    var collationInpt = collationInput(i);
                    var yaourtjInpt = yaourtjInput(i);
                    var yaourtnInpt = yaourtnInput(i);
                    var cafeInpt = cafeInput(i);
                    var theInpt = theInput(i);
                    // ajouts des champs
                    var newCell2 = newRow.insertCell(2);
                    var newCell3 = newRow.insertCell(3);
                    var newCell4 = newRow.insertCell(4);
                    var newCell5 = newRow.insertCell(5);
                    var newCell6 = newRow.insertCell(6);
                    var newCell7 = newRow.insertCell(7);
                    newCell2.appendChild(dinnerInpt);
                    newCell3.appendChild(collationInpt);
                    newCell4.appendChild(yaourtjInpt);
                    newCell5.appendChild(yaourtnInpt);
                    newCell6.appendChild(cafeInpt);
                    newCell7.appendChild(theInpt);
                }


                // Ajoutez la nouvelle ligne au tbody
                tbody.appendChild(newRow);

                // Incrémente la date de départ pour la prochaine itération
                dateA.setDate(dateA.getDate() + 1);
            }

            console.log(differenceEnJours);
        }

        function dateInput(params) {
            var newDateInput = document.createElement("input");
            newDateInput.type = "date";
            newDateInput.name = "date" + params;
            newDateInput.className = "form-control";
            newDateInput.value = formatDate(dateA); // Formatage de la date
            newDateInput.readOnly = true;
            newDateInput.style.textAlign = "center";
            return newDateInput;
        }
        function dejeunerInput(params) {
            var newInput = document.createElement("input");
            newInput.type = "number";
            newInput.name = "dejeuner" + params;
            newInput.className = "form-control";
            newInput.min = "0";
            newInput.max = "3";
            newInput.style.textAlign = "center";
            return newInput;
        }
        function dinerInput(params) {
            var newInput = document.createElement("input");
            newInput.type = "number";
            newInput.name = "dinner" + params;
            newInput.className = "form-control";
            newInput.min = "0";
            newInput.max = "3";
            newInput.style.textAlign = "center";
            return newInput;
        }
        function collationInput(params) {
            var newInput = document.createElement("input");
            newInput.type = "number";
            newInput.name = "collation" + params;
            newInput.className = "form-control";
            newInput.min = "0";
            newInput.max = "3";
            newInput.style.textAlign = "center";
            return newInput;
        }
        function yaourtjInput(params) {
            var newInput = document.createElement("input");
            newInput.type = "number";
            newInput.name = "yaourtj" + params;
            newInput.className = "form-control";
            newInput.min = "0";
            newInput.max = "3";
            newInput.style.textAlign = "center";
            return newInput;
        }
        function yaourtnInput(params) {
            var newInput = document.createElement("input");
            newInput.type = "number";
            newInput.name = "yaourtn" + params;
            newInput.className = "form-control";
            newInput.min = "0";
            newInput.max = "3";
            newInput.style.textAlign = "center";
            return newInput;
        }
        function cafeInput(params) {
            var newInput = document.createElement("input");
            newInput.type = "number";
            newInput.name = "cafe" + params;
            newInput.className = "form-control";
            newInput.min = "0";
            newInput.max = "3";
            newInput.style.textAlign = "center";
            return newInput;
        }
        function theInput(params) {
            var newInput = document.createElement("input");
            newInput.type = "number";
            newInput.name = "the" + params;
            newInput.className = "form-control";
            newInput.min = "0";
            newInput.max = "3";
            newInput.style.textAlign = "center";
            return newInput;
        }

        function formatDate(date) {
            var year = date.getFullYear();
            var month = String(date.getMonth() + 1).padStart(2, "0");
            var day = String(date.getDate()).padStart(2, "0");
            return year + "-" + month + "-" + day;
        }

    </script>
</body>

</html>