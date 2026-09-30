<?php 
require '../php/check_session.php'; 

$host = "localhost"; $user = "root"; $pass = ""; $dbname = "nimasa";
$conn = new mysqli($host, $user, $pass, $dbname);

$js_app_data = json_encode(null);

if (isset($_SESSION['tag'])) {
    $current_tag = (int)$_SESSION['tag']; 
    $user_email = $_SESSION['user_email'];

    $stmt = $conn->prepare("SELECT * FROM cabotage WHERE email = ? AND tag = ? LIMIT 1");
    $stmt->bind_param("si", $user_email, $current_tag);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        function clean($val) {
            return ($val === "0" || $val === 0 || is_null($val)) ? "" : $val;
        }

        $formatted_data = [
            'form_fields' => [
                'text_data' => [
                    clean($row['dat1']), 
                    clean($row['dat2']), 
                    clean($row['dat3']), 
                    clean($row['dat4']), 
                    clean($row['dat5']),
                    // --- NEW TEXT DATA ---
                    clean($row['imo_number']),      // index 5
                    clean($row['official_number'])  // index 6
                ],
                'file_orig_names' => [
                    // Page 1 Files (index 0-12)
                    clean($row['dat6_name']), clean($row['dat7_name']), clean($row['dat8_name']), 
                    clean($row['dat9_name']), clean($row['dat10_name']), clean($row['dat11_name']),
                    clean($row['dat12_name']), clean($row['dat13_name']), clean($row['dat14_name']),
                    clean($row['dat15_name']), clean($row['dat16_name']), clean($row['dat17_name']),
                    clean($row['dat18_name']),
                    // Page 2 Files (index 13-28)
                    clean($row['dat19_name']), clean($row['dat20_name']), clean($row['dat21_name']), 
                    clean($row['dat22_name']), clean($row['dat23_name']), clean($row['dat24_name']),
                    clean($row['dat25_name']), clean($row['dat26_name']), clean($row['dat27_name']),
                    clean($row['dat28_name']), clean($row['dat29_name']), clean($row['dat30_name']),
                    clean($row['dat31_name']), clean($row['dat32_name']), clean($row['dat33_name']),
                    clean($row['dat34_name']),
                    // --- NEW SPECIAL FILES (index 29, 30, 31) ---
                    clean($row['tax_clearance_name']), 
                    clean($row['manning_liscense_name']), 
                    clean($row['maritime_labour_name'])
                ]
            ]
        ];
        $js_app_data = json_encode($formatted_data);
    }
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<head>
<title>NIMASA Cabotage waiver: Bareboat</title>
<link rel="stylesheet" href="../css/style2.css">
<link rel="icon" href="../images/logo.png" type="image/x-icon">
<meta name="viewport" content="width=device-width" initial-scale="1.0">

</head>

<html>




<script>
let currentTag = <?php echo isset($_SESSION['tag']) ? (int)$_SESSION['tag'] : 0; ?>;
const sessionData = <?php echo $js_app_data; ?>;

function processApplicationData(data) {
    if (!data || !data.form_fields) return;

    const textFields = data.form_fields.text_data;
    const fileNames = data.form_fields.file_orig_names;

    // 1. Populate Text Fields (including new ones)
    // IDs: noc=dat1, nov=dat2, tov=dat3, grt=dat4, vrs=dat5, imono=imo_number, vofno=official_number
    const textIds = ["noc", "nov", "tov", "grt", "vrs", "imono", "vofno"];
    textIds.forEach((id, i) => {
        const el = document.getElementById(id);
        if (el) el.value = textFields[i] || "";
    });

    // 2. Populate File Labels (including new ones)
    const fileDisplayIds = [
        "CAF", "BOS", "EOCNSRC", "BC", "ATPICTF", "CLDF", "DOF", "DCLWOTCLHP", "DOE", "CMSMC", "CMLDF", 
		"CWF1", "CAF1", "BOS1", "EOCNSRC1", "BC1", "ATPICTF1", "CLDF1", "DOF1", "DCLWOTCLHP1", "DOE1", "CMSMC1", 
		"DUMMY1", "DUMMY2", "DUMMY3", "DUMMY4", "DUMMY5", "DUMMY6", "DUMMY7",
        
        "TCC", "MLSC", "MLCT" 
    ];




    fileDisplayIds.forEach((id, index) => {
        const element = document.getElementById(id);
		
        const nameFromServer = fileNames[index];

        if (element) {
            if (nameFromServer && nameFromServer !== "0" && nameFromServer !== 0) {
                element.innerText = "--- " + nameFromServer + " ---";
                
                // Set the uploadFile flag so the system knows a file already exists
                if (typeof uploadFile !== 'undefined') {
                    // For the 3 special files, we use the 50, 51, 52 index logic
                    if (index >= 29) {
                        let specialIndex = index - 29 + 50; // maps 29->50, 30->51, 31->52
                        uploadFile[specialIndex] = 1;
                    } 
					else if (index >= 22) {
                        let specialIndex = index - 22 + 30; 
                        uploadFile[specialIndex] = 1;
                    }
					else {
                        uploadFile[index] = 1; 
                    }
                }
            } else {
                element.innerText = "";
            }
        }
    });
}

