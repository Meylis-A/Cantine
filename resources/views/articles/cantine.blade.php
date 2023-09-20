<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantines - cantines</title>
</head>
<style>
    body {
        background: rgb(245, 239, 239);
    }
</style>

<body>
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="row">
                    <h1 class="mt-5">CANTINES</h1>
                    <a href="{{route('articles.index')}}" class="btn btn-link my-3 btn-sm"
                        style="width: inherit;">Retour</a>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="matricule">Matricule</label>
                                <input type="text" name="matricule" id="matriculeA" class="form-control"
                                    style="width: 80%;" placeholder="Matricule" onkeyup="afficheInfo()">
                            </div>
                            <div class="col">
                                <label for="date">Date</label>
                                <input type="date" name="datem" id="datem" class="form-control" style="width: 80%;"
                                    onchange="afficheInfo()">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-6">
                        <div id="image-cantine">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col ml-3 mt-2">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <h4>Matricule : </h4>
                            </div>
                            <div class="col">
                                <h4 id="matr"></h4>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-lg-4">
                                <h4>Nom : </h4>
                            </div>
                            <div class="col">
                                <h4 id="nom"></h4>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-lg-4">
                                <h4>Prenom : </h4>
                            </div>
                            <div class="col">
                                <h4 id="prenom"></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">

                    <table class="table">
                        <thead>
                            <th class="text-center p-2">Date</th>
                            <th class="text-center p-2">Dejeuner</th>
                            <th class="text-center p-2">Diner</th>
                            <th class="text-center p-2">Collation</th>
                            <th class="text-center p-2">Yaourt jour</th>
                            <th class="text-center p-2">Yaourt nuit</th>
                            <th class="text-center p-2">Café</th>
                            <th class="text-center p-2">Thé</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" id="date"></td>
                                <td class="text-center" id="dejeuner"></td>
                                <td class="text-center" id="diner"></td>
                                <td class="text-center" id="collation"></td>
                                <td class="text-center" id="yaourtj"></td>
                                <td class="text-center" id="yaourtn"></td>
                                <td class="text-center" id="cafe"></td>
                                <td class="text-center" id="the"></td>
                            </tr>
                            <tr>
                                <td class="text-center" id="dateHistory"></td>
                                <td class="text-center" id="dejeunerHistory"></td>
                                <td class="text-center" id="dinerHistory"></td>
                                <td class="text-center" id="collationHistory"></td>
                                <td class="text-center" id="yaourtjHistory"></td>
                                <td class="text-center" id="yaourtnHistory"></td>
                                <td class="text-center" id="cafeHistory"></td>
                                <td class="text-center" id="theHistory"></td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- <textarea class="form-control" name="message" id="observation2" cols="15" rows="2" disabled
                        style="width: 75%;visibility: hidden;"></textarea> -->
                    <form action="{{ route('articles.store-cantine') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mt-3">
                            <select name="pourj" id="check" class="form-control mb-3"
                                style="visibility: hidden; width: 20%;">
                                <!-- <option value="dejeuner">Dejeuner</option> -->
                                <option value="dejeuner">Dejeuner</option>
                                <option id="dinerChoix" value="diner">Diner</option>
                                <option id="collationChoix" value="collation">Collation</option>
                                <option id="yaourtjChoix" value="yaourtj">Yaourt jour</option>
                                <option id="yaourtnChoix" value="yaourtn">Yaourt Nuit</option>
                                <option id="cafeChoix" value="cafe">Cafe</option>
                                <option id="theChoix" value="the">Thé</option>
                            </select>
                        </div>
                        <div class="row mb-3" style="visibility: hidden; width: 75%;" id="mess">
                            <textarea class="form-control" name="message" id="observation" cols="30" rows="3"
                                placeholder="Message"></textarea>
                            <input type="hidden" name="matricule" id="matriForm">
                            <input type="hidden" name="dateForm" id="dateForm">
                            <input type="hidden" name="cantine" id="cantine">
                            <button type="submit" class="btn btn-success mt-3" style="width: 20%;">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script>
        // pour le menues
        var date = document.getElementById("date");
        var dejeuner = document.getElementById("dejeuner");
        var diner = document.getElementById("diner");
        var collation = document.getElementById("collation");
        var yaourtj = document.getElementById("yaourtj");
        var yaourtn = document.getElementById("yaourtn");
        var cafe = document.getElementById("cafe");
        var the = document.getElementById("the");

        var selectD = document.getElementById("check");

        // pour l'historique
        var dateHistory = document.getElementById("dateHistory");
        var dejeunerHistory = document.getElementById("dejeunerHistory");
        var dinerHistory = document.getElementById("dinerHistory");
        var collationHistory = document.getElementById("collationHistory");
        var yaourtjHistory = document.getElementById("yaourtjHistory");
        var yaourtnHistory = document.getElementById("yaourtnHistory");
        var cafeHistory = document.getElementById("cafeHistory");
        var theHistory = document.getElementById("theHistory");

        // personnelle
        var nom = document.getElementById("nom");
        var prenom = document.getElementById("prenom");
        var matricule = document.getElementById("matr");
        var imagePersonne = document.getElementById("image-cantine");



        function afficheInfo() {
            var param = document.getElementById("matriculeA").value;
            var datee = document.getElementById("datem").value;

            if (datee == "") {
                var d = new Date();
                var year = d.getFullYear();
                var month = (d.getMonth() + 1).toString().padStart(2, '0'); // Notez l'ajout de 1 car les mois commencent à 0
                var day = d.getDate().toString().padStart(2, '0');
                datee = year + '-' + month + '-' + day;
                document.getElementById("dateForm").value = datee;
            } else {
                document.getElementById("dateForm").value = datee;
            }


            fetch('/cantines/' + param + '/' + datee)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    if (data[0][0] != null) { // personne
                        var perso = data[0][0];
                        nom.innerHTML = perso.nom;
                        prenom.innerHTML = perso.prenom;
                        matricule.innerHTML = perso.matricule;
                        document.getElementById("cantine").value = perso.cadre;
                        imagePersonne.innerHTML = " <img src=\"{{ asset('images/') }}/" + perso.matricule + ".png\" class=\"mt-3 justify-content-center d-flex\" width=\"80%\" height=\"80%\" alt =\"" + perso.matricule + "\">";
                    } else {
                        nom.innerHTML = "";
                        prenom.innerHTML = "";
                        matricule.innerHTML = "";
                    }

                    if (data[1][0] != null) { // choix

                        var menues = data[1][0];
                        var history = data[2][0]; // history
                        var perso = data[0][0]; // perso
                        // menues
                        document.getElementById("matriForm").value = data[0][0].matricule;

                        if (perso.cadre == "1") { // cantine 1
                            date.innerHTML = menues.date;
                            dejeuner.innerHTML = menues.dejeuner;
                            document.getElementById("dinerChoix").style.display = "none";
                            document.getElementById("collationChoix").style.display = "none";
                            document.getElementById("yaourtjChoix").style.display = "none";
                            document.getElementById("yaourtnChoix").style.display = "none";
                            document.getElementById("cafeChoix").style.display = "none";
                            document.getElementById("theChoix").style.display = "none";
                        } else {
                            date.innerHTML = "";
                            dejeuner.innerHTML = "";
                            document.getElementById("dinerChoix").style.display = "block";
                            document.getElementById("collationChoix").style.display = "block";
                            document.getElementById("yaourtjChoix").style.display = "block";
                            document.getElementById("yaourtnChoix").style.display = "block";
                            document.getElementById("cafeChoix").style.display = "block";
                            document.getElementById("theChoix").style.display = "block";
                        }
                        // historique


                        if (perso.cadre == "0") {
                            dateHistory.innerHTML = datee;
                            if (history != null) {
                                dejeunerHistory.innerHTML = (history.dejeuner == true ? "1" : "0");
                                dinerHistory.innerHTML = (history.diner == true ? "1" : "0");
                                collationHistory.innerHTML = (history.collation == true ? "1" : "0");
                                yaourtjHistory.innerHTML = (history.yaourtj == true ? "1" : "0");
                                yaourtnHistory.innerHTML = (history.yaourtn == true ? "1" : "0");
                                cafeHistory.innerHTML = (history.cafe == true ? "1" : "0");
                                theHistory.innerHTML = (history.the == true ? "1" : "0");
                            } else {
                                dateHistory.innerHTML = datee;
                                dejeunerHistory.innerHTML = "0";
                                dinerHistory.innerHTML = "0";
                                collationHistory.innerHTML = "0";
                                yaourtjHistory.innerHTML = "0";
                                yaourtnHistory.innerHTML = "0";
                                cafeHistory.innerHTML = "0";
                                theHistory.innerHTML = "0";
                            }

                        } else {
                            if (history != null) {
                                dinerHistory.innerHTML = "";
                                collationHistory.innerHTML = "";
                                yaourtjHistory.innerHTML = "";
                                yaourtnHistory.innerHTML = "";
                                cafeHistory.innerHTML = "";
                                theHistory.innerHTML = "";
                                dateHistory.innerHTML = history.date;
                                dejeunerHistory.innerHTML = (history.dejeuner == true ? "1" : "0");
                            } else {
                                dateHistory.innerHTML = datee;
                                dejeunerHistory.innerHTML = "0";
                                dinerHistory.innerHTML = "";
                                collationHistory.innerHTML = "";
                                yaourtjHistory.innerHTML = "";
                                yaourtnHistory.innerHTML = "";
                                cafeHistory.innerHTML = "";
                                theHistory.innerHTML = "";
                            }
                        }

                        //observation2.innerHTML = menues.message;
                        document.getElementById("check").style.visibility = "visible";
                        document.getElementById("mess").style.visibility = "visible";
                        // document.getElementById("observation2").style.visibility = "hidden";
                    } else {

                        document.getElementById("check").style.visibility = "visible";
                        document.getElementById("mess").style.visibility = "visible";
                        // document.getElementById("observation2").style.visibility = "hidden";
                        // menues
                        date.innerHTML = "";
                        dejeuner.innerHTML = "";
                        diner.innerHTML = "";
                        collation.innerHTML = "";
                        yaourtj.innerHTML = "";
                        yaourtn.innerHTML = "";
                        cafe.innerHTML = "";
                        the.innerHTML = "";
                        date.innerHTML = "";
                        if (perso.cadre == "0") {
                            dateHistory.innerHTML = datee;
                            dejeunerHistory.innerHTML = "0";
                            dinerHistory.innerHTML = "0";
                            collationHistory.innerHTML = "0";
                            yaourtjHistory.innerHTML = "0";
                            yaourtnHistory.innerHTML = "0";
                            cafeHistory.innerHTML = "0";
                            theHistory.innerHTML = "0";
                        } else {
                            // historique
                            dateHistory.innerHTML = "";
                            dejeunerHistory.innerHTML = "";
                            dinerHistory.innerHTML = "";
                            collationHistory.innerHTML = "";
                            yaourtjHistory.innerHTML = "";
                            yaourtnHistory.innerHTML = "";
                            cafeHistory.innerHTML = "";
                            theHistory.innerHTML = "";
                        }

                    }
                })
                .catch(error => {
                    // Gérez les erreurs ici
                    console.error(error);
                });

        }
    </script>
</body>

</html>