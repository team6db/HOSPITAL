<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Datatables Sample</title>
        <style type="text/css" title="currentStyle">
            @import "css/demo_page.css";
            @import "css/demo_table_jui.css";
            @import "css/jquery-ui-1.8.4.custom.css";
            @import "css/ColReorder.css";
        </style>
        <script type="text/javascript" language="javascript" src="js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8" src="js/ColReorder.js"></script>
        <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
                $('#employeestable').dataTable({
                    "sDom": 'R<"H"lfr>t<"F"ip>',
                    "bJQueryUI": true,
                    "sPaginationType": "full_numbers"
                });
            });
            function onDelete()
            {
                if (confirm('Do you want to delete ?') == true)
                {
                    document.getElementById('frm').submit();
                    return true;
                }
                else
                {
                    return false;
                }
            }

        </script>
    </head>
    <body>

        <div style="width: 100%;">
            <h1>Nurses</h1> 
            <form name="create" action="addnurse.php" method="post">
                <input id="submit" name="newnurse" type="submit" value="addNurse"/>
            </form>
            <br/>

            <form id="frm" style="float:left;width: 100%;" name="delete" action="datatable.php" method="post" >
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="employeestable" width="100%">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>nurseID</th>
                            <th>First_Name</th>
                            <th>Last_name</th>
                            <th>Salary</th>
							<th>Hire_Date</th>
							<th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                           include 'connectdb.php';




                        $strSQL = "SELECT ID,FNAME,LNAME,SALARY,HIRE_DATE FROM NURSES ORDER BY ID ";

                        $objParse = oci_parse($objConnect, $strSQL);
                        $objExecute = oci_execute($objParse, OCI_DEFAULT);

                        while ($row = oci_fetch_array($objParse, OCI_BOTH)) {
                            //   if ($row['typeroom'] == 'fan-room') {
                            $td = ' <tr class="gradeA">';
                            //     } else {
                            //         $td = ' <tr class="gradeU">';
                            //     }
                            $td = $td . ' <td class="center">' . '<input name="checkbox[]" type="checkbox" id="checkbox[]" value="' . $row['ID'] . '">' . '</td>';
                            $td = $td . ' <td class="center">' . $row['ID'] . '</td>';
                            $td = $td . ' <td class="center">' . $row['FNAME'] . '</td>';
                            $td = $td . ' <td class="center">' . $row['LNAME'] . '</td>';
							$td = $td . ' <td class="center">' . $row['SALARY'] . '</td>';
							$td = $td . ' <td class="center">' . $row['HIRE_DATE'] . '</td>';
							//$td = $td . ' <td class="center">' . $row['AMBU_NO'] . '</td>';
                            $td = $td . ' <td class="center"><a href="editnurse.php?ID='. $row['ID'] .'">Edit </a></td>';
                            $td = $td . '</tr>';
                            echo $td;
                        }
                        ?>


                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>nurseID</th>
                            <th>First_Name</th>
                            <th>Last_name</th>
                            <th>Salary</th>
							<th>Hire_Date</th>
							<th>Edit</th>
                        </tr>
                    </tfoot>
                </table>

                <input id="delete" name="delete"  type="submit" value="deleteNurse" onClick="onDelete();
                return false;"/>
            </form><br/>  <br/> 
            <form action="index.php">
                <input type="submit" value="back." />
            </form>
            <?php
            //   echo 'i';

            if (isset($_POST["checkbox"])) {
                // echo 'Here';

               for ($i = 0; $i < count($_POST["checkbox"]); $i++) {
                    if ($_POST["checkbox"][$i] != "") {
                        $strSQL = "DELETE FROM NURSES ";
                        $strSQL .="WHERE ID = " . $_POST["checkbox"][$i] . "";
                        //echo $strSQL ;
                        $objParse = oci_parse($objConnect, $strSQL);
                        $objExecute = oci_execute($objParse, OCI_DEFAULT);
                        if ($objExecute) {
                            oci_commit($objConnect); //*** Commit Transaction ***//
                        }
                    }
                }

                echo "<meta http-equiv=\"refresh\" content=\"0;URL=datatable.php\">";
            }

         //   exit;
            ?>

        </div>

    </body>
</html>
