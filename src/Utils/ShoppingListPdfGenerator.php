<?php

namespace App\Utils;


use App\Entity\Planning;
use App\Repository\PlanningRepository;
use Fpdf\Fpdf;

class ShoppingListPdfGenerator 
{

    /**
     * @return PDF
     * List of ingredients with quantity and unit
     */
    public function generator($list, $rootPath)
    {
        
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true);
        //Logo
        $pdf->Image($rootPath . '/public/img/chef.png', 10,10,10,10, 'png');
        //Brand
        $pdf->SetFont('Arial','B',20);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(12);
        $pdf->SetFillColor(129,241,178);
        $pdf->Cell(47,10,'Food Planner',0,2,'L', true);
        $pdf->Ln(15);
        //Banière
        $pdf->SetFillColor(52,56,144);
        $pdf->Rect(0,22,226,4,'F');
        //Set font color for shopping list
        $pdf->SetTextColor(65,65,65);
        $pdf->SetFontSize(15);
        $pdf->Cell(40,10, 'Liste de course :', 0, 2,'L');
        $pdf->Ln(10);

        // Shopping list
        $pdf->SetFont('Arial','',12);

        foreach( $list as $name => $quantity) {

            $pdf->Cell(10,10);
            
            $pdf->Image($rootPath .'/public/img/checkbox.png', $pdf->GetX()-4, $pdf->GetY()+3, 3, 3);
            $ingredientName = utf8_decode($name);
            $ingredientQuantity = utf8_decode($quantity['unit']);
            $pdf->Cell(90,9, $ingredientName. ' ' . $quantity['quantity'] . ' ' . $ingredientQuantity, 0, 0,'L');
            
            if( $pdf->GetX() > $pdf->GetPageWidth() - 90 ) {
                $pdf->Ln();
            }
        }

        return $pdf;
    }
}