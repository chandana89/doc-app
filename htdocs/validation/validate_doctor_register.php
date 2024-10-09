<script>
//String Pattern for Regex
var patternName = /^[a-zA-Z]+(?:['-][a-zA-Z]+)*(?:\s[a-zA-Z]+(?:['-][a-zA-Z]+)*)*$/;
var patternContactNo = /^(?:\+?61|0)[2-478](?:\d{4}){2}$/;
var patternPassword = /(?=^.{8,}$)(?=.*\d)(?=.*[!@#$%^&*]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/;
var patternEmail = /^(?!\.)(?!.*\.\.)(?!.*\.@)[a-zA-Z0-9_.+-]+@[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*(\.[a-zA-Z0-9]+)*\.[a-zA-Z0-9]{2,}$/;
var patternDescription = /^[a-zA-Z0-9\s.,;:?!()\-_'\"&@#/$%*]*$/;
var patternDate = /^(?:(?:19|[2-9]\d)\d{2})-(?:(?:0[13578]|1[02])-(?:31)|(?:0[1,3-9]|1[0-2])-(?:29|30)|(?:0[1-9]|1[0-2])-(?:0[1-9]|1\d|2[0-8])|(?:02-29))$/;


function generateErrorElement(htmlCode) {
    const err = document.createElement('div');
    err.innerHTML = htmlCode;
    return err;
}

//Validate Input Fields
function validateForm() {
    //Clear All Error Messages
    for (let i = 1; i < 23; i++) {
        try {
            document.getElementById("error_message_" + i).remove();
        } catch (err) {
            console.log(err);
        }
    }

    var valid = true;
    var errorElement = "";
    var inputData = "";

    inputData = document.getElementById("email").value;
    if (inputData != "" && inputData != null) {
        document.getElementById("email").classList.add = "valid_input_field";
        console.log("Input Not Empty");

        var url = "../verify/checkIfAccountExists.php";
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText.length > 2) {
                    valid = false;
                    document.getElementById("email").parentNode.append(generateErrorElement(
                        '<div class="custom_error_message_container" id="error_message_1"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Doctor account already exists!</span></div>'
                    ));
                    document.getElementById("email").classList.add = "error_input_field";
                }
            }
        };
        xhttp.open("POST", url, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("email=" + inputData);
    } 

    inputData = document.getElementById("firstName").value;
    if (inputData != "" && inputData != null) {
        if (patternName.test(inputData) == false) {
            document.getElementById("firstName").parentNode.append(generateErrorElement(
                '<div class="custom_error_message_container" id="error_message_2"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Please enter a valid first name</span></div>'
            ));
            document.getElementById("firstName").classList.add = "error_input_field";
            console.log("Incorrect Format");
            valid = false;
        } else {
            document.getElementById("firstName").classList.add = "valid_input_field";
            console.log("Correct Format");
        }
    } else {
        document.getElementById("firstName").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_3"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">This field is required!</span></div>'
        ));
        console.log("Input Empty");
        valid = false;
    }

    inputData = document.getElementById("lastName").value;
    if (inputData != "" && inputData != null) {
        if (patternName.test(inputData) == false) {
            document.getElementById("lastName").parentNode.append(generateErrorElement(
                '<div class="custom_error_message_container" id="error_message_4"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Please enter a valid last name</span></div>'
            ));
            document.getElementById("lastName").classList.add = "error_input_field";
            console.log("Incorrect Format");
            valid = false;
        } else {
            document.getElementById("lastName").classList.add = "valid_input_field";
            console.log("Correct Format");
        }
    } else {
        document.getElementById("lastName").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_5"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">This field is required!</span></div>'
        ));
        document.getElementById("lastName").classList.add = "error_input_field";
        console.log("Input Empty");
        valid = false;
    }

    inputData = document.getElementById("email").value;
    if (inputData != "" && inputData != null) {
        if (patternEmail.test(inputData) == false) {
            document.getElementById("email").parentNode.append(generateErrorElement(
                '<div class="custom_error_message_container" id="error_message_6"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Please enter a valid email</span></div>'
            ));
            document.getElementById("email").classList.add = "error_input_field";
            console.log("Incorrect Format");
            valid = false;
        } else {
            document.getElementById("email").classList.add = "valid_input_field";
            console.log("Correct Format");
        }
    } else {
        document.getElementById("email").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_7"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">This field is required!</span></div>'
        ));
        document.getElementById("email").classList.add = "error_input_field";
        console.log("Input Empty");
        valid = false;
    }

    var maleRadio = document.getElementById("male");
    var femaleRadio = document.getElementById("female");
    if (maleRadio.checked || femaleRadio.checked) {
    
        document.getElementById("male").classList.add = "valid_input_field";
        console.log("Correct Format");
        
    } else {
        document.getElementById("male").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_8"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">This field is required!</span></div>'
        ));
        document.getElementById("male").classList.add = "error_input_field";
        console.log("Input Empty");
        valid = false;
    }

    inputData = document.getElementById("description").value;
    if (inputData != "" && inputData != null) {
        if (patternDescription.test(inputData) == false) {
            document.getElementById("description").parentNode.append(generateErrorElement(
                '<div class="custom_error_message_container" id="error_message_9"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Please enter a valid address</span></div>'
            ));
            document.getElementById("description").classList.add = "error_input_field";
            console.log("Incorrect Format");
            valid = false;
        } else {
            document.getElementById("description").classList.add = "valid_input_field";
            console.log("Correct Format");
        }
    } else {
        document.getElementById("description").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_10"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">This field is required!</span></div>'
        ));
        document.getElementById("description").classList.add = "error_input_field";
        console.log("Input Empty");
        valid = false;
    }

    inputData = document.getElementById("contactNo").value;
    if (inputData != "" && inputData != null) {
        if (patternContactNo.test(inputData) == false) {
            document.getElementById("contactNo").parentNode.append(generateErrorElement(
                '<div class="custom_error_message_container" id="error_message_10"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Enter a valid contact number</span></div>'
            ));
            document.getElementById("contactNo").classList.add = "error_input_field";
            console.log("Incorrect Format");
            valid = false;
        } else {
            document.getElementById("contactNo").classList.add = "valid_input_field";
            console.log("Correct Format");
        }
    } else {
        document.getElementById("contactNo").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_11"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">This field is required!</span></div>'
        ));
        document.getElementById("contactNo").classList.add = "error_input_field";
        console.log("Input Empty");
        valid = false;
    }

    inputData = document.getElementById("dob").value;
    if (inputData != "" && inputData != null) {
        if (patternDate.test(inputData) == false) {
            document.getElementById("dob").parentNode.append(generateErrorElement(
                '<div class="custom_error_message_container" id="error_message_12"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Please enter a valid date of birth</span></div>'
            ));
            document.getElementById("dob").classList.add = "error_input_field";
            console.log("Incorrect Format");
            valid = false;
        } else {
            document.getElementById("dob").classList.add = "valid_input_field";
            console.log("Correct Format");
        }
    } else {
        document.getElementById("dob").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_13"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">This field is required!</span></div>'
        ));
        document.getElementById("dob").classList.add = "error_input_field";
        console.log("Input Empty");
        valid = false;
    }

    inputData = document.getElementById("specialization").value;
    if (inputData == "-1") {
        document.getElementById("specialization").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_14"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Please select an option</span></div>'
        ));
        document.getElementById("specialization").classList.add = "error_input_field";
        console.log("Incorrect Format");
        valid = false;
    } else {
        document.getElementById("specialization").classList.add = "valid_input_field";
        console.log("Correct Format");
    }

    inputData = document.getElementById("hospital").value;
    if (inputData == "-1") {
        document.getElementById("hospital").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_15"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Please select an option</span></div>'
        ));
        document.getElementById("hospital").classList.add = "error_input_field";
        console.log("Incorrect Format");
        valid = false;
    } else {
        document.getElementById("hospital").classList.add = "valid_input_field";
        console.log("Correct Format");
    }

    var myPassword = document.getElementById("password").value;
    var myRePassword = document.getElementById("rePassword").value;

    //Check Password Matched Re-entered Password
    if (myPassword != "" && myPassword != null) {
        if (myRePassword != "" && myRePassword != null) {
            if (myPassword != myRePassword) {
                const alertDiv = document.createElement("div");
                alertDiv.innerHTML =
                    '<div class="custom_error_message_container" id="error_message_16"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Passwords do not match. Try again.</span></div>';
                document.getElementById('passwordSwap').insertBefore(alertDiv, document.getElementsByClassName(
                    'custom_alert_info_register')[0]);

                document.getElementById("rePassword").classList.add = "error_input_field";
                console.log("Re-enter Password Doesn't Match");
                valid = false;
                //Check Password Matches Format
            } else if (myPassword == myRePassword) {
                document.getElementById("rePassword").classList.add = "valid_input_field";
                if (patternPassword.test(myPassword) == false) {
                    //document.getElementById("rePassword").parentNode.append(generateErrorElement('<div class="custom_error_message_container" id="error_message_15"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Password is in incorrect format!</span></div>'));   
                    document.getElementById("password").classList.add = "error_input_field";
                    document.getElementById("rePassword").classList.add = "error_input_field";
                    console.log("Password Doesn't Match Pattern");
                    valid = false;

                    const alertDiv = document.createElement("div");
                    alertDiv.innerHTML =
                        '<div class="custom_error_message_container" id="error_message_17"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Password is in incorrect format!</span></div>';
                    document.getElementById('passwordSwap').insertBefore(alertDiv, document.getElementsByClassName(
                        'custom_alert_info_register')[0]);
                }
            }
        } else {
            document.getElementById("rePassword").classList.add = "error_input_field";
            const alertDiv = document.createElement("div");
            alertDiv.innerHTML =
                '<div class="custom_error_message_container" id="error_message_18"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">This field is required!</span></div>';
            document.getElementById('passwordSwap').insertBefore(alertDiv, document.getElementsByClassName(
                'custom_alert_info_register')[0]);

            valid = false;
        }
    } else {
        document.getElementById("password").classList.add = "error_input_field";
        document.getElementById("password").parentNode.append(generateErrorElement(
            '<div class="custom_error_message_container" id="error_message_22"><i class="fa fa-exclamation-circle"></i><span class="custom_error_message">This field is required!</span></div>'
        ));
        valid = false;
    }

    return valid;
}
</script>