<?php session_start();
include "konekcija.php";
if(isset($_POST['btnUpisiSliku'])) {
        $alt = $_POST['alt'];
        $model=$_POST['ddlModelSlika'];
        $slika=$_FILES['fSlika'];
        $ime=$slika['name'];
        $tip=$slika['type'];
        $velicina=$slika['size'];
        $tmpPutanja=$slika['tmp_name'];

//        var_dump($slika);
        $errors=[];

        if($model=="0"){
            array_push($errors,"Polje mora biti Izabrano");
        }
        if(empty($alt)){
            array_push($errors,"Polje mora biti popunjeno");
        }

        $formati=array("image/jpg", "image/jpeg", "image/png");

        if(!in_array($tip, $formati)){
            array_push($errors, "Tip fajla mora biti (jpg/jpeg/png)");
        }
        if(!$velicina>3000000){
            array_push($errors, "Fajl mora biti manje od 3MB");
        }

        if(count($errors)==0) {
            $naziv = time() . $ime;
            $novaPutanja = "images/" . $naziv;


            if (move_uploaded_file($tmpPutanja, $novaPutanja)) {
                $upit = "INSERT INTO slika values ('', :putanja, :alt, :model)";
                $rez = $konekcija->prepare($upit);
                $rez->bindParam(':putanja', $novaPutanja);
                $rez->bindParam(':alt', $alt);
                $rez->bindParam(':model', $model);

                try {
                    $rez->execute();
                    if ($rez) {
//                    header("Location: ../dodajKamion.php");
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

            }
        }
}