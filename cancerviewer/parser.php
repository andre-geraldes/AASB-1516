<?php
    //Include PHPExcel_IOFactory
    include 'Classes/PHPExcel/IOFactory.php';

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // Allow certain file formats
    if($fileType != "xlsx" && $fileType != "xls") {
        $uploadOk = 0;
    }

    //Check if file exists, if so, erases it
    if (file_exists($target_file)) {
        unlink($target_file);
    }

    if($uploadOk){
        //Save file
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        //Open the excel file
        $objPHPExcel = PHPExcel_IOFactory::load($target_file);
        $array = array();
        //$worksheet = $objPHPExcel->setActiveSheetIndexbyName('Sheet1');
        $worksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        //Clustering with python
        //First convert excel to a txt with the matrix
        $matrix = array();
        for ($row = 2; $row <= $highestRow; ++ $row) {
            $line = array();
            for ($col = 1; $col < $highestColumnIndex; ++ $col) {
                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                //Empty cells are 0
                if($val !== '  '){
                    $line[] = $val;
                }
                else {
                    $line[] = 0;
                }
            }
            $matrix[] = $line;
        }
        //Write matrix to file
        if (file_exists('matrix.txt')) {
            unlink('matrix.txt');
        }
        $myfile = fopen("matrix.txt", "w");
        for($i = 0; $i < count($matrix); $i++){
            for($j = 0; $j < count($matrix[$i]); $j++){
                fwrite($myfile, $matrix[$i][$j]."-");
            }
            fwrite($myfile, "\n");
        }
        fclose($myfile);
        //Now call python with the matrix to create the cluster
        //Returns two arrays in same line
        $res = exec('python cluster.py');
        //Break python arrays
        $a = preg_split("/], \[/", $res);
        $a[0] = substr($a[0],2,strlen($a[0]));
        $a[1] = substr($a[1],0,strlen($a[1])-2);
        //Convert to arrays in php
        $rows = preg_split("/,/", $a[0]);
        $columns = preg_split("/,/", $a[1]);
        //Remove spaces in indexes and convert to int
        for($i = 0; $i < count($rows); $i++){
            $rows[$i] = str_replace(' ', '', $rows[$i]);
            $rows[$i] = (int)$rows[$i] + 1; //D3.js first index is 1 so we need to sum 1 here.
        }
        for($i = 0; $i < count($columns); $i++){
            $columns[$i] = str_replace(' ', '', $columns[$i]);
            $columns[$i] = (int)$columns[$i] + 1;
        }

        //Create JSON file
        //Get genes names
        $genes = array();
        for ($col = 0; $col < $highestColumnIndex; ++ $col) {
            $cell = $worksheet->getCellByColumnAndRow($col, 1);
            $val = $cell->getValue();
            $genes[] = $val;
        }
        //Get caseID names
        $cases = array();
        for ($row = 1; $row <= $highestRow; ++ $row) {
            $cell = $worksheet->getCellByColumnAndRow(0, $row);
            $val = $cell->getValue();
            $cases[] = $val;
        }

        //Save json file
        $a = array(); //genes names
        $b = array(); //caseIDs
        $c = array(); //rows for cluster
        $d = array(); //columns for cluster
        for($i = 1; $i < count($genes); $i++){
            $a[] = array('name' => $genes[$i]);
        }
        for($i = 1; $i < count($cases); $i++){
            $b[] = array('name' => $cases[$i]);
        }
        for($i = 0; $i < count($rows); $i++){
            $c[] = array('name' => $rows[$i]);
        }
        for($i = 0; $i < count($columns); $i++){
            $d[] = array('name' => $columns[$i]);
        }
        $all = array('gene' => $a, 'caseID' => $b, 'row' => $c, 'column' => $d);
        //Erase last json file
        if (file_exists('cancer.json')) {
            unlink('cancer.json');
        }
        //Create json file
        $myfile = fopen("cancer.json", "w");
        fwrite($myfile, json_encode($all));
        fclose($myfile);


        //Get values for tsv file
        //Regular expression for each mutation
        $pattern_trunc = "/[A-Z][0-9]+\*/";
        $pattern_missence = "/([A-Z][0-9]+[A-Z]$)|([A-Z][0-9]+[A-Z],)|([A-Z][0-9]+[A-Z];)/";
        $pattern_frameshift = "/fs/";
        $pattern_splice = "/splice/";
        $pattern_deletion = "/([A-Z0-9\_]+((del\;)|(del\,)))|($[A-Z0-9\_]+del)/";
        $pattern_delins = "/delins/";
        $pattern_insertion = "/[0-9]ins/";
        for ($col = 1; $col < $highestColumnIndex; ++ $col) {
            for ($row = 2; $row <= $highestRow; ++ $row) {
                //Get value in each cell
                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $total = "";
                if($val != ''){
                    if(strpos($val, 'MUT') !== false){
                        if (preg_match($pattern_trunc, $val)){
                            $total .= 'A';
                        }
                        if (preg_match($pattern_missence, $val)){
                            $total .= 'B';
                        }
                        if (preg_match($pattern_frameshift, $val)){
                            $total .= 'C';
                        }
                        if (preg_match($pattern_splice, $val)){
                            $total .= 'D';
                        }
                        if (preg_match($pattern_deletion, $val)){
                            $total .= 'E';
                        }
                        if (preg_match($pattern_delins, $val)){
                            $total .= 'F';
                        }
                        if (preg_match($pattern_insertion, $val)){
                            $total .= 'G';
                        }
                    }
                    if(strpos($val, 'AMP') !== false){
                        $total .= "1";
                    }
                    if(strpos($val, 'HOMDEL') !== false){
                        $total .= "2";
                    }
                    if(strpos($val, 'UP') !== false){
                        $total .= "3";
                    }
                    if(strpos($val, 'DOWN') !== false){
                        $total .= "4";
                    }
                    if(strpos($val, 'HYPER') !== false){
                        $total .= "5";
                    }
                    if(strpos($val, 'HYPO') !== false){
                        $total .= "6";
                    }
                    if(strlen($total) == 0){
                        $total = "0";
                    }
                }
                else {
                    $total = "0";
                }
                $array[] = ($row-1)."\t".$col."\t"."$total";
            }
        }
        //Erase last tsv
        if (file_exists('cancer.tsv')) {
            unlink('cancer.tsv');
        }
        //Create tsv file
        $myfile = fopen("cancer.tsv", "w");
        fwrite($myfile, "row_idx\tcol_idx\tvalue"."\n");
        for($i = 0; $i < count($array); $i++){
            fwrite($myfile, $array[$i]."\n");
        }
        fclose($myfile);

        //Check for button
        echo 1;
    }
    else {
        //Problems with file
        echo 0;
    }

?>
