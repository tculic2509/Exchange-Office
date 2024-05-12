<?php

class ConvertValutaPage extends AbstractClass
{
    public $templateName = 'Convert';
    function execute()
    {

        try {
            $kolicina = $_GET['kolicina'];
            $valuta1 = $_GET['valuta1'];
            $valuta2 = $_GET['valuta2'];
        
            if ($kolicina == "" || $kolicina == null || $valuta1 == "" || $valuta1 == null || $valuta2 == "" || $valuta2 == null) {
                header('HTTP/1.1 404 Not found');
                $this->data = "Unesi valute ili količinu!!!";
                http_response_code(404);
                return;
            }
        
            // Usporedba tablice tecaj i history pomoću valutaID i id za valutu1
            $sql1 = "SELECT tecaj FROM history 
                     INNER JOIN tecaj ON history.valutaID = tecaj.id 
                     WHERE tecaj.valuta = '$valuta1'";
            $result1 = AppCore::getDB()->sendquery($sql1);
            $row1 = AppCore::getDB()->fetchArray($result1);
        
            if (!$row1) {
                header('HTTP/1.1 404 Not found');
                $this->data = "Valuta 1 ne postoji";
                http_response_code(404);
                return;
            }
            $rez1 = $row1['tecaj'];
        
            // Usporedba tablice tecaj i history pomoću valutaID i id za valutu2
            $sql2 = "SELECT tecaj FROM history 
                     INNER JOIN tecaj ON history.valutaID = tecaj.id 
                     WHERE tecaj.valuta = '$valuta2'";
            $result2 = AppCore::getDB()->sendquery($sql2);
            $row2 = AppCore::getDB()->fetchArray($result2);
        
            if (!$row2) {
                header('HTTP/1.1 404 Not found');
                $this->data = "Valuta 2 ne postoji";
                http_response_code(404);
                return;
            }
            $rez2 = $row2['tecaj'];
        
            // Konvertiranje
            if ($rez1 > 0) {
                $convert = ($kolicina / $rez1) * $rez2;
                $this->data = "Conversion from " . $valuta1 . ' to ' . $valuta2 . ' is ' . $convert;
            }
           
        } catch (Exception $e) {
            throw new Exception("Greška: " . $e->getMessage());
        } catch (DivisionByZeroError $e) {
            throw new DivisionByZeroError($e->getMessage());
        }
        
    }
}
