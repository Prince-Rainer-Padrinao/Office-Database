<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Data Form</title>
    <style>
        body {
            font-family: Calibri, Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: white;
            border: 1px solid #000;
            width: 750px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
        }
        .form-header {
            background-color: #3b5336; /* Dark green */
            color: white;
            padding: 12px 20px;
            font-size: 26px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-body {
            padding: 30px 40px;
            display: flex;
            gap: 50px;
        }
        .column {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .form-group {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        .form-group label {
            font-weight: bold;
            margin-right: 12px;
            font-size: 15px;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"] {
            width: 190px;
            padding: 5px;
            border: 1px solid #000;
            background-color: #dcdcdc;
            font-family: Calibri, Arial, sans-serif;
            font-size: 15px;
            outline: none;
        }
        .form-group.financial input {
            text-align: right;
        }
        .radio-group {
            width: 190px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 14px;
        }
        .radio-group input[type="radio"] {
            margin: 0 5px 0 0;
        }
        .submit-container {
            text-align: center;
            padding: 0 0 25px 0;
        }
        .submit-btn {
            padding: 6px 35px;
            font-weight: bold;
            font-size: 16px;
            border: 1px solid #000;
            background-color: #f0f0f0;
            cursor: pointer;
            box-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }
        .submit-btn:hover {
            background-color: #e0e0e0;
        }
        .link-text {
            color: #0563C1 !important;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        Employee Data Form
        <span style="font-size: 20px;">📊</span>
    </div>
    
    <form id="employeeForm">
        <div class="form-body">
            <div class="column">
                <div class="form-group">
                    <label>Employee Name</label>
                    <input type="text" name="emp_name" value="Ajay">
                </div>
                <div class="form-group">
                    <label>Employee Code</label>
                    <input type="text" name="emp_code" value="XNC001">
                </div>
                <div class="form-group">
                    <label>Email ID</label>
                    <input type="email" name="email" class="link-text" value="ajay@xlncad.com">
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="text" name="dob" value="03-03-1985">
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <input type="text" name="department" value="Technical">
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <div class="radio-group">
                        <label><input type="radio" name="location" value="Site" checked> Site</label>
                        <label><input type="radio" name="location" value="Office"> Office</label>
                    </div>
                </div>
            </div>

            <div class="column">
                <div class="form-group financial">
                    <label>Basic Salary</label>
                    <input type="number" name="basic_salary" id="basic" value="60000" class="calc-input">
                </div>
                <div class="form-group financial">
                    <label>HRA</label>
                    <input type="number" name="hra" id="hra" value="25000" class="calc-input">
                </div>
                <div class="form-group financial">
                    <label>Medical</label>
                    <input type="number" name="medical" id="medical" value="6800" class="calc-input">
                </div>
                <div class="form-group financial">
                    <label>Conveyance</label>
                    <input type="number" name="conveyance" id="conveyance" value="4500" class="calc-input">
                </div>
                <div class="form-group financial">
                    <label>Deductions</label>
                    <input type="number" name="deductions" id="deductions" value="1850" class="calc-input">
                </div>
                <div class="form-group financial" style="margin-top: 5px;">
                    <label>Net Pay</label>
                    <input type="number" name="net_pay" id="netPay" value="96300" readonly style="background-color: #c8c8c8;"> 
                </div>
            </div>
        </div>

        <div class="submit-container">
            <br><br>
            <a href="database.php" class="submit-btn" style="text-decoration: none; background-color: #3b5336; color: white;">View Database</a>
            <button type="submit" class="submit-btn">Submit</button>
        </div>
    </form>
</div>

<script>
    // 1. Math Logic Section
    const calcInputs = document.querySelectorAll('.calc-input');
    const netPayOutput = document.getElementById('netPay');

    function calculateNetPay() {
        const basic = parseFloat(document.getElementById('basic').value) || 0;
        const hra = parseFloat(document.getElementById('hra').value) || 0;
        const medical = parseFloat(document.getElementById('medical').value) || 0;
        const conveyance = parseFloat(document.getElementById('conveyance').value) || 0;
        const deductions = parseFloat(document.getElementById('deductions').value) || 0;

        const total = (basic + hra + medical + conveyance) - deductions;
        netPayOutput.value = total;
    }

    calcInputs.forEach(input => {
        input.addEventListener('input', calculateNetPay);
    });

    // 2. Form Submission (AJAX) Section - moved OUTSIDE the math function
    const form = document.getElementById('employeeForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); 

        const formData = new FormData(form);

        fetch('insert.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                alert('✅ Employee data saved successfully!'); 
                form.reset(); 
                calculateNetPay(); 
            } else {
                alert('❌ Something went wrong saving the data: ' + data);
            }
        })
        .catch(error => {
            alert('❌ Server connection error.');
        });
    });
</script>

</body>
</html>
