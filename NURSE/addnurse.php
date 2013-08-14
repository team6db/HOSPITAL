<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>ADD NURSE</title>
        <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.2.custom.css" />
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {

                $("#HIRE_DATE").datepicker({dateFormat: 'dd-mm-yy'});
            });

        </script>
    </head>
    <body>
        <h1>ADD NURSE</h1>
        <?php
        include 'connectdb.php';



        $total = 0;
        if (isset($_POST['back']))
            $back = $_POST['back'];
        else
            $back = $_SERVER['HTTP_REFERER'];

        if (isset($_POST['ID']) && isset($_POST['FNAME']) && isset($_POST['LNAME']) && isset($_POST['SALARY']) ) {
            //  echo $_POST['comm'];

            if ($_POST['ID'] != '') {
                $strSQL = "SELECT ID,FNAME,LNAME,SALARY,HIRE_DATE FROM NURSES WHERE ID = " . ($_POST['ID']) . " ORDER BY ID ";
                $objParse = oci_parse($objConnect, $strSQL);
                $objExecute = oci_execute($objParse, OCI_DEFAULT);
                $total = oci_fetch_all($objParse, $Result);
                //  echo $total;
            }
            //  Dont Blank it ~!!!! below
            if ($total == 0 && ctype_digit($_POST['ID']) && ctype_alnum($_POST['FNAME'])&& ctype_alnum($_POST['LNAME']) && ctype_digit($_POST['SALARY']) && ($_POST['HIRE_DATE'] != '') ) {

				$fname = strtoupper($_POST["FNAME"]);
				$lname = strtoupper($_POST["LNAME"]);
                $strSQL = "INSERT INTO NURSES ";
                $strSQL .="(ID,FNAME,LNAME,SALARY,HIRE_DATE) ";
                $strSQL .="VALUES ";
                $strSQL .="('" . $_POST["ID"] . "','" . $fname . "','" . $lname . "','" .$_POST['SALARY'] .  "',to_date('" . $_POST['HIRE_DATE'] . "','dd/mm/yyyy')" . ")";
                //echo $strSQL;
				$objParse = oci_parse($objConnect, $strSQL);
                $objExecute = oci_execute($objParse, OCI_DEFAULT);
                if ($objExecute) {
                    oci_commit($objConnect); //*** Commit Transaction ***//

                    echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $back . "\">";
                }

                exit;
            }
        }
        $a = [0, 0, 0, 0, 0];
        if (isset($_POST['ID']) && (!ctype_digit($_POST['ID']) || $total > 0)) {
            $a[0] = 1;
        }
        if (isset($_POST['FNAME']) && !ctype_alnum($_POST['FNAME'])) {
            $a[1] = 1;
        }
		if (isset($_POST['LNAME']) && !ctype_alnum($_POST['LNAME'])) {
            $a[2] = 1;
        }
		if (isset($_POST['SALARY']) && !ctype_digit($_POST['SALARY'])) {
            $a[3] = 1;
		}
        if (isset($_POST['HIRE_DATE']) && $_POST['HIRE_DATE'] == '') {
            $a[4] = 1;
        }
        
        ?>
        <form id="frm1" action="addnurse.php"  method="post">
            <table>
                <tr>
                    <td>ID :</td>
                    <td><input id="ID" name="ID" type="text" value="<?php if (isset($_POST['ID'])) echo $_POST['ID'] ?>"<?php if ($a[0] == 1) echo "style='background-color: #FFFF66;'" ?>/></td>
                </tr>
                <tr>
                    <td>FIRST NAME :</td>
                    <td><input id="FNAME" name="FNAME" type="text" value="<?php if (isset($_POST['FNAME'])) echo $_POST['FNAME'] ?>"<?php if ($a[1] == 1) echo "style='background-color: #FFFF66;'" ?> /></td>
                </tr>
				<tr>
                    <td>LAST  NAME :</td>
                    <td><input id="LNAME" name="LNAME" type="text" value="<?php if (isset($_POST['LNAME'])) echo $_POST['LNAME'] ?>"<?php if ($a[2] == 1) echo "style='background-color: #FFFF66;'" ?> /></td>
                </tr>
                <tr>
                    <td>SALARY</td>
                    <td><input id="SALARY" name="SALARY" type="text" value="<?php if (isset($_POST['SALARY'])) echo $_POST['SALARY'] ?>" <?php if ($a[3] == 1) echo "style='background-color: #FFFF66;'" ?> /></td>
                </tr>
				<tr>
                    <td>HIREDATE</td>
                    <td><input  name="HIRE_DATE" id="HIRE_DATE"  type="text"  value="<?php if (isset($_POST['HIRE_DATE'])) echo $_POST['HIRE_DATE'] ?>" maxlength="40" <?php if (isset($_POST['HIRE_DATE']) && $_POST['HIRE_DATE'] == '') echo "style='background-color: #FFFF66;'" ?> /> </td>
                </tr>
                <tr>
                    <td><input id="back" name="back" type="hidden" value="<?php echo $back; ?>" /></td>
                </tr>
                <tr>

                    <td><input id="submit" name="submit" type="submit" value="Add Nurse" /></td>
                </tr>
            </table>

        </form>
        <form id="frm" action="<?php echo $back ?>" method="post">

            <input type="submit" value="back." />
        </form>
    </body>
</html>
