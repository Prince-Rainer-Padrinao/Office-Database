<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFW Profiling Form</title>
    <style>
        body {
            font-family: Calibri, Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 40px 20px;
        }
        .form-container {
            background-color: white;
            border: 1px solid #000;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 2px 2px 15px rgba(0,0,0,0.2);
            padding: 0;
        }
        .form-header {
            background-color: #3b5336; /* Dark green */
            color: white;
            padding: 20px;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .form-body {
            padding: 30px 50px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #3b5336;
            border-bottom: 2px solid #3b5336;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 5px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 200px;
        }
        .form-group.full-width {
            flex: 100%;
        }
        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group input[type="date"] {
            padding: 8px;
            border: 1px solid #000;
            background-color: #f9f9f9;
            font-family: Calibri, Arial, sans-serif;
            font-size: 15px;
            outline: none;
            width: 100%;
            box-sizing: border-box;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="number"]:focus,
        .form-group input[type="date"]:focus {
            background-color: #e8f4ea;
            border-color: #3b5336;
        }
        .radio-group, .checkbox-group {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            padding: 5px 0;
        }
        .radio-group label, .checkbox-group label {
            font-weight: normal;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        .submit-container {
            text-align: center;
            padding: 30px 0;
            margin-top: 20px;
            background-color: #eef2ed;
            border-top: 1px solid #ccc;
        }
        .submit-btn {
            padding: 12px 50px;
            font-weight: bold;
            font-size: 18px;
            border: 1px solid #000;
            background-color: #3b5336;
            color: white;
            cursor: pointer;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
            text-transform: uppercase;
        }
        .submit-btn:hover {
            background-color: #2a3b26;
        }
        .top-links {
            max-width: 900px;
            margin: 0 auto 15px auto;
            text-align: right;
        }
        .top-links a {
            background-color: #0563C1;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #000;
        }
        .top-links a:hover {
            background-color: #044a91;
        }
    </style>
</head>
<body>

<div class="top-links">
    <a href="database.php">📊 View Database</a>
</div>

<div class="form-container">
    <div class="form-header">
        OFW Profiling Form
    </div>
    
    <form id="employeeForm">
        <div class="form-body">
            
            <div class="section-title">I. PERSONAL INFORMATION</div>
            <div class="form-row">
                <div class="form-group"><label>Last Name</label><input type="text" name="last_name" required></div>
                <div class="form-group"><label>First Name</label><input type="text" name="first_name" required></div>
                <div class="form-group"><label>Middle Name</label><input type="text" name="middle_name"></div>
            </div>
            <div class="form-row">
                <div class="form-group" style="flex: 2;">
                    <label>Sex</label>
                    <div class="radio-group">
                        <label><input type="radio" name="sex" value="Male" required> Male</label>
                        <label><input type="radio" name="sex" value="Female"> Female</label>
                        <label><input type="radio" name="sex" value="Prefer not to say"> Prefer not to say</label>
                    </div>
                </div>
                <div class="form-group"><label>Date of Birth</label><input type="date" name="dob" required></div>
                <div class="form-group"><label>Age</label><input type="number" name="age" required></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Civil Status</label>
                    <div class="radio-group">
                        <label><input type="radio" name="civil_status" value="Single" required> Single</label>
                        <label><input type="radio" name="civil_status" value="Married"> Married</label>
                        <label><input type="radio" name="civil_status" value="Widowed"> Widowed</label>
                        <label><input type="radio" name="civil_status" value="Separated"> Separated</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contact Number</label><input type="text" name="contact_number" required></div>
                <div class="form-group"><label>Email Address</label><input type="email" name="email"></div>
                <div class="form-group"><label>Facebook Account (optional)</label><input type="text" name="facebook"></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width"><label>Complete Philippine Address (Street/Block/Lot)</label><input type="text" name="ph_address" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Barangay</label><input type="text" name="barangay" required></div>
                <div class="form-group"><label>Municipality</label><input type="text" name="municipality" required></div>
                <div class="form-group"><label>Province</label><input type="text" name="province" required></div>
            </div>

            <div class="section-title">II. PASSPORT & IDENTIFICATION DETAILS</div>
            <div class="form-row">
                <div class="form-group"><label>Passport Number</label><input type="text" name="passport_number" required></div>
                <div class="form-group"><label>Passport Expiry Date</label><input type="date" name="passport_expiry" required></div>
                <div class="form-group"><label>OEC Number (if available)</label><input type="text" name="oec_number"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>DMW/POEA E-Registration Number</label><input type="text" name="dmw_number"></div>
                <div class="form-group"><label>SSS Number</label><input type="text" name="sss_number"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>PhilHealth Number</label><input type="text" name="philhealth_number"></div>
                <div class="form-group"><label>Pag-IBIG Number</label><input type="text" name="pagibig_number"></div>
            </div>

            <div class="section-title">III. EMPLOYMENT DETAILS (ABROAD)</div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Current Status</label>
                    <div class="radio-group">
                        <label><input type="radio" name="current_status" value="Active OFW" required> Active OFW</label>
                        <label><input type="radio" name="current_status" value="Vacationing in PH"> Vacationing in PH</label>
                        <label><input type="radio" name="current_status" value="End of Contract"> End of Contract</label>
                        <label><input type="radio" name="current_status" value="Repatriated"> Repatriated</label>
                        <label><input type="radio" name="current_status" value="Returning to Host Country"> Returning to Host Country</label>
                        <label><input type="radio" name="current_status" value="Jobseeker"> Jobseeker</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Country of Employment</label><input type="text" name="country_employment" required></div>
                <div class="form-group"><label>Job Position/Occupation</label><input type="text" name="job_position" required></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width"><label>Employer/Company Name</label><input type="text" name="employer_name"></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Employment Type</label>
                    <div class="radio-group">
                        <label><input type="radio" name="employment_type" value="Land-based" required> Land-based</label>
                        <label><input type="radio" name="employment_type" value="Sea-based"> Sea-based</label>
                    </div>
                </div>
                <div class="form-group"><label>Deployment Date</label><input type="date" name="deployment_date"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contract Duration</label><input type="text" name="contract_duration" placeholder="e.g., 2 years"></div>
                <div class="form-group"><label>Monthly Salary (currency)</label><input type="text" name="monthly_salary" placeholder="e.g., 1500 USD"></div>
            </div>

            <div class="section-title">IV. SKILLS & TRAINING</div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Highest Educational Attainment</label>
                    <div class="radio-group">
                        <label><input type="radio" name="education" value="Elementary" required> Elementary</label>
                        <label><input type="radio" name="education" value="High School"> High School</label>
                        <label><input type="radio" name="education" value="Vocational"> Vocational</label>
                        <label><input type="radio" name="education" value="College"> College</label>
                        <label><input type="radio" name="education" value="Postgraduate"> Postgraduate</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Field of Study</label><input type="text" name="field_of_study"></div>
                <div class="form-group"><label>TESDA Certification (if any)</label><input type="text" name="tesda_cert"></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width"><label>Other Skills/Certifications</label><input type="text" name="other_skills"></div>
            </div>

            <div class="section-title">V. FAMILY INFORMATION</div>
            <div class="form-row">
                <div class="form-group"><label>Name of Beneficiary/Next of Kin</label><input type="text" name="kin_name" required></div>
                <div class="form-group"><label>Relationship</label><input type="text" name="kin_relationship" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contact Number</label><input type="text" name="kin_contact" required></div>
                <div class="form-group"><label>Number of Dependents</label><input type="number" name="dependents"></div>
            </div>

            <div class="section-title">VI. ASSISTANCE & CONCERNS</div>
            <div class="form-row">
                <div class="form-group">
                    <label>Have you availed any DMW/OWWA assistance?</label>
                    <div class="radio-group">
                        <label><input type="radio" name="availed_assistance" value="Yes" required> Yes</label>
                        <label><input type="radio" name="availed_assistance" value="No"> No</label>
                    </div>
                </div>
                <div class="form-group"><label>If yes, specify:</label><input type="text" name="assistance_specify"></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Current Concerns/Needs (Check all that apply)</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="concerns[]" value="Legal Assistance"> Legal Assistance</label>
                        <label><input type="checkbox" name="concerns[]" value="Repatriation"> Repatriation</label>
                        <label><input type="checkbox" name="concerns[]" value="Financial Assistance"> Financial Assistance</label>
                        <label><input type="checkbox" name="concerns[]" value="Livelihood Program"> Livelihood Program</label>
                        <label><input type="checkbox" name="concerns[]" value="Scholarship"> Scholarship</label>
                        <label><input type="checkbox" name="concerns[]" value="Reintegration Program"> Reintegration Program</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full-width"><label>Others (specify):</label><input type="text" name="concerns_others"></div>
            </div>

            <div class="section-title">VII. EMERGENCY INFORMATION</div>
            <div class="form-row">
                <div class="form-group"><label>Emergency Contact Person</label><input type="text" name="emergency_contact_name" required></div>
                <div class="form-group"><label>Relationship</label><input type="text" name="emergency_relationship" required></div>
                <div class="form-group"><label>Contact Number</label><input type="text" name="emergency_contact_number" required></div>
            </div>

            <div class="section-title">VIII. DATA PRIVACY CONSENT</div>
            <p style="font-size: 14px; text-align: justify; color: #555;">
                I hereby certify that the information provided is true and correct to the best of my knowledge. I authorize the Department of Migrant Workers (DMW) to use my information for database, profiling, and assistance purposes in accordance with the Data Privacy Act of 2012.
            </p>
            <div class="form-row">
                <div class="form-group">
                    <label style="color: #c00;">
                        <input type="checkbox" name="privacy_consent" value="Agreed" required> I agree and consent to the Data Privacy terms above.
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>E-Signature (Type Full Name)</label><input type="text" name="signature" required></div>
                <div class="form-group"><label>Date Signed</label><input type="date" name="date_signed" required></div>
            </div>

        </div>

        <div class="submit-container">
            <button type="submit" class="submit-btn">Submit Profile</button>
        </div>
    </form>
</div>

<script>
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
                alert('✅ OFW Profile saved successfully!'); 
                form.reset(); 
                window.scrollTo(0, 0); // Scroll back to the top for the next entry
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
