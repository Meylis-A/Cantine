<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantines - Nouveau employé</title>
</head>
<style>
    body {
        background: rgb(245, 239, 239);
    }
</style>

<body>
    <div class="container">
        <div class="row mb-5 justify-content-center">
            <div class="col-md-6 mt-5">
                <h1 class="mt-2">Ajouter</h1>
                <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="societe">Societe</label>
                        <select name="societe" id="societe" class="form-control">
                            <option value="Wimmo">Wimmo</option>
                            <option value="Enduma">Enduma</option>
                            <option value="AgroFood">AgroFood</option>
                            <option value="Orkidex">Orkidex</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="matricule">Matricule</label>
                        <input type="text" name="matricule" id="matricule" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prenom</label>
                        <input type="text" name="prenom" id="prenom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="cadre">Cantine</label>
                        <select name="cadre" id="societe" class="form-control">
                            <option value="1" selected>Cantine 1</option>
                            <option value="0">Cantine 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" name="photo" id="photo" class="form-control" required>
                    </div>
                    <a href="{{route('articles.index')}}" class="btn btn-primary  mt-3 btn-sm">Annuler</a>
                    <button type="submit" class="btn btn-success mt-3 btn-sm">Enregistrer</button>

                </form>
            </div>
        </div>
    </div>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')"></script>
</body>

</html>