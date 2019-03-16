<?php

include '../../include/controller.php';

//load_data.php  
$output = '';

if (isset($_POST["profno"])) {
    if ($_POST["profno"] != '') {
        $sql = "SELECT * FROM consulthours WHERE userno = '" . $_POST["profno"] . "'";
    } else {
        $output .= "<h5>Please select a professor.</h5>";
    }
    
    $output.='<b>Consultation Hours: </b>';
    $output.='<div class="row">';
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $consulthours = $row['daytime'];


            $output .= '<div class="col-md-3"><div style="border:1px solid #ccc; padding:20px; margin-bottom:20px;">' . $consulthours . '</div></div>';
        }
    } else {
        $output = '<div class="row"><div class="col-md-3"><div style="border:1px solid #ccc; padding:20px; margin-bottom:20px;">No consultation hours set.</div></div></div>';
    }
    $output .= '                        </div>
                                        <div class="form-group">
                                            <label for="title"><b>Subject of Concern: </b><span class="require">*</span></label>
                                            <input type="text" class="form-control" name="cTitle" required />
                                        </div>

                                        <div class="form-group">
                                            <label for="description"><b>Description: </b></label>
                                            <textarea rows="2" class="form-control" name="cDesc" required ></textarea>
                                        </div>';
    echo $output;
}
?> 
