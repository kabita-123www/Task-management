<!DOCTYPE html>
<html>

<head>
    <!-- Title Page-->
    <title>Add Employee | Task Management System</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i"
        rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">
</head>

<body>
    <header>
        <nav>
            <h1>T.M.S</h1>
            <ul id="navli">
                <li><a class="homeblack" href="aloginwel.php">HOME</a></li>
                <li><a class="homered" href="addemp.php">Add Employee</a></li>
                <li><a class="homeblack" href="viewemp.php">View Employee</a></li>
                <li><a class="homeblack" href="assign.php">Assign Task</a></li>
                <li><a class="homeblack" href="assignproject.php">Task Status</a></li>
                <li><a class="homeblack" href="empleave.php">Employee Leave</a></li>
                <li><a class="homeblack" href="alogin.html">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="divider"></div>

    <div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
        <div class="wrapper wrapper--w680">
            <div class="card card-1">
                <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Registration Info</h2>
                    <form id="registrationForm" action="process/addempprocess.php" method="POST" enctype="multipart/form-data">
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-1" type="text" placeholder="First Name" name="firstName" required="required">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-1" type="text" placeholder="Last Name" name="lastName" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                            <input class="input--style-1" type="email" placeholder="Email" name="email" required="required">
                        </div>

                        <p>D.O.B</p>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-1 date" type="date" placeholder="BIRTHDATE" name="birthday" required="required">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="gender" required>
                                            <option disabled="disabled" selected="selected">GENDER</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                            <input class="input--style-1" type="text" placeholder="Contact Number" name="contact" required="required">
                        </div>

                        <div class="input-group">
                            <input class="input--style-1" type="text" placeholder="Address" name="address" required="required">
                        </div>

                        <div class="input-group">
                            <input class="input--style-1" type="text" placeholder="Department" name="dept" required="required">
                        </div>

                        <div class="input-group">
                            <input class="input--style-1" type="text" placeholder="Degree" name="degree" required="required">
                        </div>

                      
                        <div class="input-group">
                            <input class="input--style-1" type="number" placeholder="Salary" name="salary">
                        </div>

                        <div class="input-group">
                            <input class="input--style-1" type="file" placeholder="file" name="file">
                        </div>

                        <div class="p-t-20">
                            <button class="btn btn--radius btn--green" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form validation script -->
    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            var form = this;

            // Validate email
            var email = form.email.value;
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                event.preventDefault();
                return;
            }

            // Validate contact number (must be 10 digits and start with 98)
            var contact = form.contact.value;
            var contactPattern = /^98\d{8}$/;
            if (!contactPattern.test(contact)) {
                alert('Please enter a valid 10-digit contact number starting with 98.');
                event.preventDefault();
                return;
            }

            // Validate salary (must be a positive number)
            var salary = form.salary.value;
            if (salary && (isNaN(salary) || salary <= 0)) {
                alert('Please enter a valid positive number for salary.');
                event.preventDefault();
                return;
            }

            // Validate file upload (check file size and type)
            var fileInput = form.file;
            var file = fileInput.files[0];
            if (file) {
                var maxFileSize = 2 * 1024 * 1024; // 2MB
                var allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                if (file.size > maxFileSize) {
                    alert('File size must be less than 2MB.');
                    event.preventDefault();
                    return;
                }
                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type. Allowed types: JPEG, PNG, PDF.');
                    event.preventDefault();
                    return;
                }
            }
        });
    </script>

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>

    <script>
        const date = document.querySelector(".date");
        const today = new Date();
        let year = today.getFullYear() - 18;
        let month = today.getMonth() + 1;
        let day = today.getDate();
        date.setAttribute("min", (year - 52) + "-" + ((month > 9) ? month : "0" + month) + "-" + ((day > 9) ? day : "0" + day));
        date.setAttribute("max", year + "-" + ((month > 9) ? month : "0" + month) + "-" + ((day > 9) ? day : "0" + day));
    </script>

</body>

</html>
