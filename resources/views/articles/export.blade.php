<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table class="table" border="1" style="border-collapse: 1px">
        <thead>
            <tr>
                <th class="text-center p-2">Matricule</th>
                <th class="text-center p-2">Societe</th>
                <th class="text-center p-2">Nom</th>
                <th class="text-center p-2">Prenom</th>
                <th class="text-center p-2">Cadre</th>
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
        <tbody>
            @foreach ($history as $article)
            <tr>
                <th>{{ $article->matricule }}</th>
                <td>{{ $article->societe }}</td>
                <td>{{ $article->nom }}</td>
                <td>{{ $article->prenom }}</td>
                <td>{{ $article->cadre }}</td>
                <td>{{ $article->date }}</td>
                <td class="text-center">{{ $article->dejeuner }}</td>
                <td class="text-center">{{ $article->diner }}</td>
                <td class="text-center">{{ $article->collation }}</td>
                <td class="text-center">{{ $article->yaourtj }}</td>
                <td class="text-center">{{ $article->yaourtn }}</td>
                <td class="text-center">{{ $article->cafe }}</td>
                <td class="text-center">{{ $article->the }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>