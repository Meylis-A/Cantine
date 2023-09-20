<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body>


    <div>
        <div class="p-5 text-white" >
            <div class="ml-12">
                <span class="ts-title">Ajout Article</span>
                <form action="" method="post" class="form-control">
                    <label for="titre">Titre</label>
                    <input type="text" class="form-control" name="titre" id="titre" />
                    <label for="resume">Résume</label>
                    <input type="text" class="form-control" name="resume" id="resume" />
                    <label for="catego">Ctégorie</label>
                    <select name="categorie" class="form-control" id="catego">
                        <option value="1">Vetement</option>
                        <option value="2">Autre</option>
                    </select>
                    <label for="contenu">Contenu</label>
                    <textarea name="contenu" class="form-control" id="editor" cols="30" rows="5"></textarea>
                    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
                    <script>
                        CKEDITOR.replace("editor");
                    </script>
                </form>


            </div>
        </div>
    </div>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
</body>

</html>