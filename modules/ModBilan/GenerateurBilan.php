<?php  
    if (!defined('CONSTANTE'))
        die("Accès interdit");


    class GenerateurBilan {

        public function __construct() {

        }

        public function getHtml($resultats) {

            $html = "<div style='margin-top:30px' class='content'> <h4 class='h4borderbottom'> Votre bilan </h4>";

            if ($resultats[0]==1) {
                $html .= "<ul>";
                foreach ($resultats[1] as $errorMessage) {
                    $html .= "<li> $errorMessage </li>";
                }

                $html .= "</ul> </div>";
                return $html;
            }

            foreach ($resultats[1] as $nutriment => $proportion) {
                
                $proportion = round($proportion*100.0);
                $bgcolor="#00e7ff";
                $msg=$proportion . "%";

                if ($proportion<80) {
                    $bgcolor="red";
                }
                elseif ($proportion<90) {
                    $bgcolor="orange";
                }
                elseif ($proportion>100) {
                    $proportion=100;
                    $msg="En excès";
                    $bgcolor="#ff9cf4";
                }

                $html .= 
                    "<div class='rectangleBilan'>
                        <div class='nomNutri'> $nutriment </div>

                        <div class='proportionNutri'>
                            <div style='width:$proportion%; background-color:$bgcolor;' class='progressBarNutri'>
                                $msg
                            </div>
                        </div> 
                    </div>";
            }

            $html .= "<div style='margin-top:40px'> <a href='index.php?module=ModBilan&action=realiserBilan'> Refaire un bilan </a> </div>";

            $html .= "</div>";

            return $html;
        }

        public function bilanToPdf($resultats) {



            $date = new DateTime(date("r"));
            $dateStr = $date->format("d/m/Y");
            $dateHr = $date->format("H");
            $dateMn = $date->format("i");
            
            $pdf = new FPDF();

            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 18);

            $pdf->Image('resources/logo.png',8,2);
            
            $pdf->Ln(20);
        
            $pdf->setFillColor(230,230,230);
            
            $pdf->SetX(70);
            
            $pdf->Cell(60,8,'Votre bilan nutrition',0,1,'C',1);
            
            $pdf->Ln(10);

            $pdf->SetFont('Arial', 'B', 15);

            $pdf->Cell(30,8,"Fait le $dateStr a $dateHr" . "h" . "$dateMn (UTC+1)");
            
            $pdf->Ln(10);

            if ($resultats[0]!=0) {
                $pdf->Cell(30,8,"Une erreur est survenue, veuillez reessayer.");
                $pdf->Output("bilan_$dateStr.pdf", "I");
                return;
            }

            $pdf->SetFont('Arial', 'B', 14);

            
            $pdf->SetDrawColor(183); 
            $pdf->SetFillColor(221); 
            $pdf->SetTextColor(0); 
            
            $pdf->SetX(10);
            $pdf->Cell(60,8,'Nutriment',1,0,'C',1);  
            
            $pdf->SetX(70); 
            $pdf->Cell(60,8,'Pourcentage couvert',1,0,'C',1);

            $pdf->SetX(130); 
            $pdf->Cell(60,8,'Commentaire',1,0,'C',1);
          

            $pdf->Ln();

            $posX=10;
            $posY=66;

            $pdf->SetFont('Arial', 'B', 13);

            $comment="";

            foreach ($resultats[1] as $nutriment => $proportion) {

                if ($proportion<0.8) {
                    $comment="Carence grave";
                }
                elseif ($proportion<0.9) {
                    $comment="Insuffisant";
                }
                elseif ($proportion<=1) {
                    $comment="Correct";
                }
                else {
                    $comment="Excès";
                }

                $pdf->SetX(10);
            
                $pdf->MultiCell(60,8,utf8_decode($nutriment),1,'C');
                    
                $pdf->SetY($posY);
                $pdf->SetX(70);
       
                $pdf->MultiCell(60,8,utf8_decode(round($proportion*100) . "%"),1,'C');

                $pdf->SetY($posY);
                $pdf->SetX(130);
       
                $pdf->MultiCell(60,8,utf8_decode($comment),1,'C');

                $posY += 8;
            }   
            
           
        


            $pdf->Output("bilan_$dateStr.pdf", "I");
        }
    }















?>        