window.addEventListener('DOMContentLoaded', () => {
    processApplicationData(sessionData);
});
</script>













<body  >




<input type="file" id="fileInput" accept=".pdf,.doc,.docx" style="display:none">

<div class="blocky"> 

<div id="formspace" class="formspace">

<center>
<div class="blueheader">
<p class="BoldFont0"><br/>NIMASA|Nigerian Maritime Administration and Safety Agency
<br/><pre> </pre></p>
</div>
<br/>
<img src="../images/logo.png" style="width: 10vw; ">
<center class="BoldFont">
CABOTAGE CHECKLIST FOR PROCESSING OF WAIVER APPLICATION<br/>
VESSEL CATEGORY: BAREBOAT
<!--<h2 class="fonter1"><?php echo $_SESSION['user_email']; ?></h2>-->

</center>
<br/><br/>

</center>

<p class="BoldFont1">NAME OF COMPANY/AGENT  <input class="edits" id="noc"> <br/> </p>

<p class="BoldFont1">NAME OF VESSEL:MT/MV  <input class="edits" id="nov"> <br/> </p>

<p class="BoldFont1">TYPE OF VESSEL   <input class="edits" id="tov"> <br/> </p>

<p class="BoldFont1">GRT   <input class="edits" id="grt"> <br/> </p>

<p class="BoldFont1">VESSEL REGISTRATION STATUS   <input class="edits" id="vrs"> <br/> </p>







<p class="BoldFont1">IMO NUMBER  <input class="edits" id="imono"> <br/> </p>
<p class="BoldFont1">VESSEL OFFICIAL NUMBER   <input class="edits" id="vofno"> <br/> </p>\





<br/>
<div class="blueline">
<p class="BoldFont2">UPLOAD DOCUMENTS</p>
 </div>
 <br/>
 
 
 
  <p class="BoldFont1">Tax Clearance Certificate:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="TCC"></span>
 <button class="uploads" onclick="openTCC()">upload</button> 
 <button class="removes" onclick="RemoveTCC()">remove</button> <br/></p>
 
 
  <p class="BoldFont1">Maning Liscence:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="MLSC"></span>
 <button class="uploads" onclick="openMLSC()">upload</button> 
 <button class="removes" onclick="RemoveMLSC()">remove</button> <br/></p>
 
 
  <p class="BoldFont1">Maritime Labour Certificate:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="MLCT"></span>
 <button class="uploads" onclick="openMLCT()">upload</button> 
 <button class="removes" onclick="RemoveMLCT()">remove</button> <br/></p>
 
 
 
 
 
 
 
 
 
 
 
 
 


<p class="BoldFont1">Cabotage Affidavit Form (CAF) (Signed and Stamped by the Federal High Court):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 
 <span class="UploadData" id="CAF"></span>
 <button class="uploads" onclick="OpenCAF()">upload</button> 
 <button class="removes" onclick="RemoveCAF()">remove</button> <br/></p>



<p class="BoldFont1">Application to participate in Cabotage Trade Form(Signed and Stamped by the Federal High Court):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="BOS"></span>
 <button class="uploads" onclick="OpenBOS()">upload</button> 
 <button class="removes" onclick="RemoveBOS()">remove</button> <br/></p>



<p class="BoldFont1">Bill of Sale or Evidence of Ownership or Copy of Nigeria Ship Registry Certificate:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="EOCNSRC"></span>

 <button class="uploads" onclick="OpenEOCNSRC()">upload</button> 
 <button class="removes" onclick="RemoveEOCNSRC()">remove</button> <br/></p>







<p class="BoldFont1">Crew List Declaration Form (Signed and Stamped by the Federal High Court):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="BC"></span>
 <button class="uploads" onclick="openBC()">upload</button> 
 <button class="removes" onclick="RemoveBC()">remove</button> <br/></p>




