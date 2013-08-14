<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>EDIT NURSE</title>
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
        <h1>EDIT NURSE</h1>
        <?php
        $username = "GENOTYPE000";
        $password = "tent090035626";
        $hostname = "//localhost/XE";



        $objConnect = oci_connect($username, $password, $hostname);
        $total = 0;
        if (isset($_POST['back']))
            $back = $_POST['back'];
        else
            $back = $_SERVER['HTTP_REFERER'];

        if (isset($_POST['ID']) && isset($_POST['FNAME']) && isset($_POST['LNAME']) && isset($_POST['SALARY']) ) {
            //  echo $_POST['comm'];
            $total = 0;
            if ($_POST['ID'] != '') {
                $strSQL = "SELECT ID,FNAME,LNAME,SALARY FROM NURSES WHERE ID = " . ($_POST['ID']) . " ORDER BY ID ";
                $objParse = oci_parse($objConnect, $strSQL);
                $objExecute = oci_execute($objParse, OCI_DEFAULT);

                $total = oci_fetch_all($objParse, $Result);
                //  echo $total;
            }
           
            if ($total > 0  && ctype_alnum($_POST['FNAME']) && ctype_alnum($_POST['LNAME']) && ctype_digit($_POST['SALARY']) && ($_POST['HIRE_DATE'] != '') ) {

                $fname = strtoupper($_POST["FNAME"]);
				$lname = strtoupper($_POST["LNAME"]);
                $strSQL = "UPDATE NURSES SET ";
                $strSQL .="ID='" . $_POST["ID"] . "',FNAME='" . $fname . "',LNAME='" . $lname . "',HIRE_DATE=to_date('" . $_POST['HIRE_DATE'] . "','dd/mm/yyyy'),SALARY='" . $_POST["SALARY"] . "'";
                $strSQL .= "WHERE ID='" . $_POST['ID'] . "'";
                
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
        if (isset($_POST['ID']) && (!ctype_digit($_POST['ID']) || $total == 0)) {
            $a[0] = 1;
            $id = $_POST['ID'];
        }
        if (isset($_POST['FNAME']) && !ctype_alnum($_POST['FNAME'])) {
            $a[1] = 1;
            $fname = $_POST['FNAME'];
        }
		if (isset($_POST['LNAME']) && !ctype_alnum($_POST['LNAME'])) {
            $a[2] = 1;
            $lname = $_POST['LNAME'];
        }
        if (isset($_POST['SALARY']) && !ctype_digit($_POST['SALARY'])) {
            $a[3] = 1;
            $salary = $_POST['SALARY'];
        }
		if (isset($_POST['HIRE_DATE']) && $_POST['HIRE_DATE'] == '') {
            $a[4] = 1;
            $hiredate = $_POST['HIRE_DATE'];
        }
        if (!isset($_POST['ID']) || !isset($_POST['FNAME']) || !isset($_POST['LNAME'])  || !isset($_POST['SALARY']) || !isset($_POST['HIRE_DATE'])) {
            $strSQL = "SELECT ID,FNAME,LNAME,SALARY,TO_CHAR(HIRE_DATE,'dd-mm-yyyy') HIRE_DATE FROM NURSES WHERE ID = '" . $_GET['ID'] . "' ORDER BY ID ";
            $objParse = oci_parse($objConnect, $strSQL);
            $objExecute = oci_execute($objParse, OCI_DEFAULT);
            $id = '';
            $fname = '';
            $lname = '';
            $salary = '';
            $hiredate = '';
            while ($row = oci_fetch_array($objParse, OCI_BOTH)) {
                $id = $row['ID'];
                $fname = $row['FNAME'];
                $lname = $row['LNAME'];
                $salary = $row['SALARY'];
                $hiredate = $row['HIRE_DATE'];
            }
        }
        ?>
        <form id="frm1" action="editnurse.php"  method="post">
            <table>
                <tr>
                    <td>ID :</td>
                    <td><input readonly="readonly" id="ID" name="ID" type="text" value="<?php
                        if (isset($_POST['ID']))
                            echo $_POST['ID'];
                        else
                            echo $id;
                        ?> "/></td>

                </tr>
                <tr>
                    <td>FIRST NAME :</td>
                    <td><input id="FNAME" name="FNAME" type="text" value="<?php
                        if (isset($_POST['FNAME']))
                            echo $_POST['FNAME'];
                        else
                            echo $fname;
                        ?>"<?php if ($a[1] == 1) echo "style='background-color: #FFFF66;'" ?> /></td>
                </tr>
                <tr>
                    <td>LAST NAME :</td>
                    <td><input id="LNAME" name="LNAME" type="text" value="<?php
                        if (isset($_POST['LNAME']))
                            echo $_POST['LNAME'];
                        else
                            echo $lname;
                        ?>"<?php if ($a[2] == 1) echo "style='background-color: #FFFF66;'" ?> /></td>
                </tr>
				<tr>
                    <td>SAL</td>
                    <td><input id="SALARY" name="SALARY" type="text" value="<?php
                        if (isset($_POST['SALARY']))
                            echo $_POST['SALARY'];
                        else
                            echo $salary;
                        ?>" <?php if ($a[3] == 1) echo "style='background-color: #FFFF66;'" ?> /></td>
                </tr>
                <tr>
                    <td>HIREDATE</td>
                    <td><input  name="HIRE_DATE" id="HIRE_DATE" readonly="readonly" type="text"  value="<?php
                        if ($a[4] == 1)
                            echo $_POST['HIRE_DATE'];
                        else
                             if (isset($_POST['HIRE_DATE'])){
                                  echo $_POST['HIRE_DATE'];
                             }else
                            echo $hiredate;
                        ?>" maxlength="40" <?php if (isset($_POST['HIRE_DATE']) && $_POST['HIRE_DATE'] == '') echo "style='background-color: #FFFF66;'" ?> /> </td>
                </tr>
                <tr>

                    <td><input id="back" name="back" type="hidden" value="<?php echo $back; ?>" /></td>
                </tr>
                <tr>

                    <td><input id="submit" name="submit" type="submit" value="EDIT NURSE" /></td>
                </tr>
            </table>

        </form>
        <form id="frm" action="<?php echo $back ?>" methid="post">

            <input type="submit" value="back." />
        </form>
    </body>
</html>
