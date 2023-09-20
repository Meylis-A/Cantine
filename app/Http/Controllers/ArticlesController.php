<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Choix;
use App\Models\History;
use App\Models\Personnelle;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Person;
use Illuminate\Support\Str;
use SebastianBergmann\Environment\Console;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = Personnelle::where('etat', true)->get();
        foreach ($articles as $elem) {
            $lien_convivial = Str::slug($elem->nom . '-' . $elem->prenom, '-');
            $elem->url = $lien_convivial;

            $filename = $elem->photoname;
            $parts = explode('.', $filename);
            $format = end($parts);
            $elem->format = $format;
        }

        return view('articles.index', compact('articles'));
    }

    function archives()
    {
        $articles = Personnelle::where('etat', false)->get();
        foreach ($articles as $elem) {
            $lien_convivial = Str::slug($elem->nom . '-' . $elem->prenom, '-');
            $elem->url = $lien_convivial;

            $filename = $elem->photoname;
            $parts = explode('.', $filename);
            $format = end($parts);
            $elem->format = $format;
        }

        return view('articles.archives', compact('articles'));
    }

    function chercheMatricule(Request $request)
    {
        $articles = null;
        $matricule = $request->matricule;
        $cadre = $request->cadre;
        if ($matricule == null && $cadre == "2") {
            return redirect()->route('articles.index');
        }
        if ($cadre != null) {
            $articles = Personnelle::where('cadre', $cadre)
                ->where('etat', true)
                ->get();
        }
        if ($matricule != null) {
            $articles = Personnelle::whereRaw('LOWER(matricule) LIKE ?', ['%' . strtolower($_REQUEST['matricule']) . '%'])
                ->where('etat', true)
                ->get();
        }

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $personnelle = new Personnelle;
        $personnelle->nom = $request->nom;
        $personnelle->prenom = $request->prenom;
        $personnelle->matricule = $request->matricule;
        $personnelle->societe = $request->societe;
        $personnelle->cadre = $request->cadre;

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Vous pouvez ajuster les règles de validation selon vos besoins.
        ]);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = $request->matricule . '.' . $image->getClientOriginalExtension();
            //$image->storeAs('images', $imageName); // Stocke l'image dans le dossier 'storage/app/images'
            $customPath = public_path('images/');
            $image->move($customPath, $imageName);
        }

        // if ($request->hasFile('photo')) {
        //     $image = $request->file('photo');
        //     $imageData = file_get_contents($image->getRealPath());
        //     $base64 = base64_encode($imageData);
        //     $imageName = $image->getClientOriginalName();
        //     $personnelle->photo = $base64;
        //     $personnelle->photoname = $imageName;
        // }

        $personnelle->save();

        return redirect()->route('articles.index');
    }

    public function show($categorie, $article)
    {
        $article = Article::find($article);
        $contenu = $article->contenu;
        $contenu_decode = html_entity_decode($contenu);
        $data = [
            'article' => $article,
            'contenu' => $contenu_decode
        ];
        return view('articles.show', $data);
    }

    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request)
    {
        $article = Choix::find($request->id);
        $article->dejeuner = $request->dejeuner;
        $article->diner = $request->dinner;
        $article->collation = $request->collation;
        $article->yaourtj = $request->yaourtj;
        $article->yaourtn = $request->yaourtn;
        $article->cafe = $request->cafe;
        $article->the = $request->the;
        $article->message = $request->message;
        $article->date = $request->date;
        $article->matricule = $request->matricule;

        $article->save();

        return redirect()->route('articles.index');
    }

    public function destroy(Request $request)
    {
        $article = Personnelle::where('id', $request->idEtat)->first();
        $article->etat = false;
        $article->save();
        return redirect()->route('articles.index');
    }

    function restaurer(Request $request)
    {
        $article = Personnelle::where('id', $request->idEtat)->first();
        $article->etat = true;
        $article->save();
        return redirect()->route('articles.index');
    }

    public function choixMenues(Request $request)
    {
        $dateA = $request->dateA;

        $dateA = date_create($dateA);
        // $dateA = date_format($dateA, "Y/m/d");
        $nombreJour = $request->nombreJour;
        $matricule = $request->matricule;
        $cadre = $request->cadre;

        for ($i = 0; $i <= $nombreJour; $i++) {
            $choix = new Choix();
            $choix->dejeuner = intval($_REQUEST["dejeuner" . $i]);
            $choix->matricule = $matricule;
            $choix->date = $dateA;
            $choix->message = $_REQUEST["message"];
            if ($cadre == "0") {
                $choix->diner = intval($_REQUEST["dinner" . $i]);
                $choix->the = intval($_REQUEST["the" . $i]);
                $choix->cafe = intval($_REQUEST["cafe" . $i]);
                $choix->collation = intval($_REQUEST["collation" . $i]);
                $choix->yaourtj = intval($_REQUEST["yaourtj" . $i]);
                $choix->yaourtn = intval($_REQUEST["yaourtn" . $i]);
            }
            $choix->save();
            date_modify($dateA, "+1 days");
        }

        return redirect()->route('articles.index');

    }

    function afficheDate(Request $request)
    {
        $dateA = $request->dateA;
        $dateB = $request->dateB;
        $choix = null;

        if ($dateA == "" && $dateB == "") {
            $choix = Choix::where('matricule', $_POST['matricule'])
                ->get();
        } else {
            $choix = Choix::whereBetween('date', [$dateA, $dateB])
                ->whereBetween('date', [$dateA, $dateB])
                ->where('matricule', $_POST['matricule'])
                ->get();
        }
        $persone = Personnelle::find($_POST['matricule']);
        $data = [
            'choix' => $choix,
            'emp' => $persone
        ];
        return view('articles.afficheChoix', $data);
    }

    function choisirMenues(Request $request)
    {
        // $request->matricule;
        $emp = Personnelle::find($request->matricule);
        return view('articles.choisirMenues', compact('emp'));
    }

    function history()
    {
        $history = History::all()->sortBy('date');
        foreach ($history as $elem) {
            $perso = Personnelle::find($elem->matricule);
            $elem->nom = $perso->nom;
            $elem->prenom = $perso->prenom;
            $elem->matricule = $perso->matricule;
            $elem->societe = $perso->societe;
            $elem->cadre = $perso->cadre;
        }
        $d = Carbon::now();

        $data = [
            'history' => $history,
            'dateD' => "",
            'dateF' => "",
            'cadre' => "2"
        ];

        return view('articles.history', $data);
    }

    function cantine()
    {
        return view("articles.cantine");
    }

    function storeCantine(Request $request)
    {
        $histo = History::where('matricule', $request->matricule)
            ->where('date', $request->dateForm)
            ->first();

        if ($histo != null) {
            $selectionner = $request->pourj;
            $histo->$selectionner = true;
            $histo->save();
        } else {
            $history = new History();
            $selectionner = $request->pourj;
            $history->$selectionner = true;
            $history->date = $request->dateForm;
            $history->matricule = $request->matricule;
            $history->message = $request->message;
            $history->cadre = $request->cantine;
            $history->save();
        }

        return redirect()->route("articles.cantine");
    }

    function cantineRecherche(Request $request)
    {
        $matricule = $request->matricule;
        $perso = Personnelle::whereRaw('LOWER(matricule) LIKE ?', ['%' . strtolower($matricule) . '%'])->get();

        $choix = Choix::whereRaw('LOWER(matricule) LIKE ?', ['%' . strtolower($matricule) . '%'])
            ->where('date', $request->date)->get();

        $history = History::whereRaw('LOWER(matricule) LIKE ?', ['%' . strtolower($matricule) . '%'])
            ->where('date', $request->date)->get();

        $retour = [$perso, $choix, $history];

        return response()->json($retour);
    }

    // function historyDate(Request $request)
    // {
    //     $dateA = $request->filterDate;
    //     $dateB = $request->dateB;
    //     $matricule = $request->matricule;
    //     $cadre = $request->cadre;
    //     $history = null;
    //     if ($dateA == "" && $matricule == "" && $dateB == "") {
    //         return redirect()->route('articles.history');
    //     } elseif ($dateA != "" && $matricule != "" && $dateB == "") {
    //         $history = History::whereDate('date', '>=', $dateA)
    //             ->whereRaw('LOWER(matricule) LIKE ?', ['%' . strtolower($matricule) . '%'])
    //             ->orderBy('date', 'asc')
    //             ->get();
    //     } elseif ($dateA != "" && $matricule == "" && $dateB == "") {
    //         if ($dateA != "" && $cadre != "tous") {
    //             $history = History::where('date', '>=', $dateA)
    //                 ->where('cadre', $cadre)
    //                 ->orderBy('date', 'asc')
    //                 ->get();
    //         } else {
    //             $history = History::where('date', $dateA)
    //                 ->orderBy('date', 'asc')
    //                 ->get();
    //         }
    //     } elseif ($dateA == "" && $matricule != "") {
    //         $history = History::whereRaw('LOWER(matricule) LIKE ?', ['%' . strtolower($matricule) . '%'])
    //             ->orderBy('date', 'asc')
    //             ->get();
    //     } elseif ($dateA != "" && $matricule != "" && $dateB != "") {
    //         $history = History::whereRaw('LOWER(matricule) LIKE ?', ['%' . strtolower($matricule) . '%'])
    //             ->whereBetween('date', [$dateA, $dateB])
    //             ->whereBetween('date', [$dateA, $dateB])
    //             ->orderBy('date', 'asc')
    //             ->get();
    //     } elseif ($dateA != "" && $dateB != "" && $cadre != "tous") {
    //         $history = History::where('cadre', $cadre)
    //             ->whereBetween('date', [$dateA, $dateB])
    //             ->whereBetween('date', [$dateA, $dateB])
    //             ->orderBy('date', 'asc')
    //             ->get();
    //     } elseif ($dateA != "" && $dateB != "") {
    //         $history = History::whereBetween('date', [$dateA, $dateB])
    //             ->whereBetween('date', [$dateA, $dateB])
    //             ->orderBy('date', 'asc')
    //             ->get();
    //     }


    //     foreach ($history as $elem) {
    //         $perso = Personnelle::find($elem->matricule);
    //         $elem->nom = $perso->nom;
    //         $elem->prenom = $perso->prenom;
    //         $elem->matricule = $perso->matricule;
    //         $elem->societe = $perso->societe;
    //         $elem->cadre = $perso->cadre;
    //     }
    //     $d = Carbon::parse($request->filterDate);
    //     $c = Carbon::parse($request->dateB);
    //     $m = $matricule;
    //     if ($dateB == "") {
    //         $c = Carbon::parse($dateA);
    //     }
    //     if ($matricule == "") {
    //         $m = null;
    //     }

    //     $data = [
    //         'history' => $history,
    //         'dateD' => $d,
    //         'dateF' => $c,
    //         'matricule' => $m
    //     ];
    //     return view('articles.history', $data);
    // }

    function historyDate2(Request $request)
    {
        $dateA = $request->dateA;
        $dateB = $request->dateB;
        $cadre = intval(trim($request->cadre));



        $history = History::query();

        if ($dateA && $dateB) {
            $history->whereBetween('date', [$dateA, $dateB]);
        } elseif ($dateA) {
            $history->where('date', '>=', $dateA);
        }
        if ($cadre != '2') {
            $history->where('cadre', $cadre);
        }

        $history = $history->get();


        foreach ($history as $elem) {
            $perso = Personnelle::find($elem->matricule);
            $elem->nom = $perso->nom;
            $elem->prenom = $perso->prenom;
            $elem->matricule = $perso->matricule;
            $elem->societe = $perso->societe;
            $elem->cadre = $perso->cadre;
        }
        $d = Carbon::parse($request->dateA);
        $c = Carbon::parse($request->dateB);

        if ($request->dateB == "") {
            $c = "";
        }

        $data = [
            'history' => $history,
            'dateD' => $d,
            'dateF' => $c,
            'cadre' => $cadre
        ];
        return view('articles.history', $data);
    }

    function afficheChoix()
    {
        $choix = Choix::where('matricule', $_POST['matricule'])->get();
        $persone = Personnelle::find($_POST['matricule']);
        $data = [
            'choix' => $choix,
            'emp' => $persone
        ];
        return view('articles.afficheChoix', $data);
    }

    function effacheChoix(Request $request)
    {
        $choix = Choix::where('id', $request->id)->first();
        $choix->delete();
        $choix = Choix::where('matricule', $_GET['matricule'])->get();
        $persone = Personnelle::find($_GET['matricule']);
        $data = [
            'choix' => $choix,
            'emp' => $persone
        ];
        return view('articles.afficheChoix', $data);
    }


}