<p class="BoldFont1">Declaration of Ownership Form (Signed and Stamped by the Federal High Court):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

 <span class="UploadData" id="ATPICTF"></span>
 <button class="uploads" onclick="openATPICTF()">upload</button> 
 <button class="removes" onclick="RemoveATPICTF()">remove</button> <br/></p>




<p class="BoldFont1">Detailed Crew list written on the company’s letter head paper (Duly signed, stamped and dated by the Master):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<span class="UploadData" id="CLDF"></span>
 <button class="uploads" onclick="openCLDF()">upload</button> 
 <button class="removes" onclick="RemoveCLDF()">remove</button> <br/></p>




<p class="BoldFont1">Copy of Minimum Safe Manning Certificate (SMC):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DOF"></span>
 <button class="uploads" onclick="openDOF()">upload</button> 
 <button class="removes" onclick="RemoveDOF()">remove</button> <br/></p>




<p class="BoldFont1">Completed Maritime Labour Declaration Form:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DCLWOTCLHP"></span>
 <button class="uploads" onclick="openDCLWOTCLHP()">upload</button> 
 <button class="removes" onclick="RemoveDCLWOTCLHP()">remove</button> <br/></p>




<p class="BoldFont1">CAC (2) & CAC(7) specifying a share capital of not less than =&#8358;=25M:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

 <span class="UploadData" id="DOE"></span>
 <button class="uploads" onclick="openDOE()">upload</button> 
 <button class="removes" onclick="RemoveDOE()">remove</button> <br/></p>




<p class="BoldFont1">Copy of Certificate of Incorporation:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="CMSMC"></span>
 <button class="uploads" onclick="openCMSMC()">upload</button> 
 <button class="removes" onclick="RemoveCMSMC()">remove</button> <br/></p>




<p class="BoldFont1">Memorandum and Articles of Association:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="CMLDF"></span>
 <button class="uploads" onclick="openCMLDF()">upload</button> 
 <button class="removes" onclick="RemoveCMLDF()">remove</button> <br/></p>



<br/><br/>

<center>
<button class="savecontinue" onclick="saveandcontinue()">Save and Continue</button>

<br/>
<br/>
&nbsp

</center>

</div>












<div id="formspace1" class="formspace1">

<center>
<div class="blueheader">
<p class="BoldFont0"><br/>NIMASA|Nigerian Maritime Administration and Safety Agency
<br/><pre> </pre></p>
</div>
<br/>
<img src="../images/logo.png" style="width: 10vw; ">
<center class="BoldFont">
CABOTAGE CHECKLIST FOR PROCESSING OF WAIVER APPLICATION<br/>
VESSEL CATEGORY: BAREBOAT
</center>
<br/><br/>

</center>




<p class="BoldFont1">
<b class="BoldFontZ">*</b> Append explanatory note where type of vessel does not support Cadet training.
<br/>
<b class="BoldFontZ">**</b> Attached letter from Dry-dock to proof unavailability of berth within statutory docking window.
</p>





<div class="blueline">
<p class="BoldFont2">UPLOAD DOCUMENTS</p>
 </div>
 <br/>
 
 <p class="BoldFont1">Tax Clearance Certificates (Last 3-years depending on years in business):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="CWF1"></span>
 <button class="uploads" onclick="openFilePicker1()">upload</button> 
 <button class="removes" onclick="RemoveFile1()">remove</button> <br/></p>


<p class="BoldFont1">Evidence of Registration as a Shipping Company with NIMASA:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="CAF1"></span>
 <button class="uploads" onclick="OpenCAF1()">upload</button> 
 <button class="removes" onclick="RemoveCAF1()">remove</button> <br/></p>



<p class="BoldFont1">Evidence of Payment of Sea Protection Levy (SPL) NOTE: Exempted for vessels less than 100 GRT:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="BOS1"></span>
 <button class="uploads" onclick="OpenBOS1()">upload</button> 
 <button class="removes" onclick="RemoveBOS1()">remove</button> <br/></p>


<!--
<p class="BoldFont1">Tax Clearance Certificates (Last 3-years depending on years in business):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="EOCNSRC1"></span>

 <button class="uploads" onclick="OpenEOCNSRC1()">upload</button> 
 <button class="removes" onclick="RemoveEOCNSRC1()">remove</button> <br/></p>

-->





