<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles - Détails</title>
    <style>
        body {
            background: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="mt-5">{{ $article->titre }}</h1>
                <p>{{ $article->resume }}</p>
                <?= $contenu ?>
                <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display: inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                <a href="{{route('articles.index')}}" class="btn btn-primary my-3">Retour</a>
            </div>
        </div>
    </div>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
</body>

</html>