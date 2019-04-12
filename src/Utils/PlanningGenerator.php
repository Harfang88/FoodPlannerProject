<?php

namespace App\Utils;

use DateTime;



class PlanningGenerator
{

    public function generator($plannings)
    {
        // 1er niveau
        $mealPlanning = [];

        //2ème niveau
        $arrayMeal = [];

        //3ème niveau 
        $mealData = [];

        //Set d'un Lundi :
        $date = '2019-03-17';

        //Tableau à envoyé à vide :
        $weekDays = [];

        $localDays = [
            'Mon' => 'Lundi',
            'Tue' => 'Mardi',
            'Wed' => 'Mercredi',
            'Thu' => 'Jeudi',
            'Fri' => 'Vendredi',
            'Sat' => 'Samedi',
            'Sun' => 'Dimanche',
        ];
        
        // Boucle pour setter la base du tableau à vide :
        for ( $i = 0; $i <= 6; $i++) {

            //Niveau 1 jour :
            $date = date('Y-m-d', strtotime($date. ' + 1 days'));

            //Niveau 3 à vide :
            $mealData['recipeTitle'] = '';
            $mealData['planningId'] = '';

            //Niveau 2 :
            $arrayMeal['Déjeuner'] = $mealData;
            $arrayMeal['Diner'] = $mealData;

            $weekDays[$localDays[(new DateTime($date))->format('D')]] = $arrayMeal; 
        }
  
        //On reset les tableaux pour créer notre tableaux de plannings qui sont en BDD
        // et le comparer avec celui que l'on vient de créer.

        //2ème niveau
        $arrayMeal = [];

        //3ème niveau 
        $mealData = [];
        //Jour de départ
        $day = 'Lundi';
        //Boucle données en BDD pour tableau structure identique à weekDays:
        foreach($plannings as $planning) {

            $newDay = $localDays[$planning['mealDay']->format('D')];

            // On reset les tableaux si le jour à changé
            if( $newDay != $day ) {
                $arrayMeal = [];
                $mealData = [];
            }
            
            //2ème niveau :
            $mealData['recipeTitle'] = $planning['title'];
            $mealData['planningId'] = $planning['id'];
            
            $arrayMeal[$planning['mealTime']] = $mealData;
 
            //Assemblage 1er niveau:
            $mealPlanning[$newDay] = $arrayMeal;
            
            $day =$localDays[$planning['mealDay']->format('D')];
        }


        //Récupération des clefs jours :
        $arrayDays = array_keys($weekDays);

        //Récupération des clefs repas :
        $mealNames = array_keys($weekDays[$arrayDays[0]]);

        //Boucle sur le tableau de jours :
        foreach($arrayDays as $arrayDay) {
            //Verifie si l'entrée jour existe dans notre tableau BDD
            if(array_key_exists($arrayDay, $mealPlanning)) {
                //boucle sur les repas
                foreach($mealNames as $mealName) {
                    // verifie si l'entrée repas existe dans notre tableau BDD
                    if(array_key_exists($mealName, $mealPlanning[$arrayDay])) {
                        //Set la valeur (recette + planningId) de notre tableau BDD dans le tableau qui sera envoyé
                       $weekDays[$arrayDay][$mealName] = $mealPlanning[$arrayDay][$mealName];
                    }
                }
            }
        }

        return $weekDays;
    }

}