<?php
require_once __DIR__ . '/../../../config/constants.php';
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
  <body class="bg-background">
    <form name="make-request" action="make-request.php" method="post" enctype="multipart/form-data" class="w-1/2 m-5 mx-auto rounded-lg border border-gray-100 bg-white p-4 shadow-lg transition hover:shadow-lg sm:p-6">
    <div id="rrs-form">
        <div id="header" class="flex flex-col items-center justify-center mb-4">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 mb-2">
        <h4 class="text-center text-lg font-semibold">
            REPAIR REQUEST FORM
        </h4>
        </div>

            <div class="mb-4">
                <label for="unit" class="text-sm text-text mb-1">
                    Unit:
                    <span class="text-accent">*</span>
                </label>
                <select id="unit" name="unit" required class="input-field w-full">
                    <option value="Select Unit" selected disabled>
                        Select Unit
                    </option>
                    <option value="Tagum Unit">
                        Tagum Unit
                    </option>
                    <option value="Mabini Unit">
                        Mabini Unit
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label for="buildingLoc" class="text-sm text-text mb-1">
                    Building Location:
                    <span class="text-accent">*</span>
                </label>
                <select name="exLoc" id="exLoc" required class="input-field w-full">
                    <option value="" selected disabled>Select a building location</option>
                    <option>PECC GYM</option>
                    <option>SOM/SCIENCE BUILDING</option>
                    <option>ADMIN BUILDING</option>
                    <option>LIBRARY BUILDING</option>
                    <option>FTC BUILDING</option>
                    <option>OTHERS</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="roomLoc" class="text-sm text-text mb-1">
                    Room Location:
                    <span class="text-accent">*</span>
                </label>
                <select name="exLoc" id="exLoc" required class="input-field w-full">
                    <option value="" selected disabled>Select a room location</option>
                    <option value="Office of the Registrar">Office of the Registrar</option>
                    <option value="Dance Studio">Dance Studio</option>
                    <option value="The Light Publication Office">The Light Publication Office</option>
                    <option value="Association of Future Secondary Teachers Office">Association of Future Secondary Teachers Office</option>
                    <option value="Society of Information Technology Students Office">Society of Information Technology Students Office</option>
                    <option value="Organization of Future Elementary Educator's Office">Organization of Future Elementary Educator's Office</option>
                    <option value="Storage Room">Storage Room</option>
                    <option value="ROTC Office">ROTC Office</option>
                    <option value="Music Room">Music Room</option>
                    <option value="Sports and Cultural Office">Sports and Cultural Office</option>
                    <option value="Career and Alumni Center (CAC) Office">Career and Alumni Center (CAC) Office</option>
                    <option value="University Assessment and Guidance Center (UAGC)">University Assessment and Guidance Center (UAGC)</option>
                    <option value="Office of Student Affairs and Services (OSAS)">Office of Student Affairs and Services (OSAS)</option>
                    <option value="Campus Clinic">Campus Clinic</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="picture" class="text-sm text-text mb-1">
                    Photo:
                    <span class="text-accent">*</span>
                </label>
                <input type="file" id="img" name="picture" class="input-field w-full">
            </div>
            
            <div class="mb-4" >
                <label for="dateNoticed" class="text-sm text-text mb-1">
                    Date the Issue was Noticed:
                    <span class="text-accent">*</span>
                </label>
                <input type="date" id="dateNoticed" name="dateNoticed" required class="input-field w-full">
            </div>

            <div>
                <label for="natureReq" class="text-sm text-text mb-1">
                    Nature of Request:
                    <span class="text-accent">*</span>
                </label>
            </div>

            <div class="grid grid-cols-3 grid-rows-3 gap-2 mb-4">
                <div class="nature-option">
                    <input type="radio" id="carpma" name="nature-request" value="Carpentry/Masonry">
                    <label for="carpma" class="text-sm text-text">
                        Carpentry/Masonry
                    </label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="welding" name="nature-request" value="Welding">
                    <label for="welding" class="text-sm text-text">
                        Welding
                    </label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="hauling" name="nature-request" value="Hauling">
                    <label for="hauling" class="text-sm text-text">
                        Hauling
                    </label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="plumbing" name="nature-request" value="Plumbing">
                    <label for="plumbing" class="text-sm text-text">
                        Plumbing
                    </label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="landscaping" name="nature-request" value="Landscaping">
                    <label for="landscaping" class="text-sm text-text">
                        Landscaping
                    </label>
                </div>
                <div class="nature-option">
                    <input type="radio" id="electrical" name="nature-request" value="Electrical">
                    <label for="electrical" class="text-sm text-text">
                        Electrical
                    </label>
                </div>
                
                <div class="nature-option">
                    <input type="radio" id="aircon" name="nature-request" value="Air-Condition">
                    <label for="aircon" class="text-sm text-text">
                        Air-Condition
                    </label>
                </div>
                <div class="nature-option" id="other-option">
                    <input type="radio" id="others" name="nature-request" value="Others">
                    <label for="others" class="text-sm text-text mr-1">
                        Others:
                    </label>
                    <input type="text" name="other-details" placeholder="Please specify" class="input-field p-1 w-1/2">
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="text-sm text-text mb-1">
                    Detailed Description of the Issue:
                    <span class="text-accent">*</span>
                </label><br>
                <textarea id="descrip" name="description" rows="3" class="input-field w-full" 
                placeholder="e.g., Water is leaking from the faucet in the Faculty Restroom near Room 205."></textarea>                
            </div>


            <div class="flex flex-row items-center justify-center gap-4 mt-4">
                <button type="button" class="btn btn-secondary" onclick="location.href='request.php'">
                    Cancel
                </button>
                <button class="btn btn-primary" type="submit" onclick="showReview()">
                    Submit
                </button>
            </div>
        </div>
    </div>
    </form>
    <script src="/public/assets/js/user/forms.js"></script>
  </body>
</html>
