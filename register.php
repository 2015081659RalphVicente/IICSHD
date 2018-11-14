<?php
require './include/controller.php';
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>IICS Help Desk - Register</title>
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">

    </head>

    <body>

        <div class="container-fluid header">
            &nbsp;
        </div>
        <div class="container-fluid headerline">
            &nbsp;
        </div>


        <br>
        <!-- form start -->
        <div class="container">
            <br>
            <div class="row">
                <div class="col-md-5 left">
                    <div align="center"><img src="img/logo2.png" alt=""/><br/><br/></div>
                </div>

                <div class="col-md-7 right">
                    <div class="tab">
                        <button class="tablinks active" onclick="openTab(event, 'Student')" id="defaultOpen">Student</button>
                        <button class="tablinks active" onclick="openTab(event, 'Faculty')" id="defaultOpen">Faculty</button>
                    </div>

                    <div class="tabcontent" id="Student">
                        <p style="padding-top: 1px;"></p>
                        <h3>Register as Student</h3><hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Student Number *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="First Name *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Middle Initial *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Last Name *" value="" />
                                </div>
                                <div class="form-group">
                                    <select class="form-control">
                                        <option class="hidden"  selected disabled>Year and Section: *</option>
                                        <option disabled>──────── 1st Year ────────</option>
                                        <option>1ITA</option>
                                        <option>1ITB</option>
                                        <option>1ITC</option>
                                        <option>1ITD</option>
                                        <option>1ITE</option>
                                        <option>1ITF</option>
                                        <option>1ITG</option>
                                        <option disabled>──────── 2nd Year ────────</option>
                                        <option>2ITA</option>
                                        <option>2ITB</option>
                                        <option disabled>──────── 3rd Year ────────</option>
                                        <option>3ITA</option>
                                        <option>3ITB</option>
                                        <option disabled>──────── 4th Year ────────</option>
                                        <option>4ITA</option>
                                        <option>4ITB</option>
                                        <option>4ITC</option>
                                        <option>4ITD</option>
                                        <option>4ITE</option>
                                        <option>4ITF</option>
                                        <option>4ITG</option>
                                        <option>4ITH</option>
                                        <option>4ITI</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="E-mail (ust.edu.ph) *" value="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Password *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control"  placeholder="Confirm Password *" value="" />
                                </div>
                                <div class="form-group">
                                    <select class="form-control">
                                        <option class="hidden"  selected disabled>Security Question: *</option>
                                        <option>What is your mother's maiden name?</option>
                                        <option>What was your favorite childhood movie?</option>
                                        <option>What is the name of your first pet?</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Answer *" value="" />
                                </div>
                                <input type="submit" class="btnRegister"  value="Register"/><br><br>
                                <div align="right" style="font-size: 14px;"><a href="index.php">Already have an account? Log-In</a></div>
                            </div>
                        </div>
                        <br>
                    </div>

                    <div class="tabcontent" id="Faculty">
                        <p style="padding-top: 1px;"></p>
                        <h3>Register as Faculty</h3><hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Employee Number *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="First Name *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Middle Initial *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Last Name *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="E-mail (ust.edu.ph) *" value="" />
                                </div>  
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Password *" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control"  placeholder="Confirm Password *" value="" />
                                </div>
                                <div class="form-group">
                                    <select class="form-control">
                                        <option class="hidden"  selected disabled>Security Question: *</option>
                                        <option>What is your mother's maiden name?</option>
                                        <option>What was your favorite childhood movie?</option>
                                        <option>What is the name of your first pet?</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Answer *" value="" />
                                </div>
                                <input type="submit" class="btnRegister" value="Register"/><br><br>
                                <div align="right" style="font-size: 14px;"><a href="index.php">Already have an account? Log-In</a></div>
                            </div>
                        </div>
                        <br>
                    </div>

                </div>
            </div>

        </div>
        <!-- form end -->
        <br>

        <div class="container-fluid headerline">
            &nbsp;
        </div>
        <div class="container-fluid footer">
            &nbsp;
        </div>

    </body>

</html>

<script>
    function openTab(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>
<script>
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>

