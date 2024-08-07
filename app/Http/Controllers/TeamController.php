<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([  // Lorsque l'utilisateur soumet un formulaire sur la page de création (générée par la méthode create), les données du formulaire sont envoyées au serveur via une requête POST.Laravel capture cette requête et la rend disponible via l'objet Request
            'name'=>'required',
            'description'=>'required',
        ]);
        //Récupération de toutes les données de la requête :
        $input = $request->all(); //les attributs elli 3amarnehom fel create lkol bch n7otouhom d variable input
         //Création du produit :
         Team::create($input); //create est une méthode fournie par Laravel pour insérer une nouvelle entrée dans la base de données en utilisant le modèle Eloquent==> c pas la méthode create qu'on a créé au dessus
         //Redirection avec message de succès :
         return redirect()-> route('dashboard')->with('success','new team added successfully');
 
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }
}