<p class="BoldFont1">Bareboat Charter Agreement specifying period of not less than 5-years:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="BC1"></span>
 <button class="uploads" onclick="openBC1()">upload</button> 
 <button class="removes" onclick="RemoveBC1()">remove</button> <br/></p>




<p class="BoldFont1">Evidence / Proof of relationship between Foreign Ship owner and Nigerian Management company (For Foreign Owned):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp


 <span class="UploadData" id="ATPICTF1"></span>
 <button class="uploads" onclick="openATPICTF1()">upload</button> 
 <button class="removes" onclick="RemoveATPICTF1()">remove</button> <br/></p>




<p class="BoldFont1">Copy of survey report OR Temporary Importation document from customs for newly imported vessel(s):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="CLDF1"></span>
 <button class="uploads" onclick="openCLDF1()">upload</button> 
 <button class="removes" onclick="RemoveCLDF1()">remove</button> <br/></p>









<p class="BoldFont1">Consent to Delete from Foreign Registry:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DOF1"></span>
 <button class="uploads" onclick="openDOF1()">upload</button> 
 <button class="removes" onclick="RemoveDOF1()">remove</button> <br/></p>




<p class="BoldFont1">Evidence of Flag Suspension:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DCLWOTCLHP1"></span>
 <button class="uploads" onclick="openDCLWOTCLHP1()">upload</button> 
 <button class="removes" onclick="RemoveDCLWOTCLHP1()">remove</button> <br/></p>




<!-- other required documents -->
<br/>
<div class="blueline">
<p class="BoldFont2">OTHER DOCUMENTS</p>
 </div>
 <br/>
 
 


<p class="BoldFont1">Evidence of 2% Surcharge Payment (For Renewal):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

 <span class="UploadData" id="DOE1"></span>
 <button class="uploads" onclick="openDOE1()">upload</button> 
 <button class="removes" onclick="RemoveDOE1()">remove</button> <br/></p>




<p class="BoldFont1">Copy of last Waiver Certificate or receipt for waiver processing fees :  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="CMSMC1"></span>
 <button class="uploads" onclick="openCMSMC1()">upload</button> 
 <button class="removes" onclick="RemoveCMSMC1()">remove</button> <br/></p>































<p class="BoldFont1"><b class="BoldFontZ">*</b> Evidence of active Training of Nigerian Cadet (For Renewal):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DUMMY1"></span>
 <button class="uploads" onclick="openDUMMY1()">upload</button> 
 <button class="removes" onclick="RemoveDUMMY1()">remove</button> <br/></p>











<p class="BoldFont1"><b class="BoldFontZ">**</b> Evidence of Dry-docking in Nigeria or letter attesting to non-availability of docking space within window or in ability of available dry-dock to handle the class of vessel (For Renewal):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DUMMY2"></span>
 <button class="uploads" onclick="openDUMMY2()">upload</button> 
 <button class="removes" onclick="RemoveDUMMY2()">remove</button> <br/></p>







<p class="BoldFont1"> Historical cargo operations data (Last five loading and discharging ports):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DUMMY3"></span>
 <button class="uploads" onclick="openDUMMY3()">upload</button> 
 <button class="removes" onclick="RemoveDUMMY3()">remove</button> <br/></p>






<p class="BoldFont1">Cabotage Application for Waiver Form:  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DUMMY4"></span>
 <button class="uploads" onclick="openDUMMY4()">upload</button> 
 <button class="removes" onclick="RemoveDUMMY4()">remove</button> <br/></p>

<!--

<p class="BoldFont1"><b class="BoldFontZ">**</b>Evidence of Dry-docking in Nigeria or letter attesting to non-availability of docking space 
within window or in ability of available dry-dock to handle the class of vessel (For Renewal) :  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DUMMY5"></span>
 <button class="uploads" onclick="openDUMMY5()">upload</button> 
 <button class="removes" onclick="RemoveDUMMY5()">remove</button> <br/></p>
 
 

<p class="BoldFont1">Historical cargo operations data (Last five loading and discharging ports):  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
 <span class="UploadData" id="DUMMY6"></span>
 <button class="uploads" onclick="openDUMMY6()">upload</button> 
 <button class="removes" onclick="RemoveDUMMY6()">remove</button> <br/></p>


-->
















 
 
 

<br/><br/>

<center>
<button class="savecontinue" onclick="saveandfinish()">Save and Submit</button>
<br/><br/>
<button class="backs" onclick="saveandback()">Previous</button>
<br/>
<br/>
&nbsp

</center>

</div>





</div>






<script src="../JS/bareboatwaiver.js"></script>
</body>
</html>