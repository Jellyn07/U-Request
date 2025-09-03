<?php
require_once __DIR__ . '/../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>U-Request</title>
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
    <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
  </head>
  <body class="bg-primary">
    <form name="make-request" action="make-request.php" method="post" enctype="multipart/form-data" class="w-1/2 m-5 mx-auto rounded-lg border border-gray-100 bg-white p-4 shadow-lg transition hover:shadow-lg sm:p-6">
    <div id="rrs-form">
        <div id="header" class="flex flex-col items-center justify-center mb-4">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 mb-2">
        <h4 class="text-center text-lg font-semibold">REPIR REQUEST FORM</h4>
        </div>
        <div id="request-info">
            <label for="natureReq">Nature of Request:<span>*</span></label>
            <div id="nature-request-group" class="grid grid-cols-3 grid-rows-3 gap-4">
                <div class="nature-option">
                    <input type="radio" id="carpma" name="nature-request" value="Carpentry/Masonry">
                    <label for="carpma">Carpentry/Masonry</label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="welding" name="nature-request" value="Welding">
                    <label for="welding">Welding</label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="hauling" name="nature-request" value="Hauling">
                    <label for="hauling">Hauling</label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="plumbing" name="nature-request" value="Plumbing">
                    <label for="plumbing">Plumbing</label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="landscaping" name="nature-request" value="Landscaping">
                    <label for="landscaping">Landscaping</label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="electrical" name="nature-request" value="Electrical">
                    <label for="electrical">Electrical</label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="aircon" name="nature-request" value="Air-Condition">
                    <label for="aircon">Air-Condition</label>
                </div>
                <div class="nature-option" id="other-option">
                    <input type="radio" id="others" name="nature-request" value="Others">
                    <label for="others">Others:</label>
                    <input type="text" id="other-text" name="other-details" placeholder="Please specify">
                </div>
            </div>
            <label for="description">Detailed Description of the Issue:<span>*</span></label><br>
            <textarea id="descrip" name="description" rows="3"></textarea>

            <div class="form-last">
                <label for="unit">Unit:<span>*</span></label>
                <select id="unit" name="unit" required>
                    <option value="Select Unit" selected disabled>Select Unit</option>
                    <option value="Tagum Unit">Tagum Unit</option>
                    <option value="Mabini Unit">Mabini Unit</option>
                </select>
            </div>

            <div class="form-last">
                <label for="exLoc">Building Location:<span>*</span></label>
                <select name="exLoc" id="exLoc" required>
                    <option value="" selected disabled>Select a building location</option>
                    <option>PECC GYM</option>
                    <option>SOM/SCIENCE BUILDING</option>
                    <option>ADMIN BUILDING</option>
                    <option>LIBRARY BUILDING</option>
                    <option>FTC BUILDING</option>
                    <option>OTHERS</option>
                </select>
            </div>

            <div class="form-last">
                <label for="exLoc">Room Location:<span>*</span></label>
                <select name="exLoc" id="exLoc" required>
                    <option value="" selected disabled>Select a room location</option>
                    <optgroup label="PECC GYM">
                        <optgroup label="Ground Floor">
                            <option value="PECC GYM - Office of the Registrar">Office of the Registrar</option>
                            <option value="PECC GYM - Dance Studio">Dance Studio</option>
                            <option value="PECC GYM - The Light Publication Office">The Light Publication Office</option>
                            <option value="PECC GYM - Association of Future Secondary Teachers Office">Association of Future Secondary Teachers Office</option>
                            <option value="PECC GYM - Society of Information Technology Students Office">Society of Information Technology Students Office</option>
                            <option value="PECC GYM - Organization of Future Elementary Educator's Office">Organization of Future Elementary Educator's Office</option>
                            <option value="PECC GYM - Storage Room">Storage Room</option>
                            <option value="PECC GYM - ROTC Office">ROTC Office</option>
                            <option value="PECC GYM - Music Room">Music Room</option>
                            <option value="PECC GYM - Sports and Cultural Office">Sports and Cultural Office</option>
                            <option value="PECC GYM - Career and Alumni Center (CAC) Office">Career and Alumni Center (CAC) Office</option>
                            <option value="PECC GYM - University Assessment and Guidance Center (UAGC)">University Assessment and Guidance Center (UAGC)</option>
                            <option value="PECC GYM - Office of Student Affairs and Services (OSAS)">Office of Student Affairs and Services (OSAS)</option>
                            <option value="PECC GYM - Campus Clinic">Campus Clinic</option>
                        </optgroup>
                        <optgroup label="Lower Mezzanine">
                            <option value="PECC GYM - Fitness Room">Fitness Room</option>
                            <option value="PECC GYM - NSTP Office">NSTP Office</option>
                            <option value="PECC GYM - PECC-01 Classroom">PECC-01 Classroom</option>
                            <option value="PECC GYM - PEB 34 Classroom">PEB 34 Classroom</option>
                        </optgroup>
                        <optgroup label="Upper Mezzanine">
                            <option value="PECC GYM - PECC-02 Classroom">PECC-02 Classroom</option>
                            <option value="PECC GYM - PECC-03 Classroom">PECC-03 Classroom</option>
                            <option value="PECC GYM - PECC-04 Classroom">PECC-04 Classroom</option>
                            <option value="PECC GYM - PEB 37 Classroom">PEB 37 Classroom</option>
                        </optgroup>
                    </optgroup>
                    <optgroup label="SOM/SCIENCE BUILDING">
                        <optgroup label="Ground Floor">
                            <option value="SOM/SCIENCE BUILDING - SB 41 Classroom">SB 41 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB 42 Classroom">SB 42 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SOM Library">SOM Library</option>
                            <option value="SOM/SCIENCE BUILDING - School of Medicine Faculty Room">School of Medicine Faculty Room</option>
                            <option value="SOM/SCIENCE BUILDING - Office of the Dean (School of Medicine)">Office of the Dean (School of Medicine)</option>
                            <option value="SOM/SCIENCE BUILDING - Office of the Dean (CTET)">Office of the Dean (CTET)</option>
                            <option value="SOM/SCIENCE BUILDING - CTET Storage Room">CTET Storage Room</option>
                            <option value="SOM/SCIENCE BUILDING - CTET-Research and Extension Office">CTET-Research and Extension Office</option>
                            <option value="SOM/SCIENCE BUILDING - Simulation Laboratory (Intensive Care Unit)">Simulation Laboratory (Intensive Care Unit)</option>
                            <option value="SOM/SCIENCE BUILDING - Simulation Laboratory (Emergency Room)">Simulation Laboratory (Emergency Room)</option>
                            <option value="SOM/SCIENCE BUILDING - Simulation Laboratory (OR/DR)">Simulation Laboratory (OR/DR)</option>
                            <option value="SOM/SCIENCE BUILDING - Conference Room">Conference Room</option>
                            <option value="SOM/SCIENCE BUILDING - Storage Room">Storage Room</option>
                        </optgroup>
                        <optgroup label="Second Floor">
                            <option value="SOM/SCIENCE BUILDING - SB-01 Classroom">SB-01 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-02 Classroom">SB-02 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-03 Classroom">SB-03 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-04 Classroom">SB-04 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-05 Classroom">SB-05 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-06 Classroom">SB-06 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - Multi Spectral Imaging Office">Multi Spectral Imaging Office</option>
                            <option value="SOM/SCIENCE BUILDING - Computer Laboratory 1">Computer Laboratory 1</option>
                            <option value="SOM/SCIENCE BUILDING - Computer Laboratory 2">Computer Laboratory 2</option>
                            <option value="SOM/SCIENCE BUILDING - Researcher's Niche">Researcher's Niche</option>
                            <option value="SOM/SCIENCE BUILDING - Campus Recording Studio">Campus Recording Studio</option>
                        </optgroup>
                        <optgroup label="Third Floor">
                            <option value="SOM/SCIENCE BUILDING - SB-07 Classroom">SB-07 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-08 Classroom">SB-08 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-09 Classroom">SB-09 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-10 Classroom">SB-10 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-11 Classroom">SB-11 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-12 Classroom">SB-12 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-13 Classroom">SB-13 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - SB-14 Classroom">SB-14 Classroom</option>
                            <option value="SOM/SCIENCE BUILDING - Prayer Room for Women">Prayer Room for Women</option>
                            <option value="SOM/SCIENCE BUILDING - Pharmacology Laboratory">Pharmacology Laboratory</option>
                            <option value="SOM/SCIENCE BUILDING - Research Laboratory">Research Laboratory</option>
                        </optgroup>
                        <optgroup label="Roof Deck">
                            <option value="SOM/SCIENCE BUILDING - Conference Room (Roof Deck)">Conference Room (Roof Deck)</option>
                        </optgroup>
                    </optgroup>
                    <optgroup label="ADMIN BUILDING">
                        <optgroup label="Gound Floor">
                            <option value="ADMIN BUILDING - Audio Visual Room (AVR)">Audio Visual Room (AVR)</option>
                            <option value="ADMIN BUILDING - Bids and Awards Committee (BAC) Office">Bids and Awards Committee (BAC) Office</option>
                            <option value="ADMIN BUILDING - Collecting Office">Collecting Office</option>
                            <option value="ADMIN BUILDING - Disbursing Office">Disbursing Office</option>
                            <option value="ADMIN BUILDING - Office of Administrative Support Services">Office of Administrative Support Services</option>
                            <option value="ADMIN BUILDING - Student Account">Student Account</option>
                            <option value="ADMIN BUILDING - Office of the Chancellor">Office of the Chancellor</option>
                        </optgroup>
                        <optgroup label="Second Floor">
                            <option value="ADMIN BUILDING - Computer Laboratory">Computer Laboratory</option>
                            <option value="ADMIN BUILDING - BSIT Department">BSIT Department</option>
                            <option value="ADMIN BUILDING - AutoCAD Room">AutoCAD Room</option>
                            <option value="ADMIN BUILDING - Campus Planning Office">Campus Planning Office</option>
                            <option value="ADMIN BUILDING - DABE Faculty Room">DABE Faculty Room</option>
                        </optgroup>
                    </optgroup>
                    <optgroup label="LIBRARY BLDG.">
                        <optgroup label="Ground Floor">
                            <option value="LIBRARY BLDG. - Histology and Pathology Laboratory">Histology and Pathology Laboratory</option>
                            <option value="LIBRARY BLDG. - Biochemistry and Physiology Laboratory">Biochemistry and Physiology Laboratory</option>
                            <option value="LIBRARY BLDG. - E-Library">E-Library</option>
                        </optgroup>
                        <optgroup label="Second Floor">
                            <option value="LIBRARY BLDG. - Campus Library">Campus Library</option>
                            <option value="LIBRARY BLDG. - Accreditation Building">Accreditation Building</option>
                            <option value="LIBRARY BLDG. - Food Tech Laboratory">Food Tech Laboratory</option>
                            <option value="LIBRARY BLDG. - Accreditation Center">Accreditation Center</option>
                            <option value="LIBRARY BLDG. - CEDU Office">CEDU Office</option>
                        </optgroup>
                    </optgroup>
                    <optgroup label="FTC BUILDING">
                        <option value="FTC BUILDING - BSA Department Office 1">BSA Department Office 1</option>
                        <option value="FTC BUILDING - BSA Department Office 2">BSA Department Office 2</option>
                        <option value="FTC BUILDING - BSA Department Office 3">BSA Department Office 3</option>
                        <option value="FTC BUILDING - Faculty Club Office">Faculty Club Office</option>
                        <option value="FTC BUILDING - BSED Department Office">BSED Department Office</option>
                        <option value="FTC BUILDING - General Education Department Office">General Education Department Office</option>
                        <option value="FTC BUILDING - Farmers Training Center (FTC)">Farmers Training Center (FTC)</option>
                        <option value="FTC BUILDING - Campus Hostel">Campus Hostel</option>
                    </optgroup>
                    <optgroup label="OTHERS">
                        <option value="OTHERS - Main Gate">Main Gate</option>
                        <option value="OTHERS - Exit Gate">Exit Gate</option>
                        <option value="OTHERS - Alumni Café">Alumni Café</option>
                    </optgroup>
                </select>
            </div>
            
            <div class="form-last">
                <label for="picture">Photo:<span>*</span></label>
                <input type="file" id="img" name="picture">
            </div>
            
            <div class="form-last" style="display: none;">
                <label for="dateNoticed">Date the Issue was Noticed:<span>*</span></label>
                <input type="date" id="dateNoticed" name="dateNoticed" required>
            </div>
            <button class="inside-block bg-background text-primary p-2 rounded-md text-sm border border-primary" onclick="location.href='request.php'">Back</button>
            <button class="inside-block bg-secondary text-background p-2 rounded-md text-sm" type="submit" onclick="showReview()">Review</button>
        </div>
    </div>
    </form>
    <script src="/public/assets/js/user/forms.js"></script>
  </body>
</html>
