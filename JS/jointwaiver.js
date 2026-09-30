//let fileName[0] = "";
//let fileData[0] = null;










let fileName = new Array(100);
let fileData = new Array(100);
let uploadFile=new Array(100);

var dataType=0;

document.getElementById("fileInput").addEventListener("change", function(event) {
    const file = event.target.files[0];

    if (file) {
        // 1. Calculate the index (dataType 1 = index 0, dataType 2 = index 1, etc.)
        let index = dataType - 1;

        // 2. Store in respective arrays
        fileName[index] = file.name;
        fileData[index] = file;
		uploadFile[index]=99;

        // 3. Log the specific data
        console.log("File Name [" + index + "]:", fileName[index]);
        console.log("File Data [" + index + "]:", fileData[index]);

        // 4. Update the UI text
        if(dataType==1){ document.getElementById("CWF").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==2){ document.getElementById("CAF").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==3){ document.getElementById("BOS").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==4){ document.getElementById("EOCNSRC").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==5){ document.getElementById("BC").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==6){ document.getElementById("ATPICTF").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==7){ document.getElementById("CLDF").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==8){ document.getElementById("DOF").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==9){ document.getElementById("DCLWOTCLHP").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==10){ document.getElementById("DOE").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==11){ document.getElementById("CMSMC").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==12){ document.getElementById("CMLDF").innerText = "---" + fileName[index] + "---"; }
        
         else if(dataType==13){ document.getElementById("CWF1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==14){ document.getElementById("CAF1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==15){ document.getElementById("BOS1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==16){ document.getElementById("EOCNSRC1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==17){ document.getElementById("BC1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==18){ document.getElementById("ATPICTF1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==19){ document.getElementById("CLDF1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==20){ document.getElementById("DOF1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==21){ document.getElementById("DCLWOTCLHP1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==22){ document.getElementById("DOE1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==23){ document.getElementById("CMSMC1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==24){ document.getElementById("CMLDF1").innerText = "---" + fileName[index] + "---"; }
		
		
		
		
		
		
		else if(dataType==31){ document.getElementById("DUMMY1").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==32){ document.getElementById("DUMMY2").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==33){ document.getElementById("DUMMY3").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==34){ document.getElementById("DUMMY4").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==35){ document.getElementById("DUMMY5").innerText = "---" + fileName[index] + "---"; }
        else if(dataType==36){ document.getElementById("DUMMY6").innerText = "---" + fileName[index] + "---"; }
		
		
		
		
		else if(dataType==51){ document.getElementById("TCC").innerText = "---" + fileName[index] + "---"; }
		else if(dataType==52){ document.getElementById("MLSC").innerText = "---" + fileName[index] + "---"; }
		else if(dataType==53){ document.getElementById("MLCT").innerText = "---" + fileName[index] + "---"; }
		
		
    }
});










function openTCC()
{
	dataType=51;
    document.getElementById("fileInput").click();
}


function RemoveTCC(){
	
 document.getElementById("TCC").innerText=""; fileName[50]=""; fileData[50]=null;
	 fileName[50]=""
uploadFile[50]=99;
}



function openMLSC()
{
	dataType=52;
    document.getElementById("fileInput").click();
}


function RemoveMLSC(){
	
 document.getElementById("MLSC").innerText=""; fileName[51]=""; fileData[51]=null;
	 fileName[51]=""
uploadFile[51]=99;
}



function openMLCT()
{
	dataType=53;
    document.getElementById("fileInput").click();
}


function RemoveMLCT(){
	
 document.getElementById("TCC").innerText=""; fileName[52]=""; fileData[52]=null;
	 fileName[52]=""
uploadFile[52]=99;
}




























function openFilePicker()
{
	dataType=1;
    document.getElementById("fileInput").click();
}



function RemoveFile(){
	
 document.getElementById("CWF").innerText=""; fileName[0]=""; 
 
 fileData[0]=null;
 fileName[0]=""
uploadFile[0]=99;
	
}






function OpenCAF()
{
	dataType=2;
    document.getElementById("fileInput").click();
}


function RemoveCAF(){
	
 document.getElementById("CAF").innerText=""; fileName[1]=""; fileData[1]=null;
	 fileName[1]=""
uploadFile[1]=99;
}


function OpenBOS()
{
	dataType=3;
    document.getElementById("fileInput").click();
}


function RemoveBOS(){
	
 document.getElementById("BOS").innerText=""; fileName[2]=""; fileData[2]=null;
	 fileName[2]=""
uploadFile[2]=99;
}



function OpenEOCNSRC()
{
	dataType=4;
    document.getElementById("fileInput").click();
}


function RemoveEOCNSRC(){
	
 document.getElementById("EOCNSRC").innerText=""; fileName[3]=""; fileData[3]=null;
	 fileName[3]=""
uploadFile[3]=99;
}


function openBC()
{
	dataType=5;
    document.getElementById("fileInput").click();
}


function RemoveBC(){
	
 document.getElementById("BC").innerText=""; fileName[4]=""; fileData[4]=null;
	 fileName[4]=""
uploadFile[4]=99;
}


function openATPICTF()
{
	dataType=6;
    document.getElementById("fileInput").click();
}


function RemoveATPICTF(){
	
 document.getElementById("ATPICTF").innerText=""; fileName[5]=""; fileData[5]=null;
	 fileName[5]=""
uploadFile[5]=99;
}


function openCLDF()
{
	dataType=7;
    document.getElementById("fileInput").click();
}


function RemoveCLDF(){
	
 document.getElementById("CLDF").innerText=""; fileName[6]=""; fileData[6]=null;
	 fileName[6]=""
uploadFile[6]=99;
}


function openDOF()
{
	dataType=8;
    document.getElementById("fileInput").click();
}


function RemoveDOF(){
	
 document.getElementById("DOF").innerText=""; fileName[7]=""; fileData[7]=null;
	 fileName[7]=""
uploadFile[7]=99;
}


function openDCLWOTCLHP()
{
	dataType=9;
    document.getElementById("fileInput").click();
}


function RemoveDCLWOTCLHP(){
	
 document.getElementById("DCLWOTCLHP").innerText=""; fileName[8]=""; fileData[8]=null;
	 fileName[8]=""
uploadFile[8]=99;
}


function openDOE()
{
	dataType=10;
    document.getElementById("fileInput").click();
}


function RemoveDOE(){
	
 document.getElementById("DOE").innerText=""; fileName[9]=""; fileData[9]=null;
	 fileName[9]=""
uploadFile[9]=99;
}


function openCMSMC()
{
	dataType=11;
    document.getElementById("fileInput").click();
}


function RemoveCMSMC(){
	
 document.getElementById("CMSMC").innerText=""; fileName[10]=""; fileData[10]=null;
	 fileName[10]=""
uploadFile[10]=99;
}


function openCMLDF()
{
	dataType=12;
    document.getElementById("fileInput").click();
}


function RemoveCMLDF(){
	
 document.getElementById("CMLDF").innerText=""; fileName[11]=""; fileData[11]=null;
 fileName[11]=""
uploadFile[11]=99;	
}



































function openFilePicker1()
{
	
	dataType=13;
    document.getElementById("fileInput").click();
}



function RemoveFile1(){
	
 document.getElementById("CWF1").innerText=""; fileName[12]=""; fileData[12]=null;
	 fileName[12]=""
uploadFile[12]=99;
}






function OpenCAF1()
{
	dataType=14;
    document.getElementById("fileInput").click();
}


function RemoveCAF1(){
	
 document.getElementById("CAF1").innerText=""; fileName[13]=""; fileData[13]=null;
fileName[13]=""
uploadFile[13]=99;	
}


function OpenBOS1()
{
	dataType=15;
    document.getElementById("fileInput").click();
}


function RemoveBOS1(){
	
 document.getElementById("BOS1").innerText=""; fileName[14]=""; fileData[14]=null;
	fileName[14]=""
uploadFile[14]=99;
}



function OpenEOCNSRC1()
{
	dataType=16;
    document.getElementById("fileInput").click();
}


function RemoveEOCNSRC1(){
	
 document.getElementById("EOCNSRC1").innerText=""; fileName[15]=""; fileData[15]=null;
	fileName[15]=""
uploadFile[15]=99;
}


function openBC1()
{
	dataType=17;
    document.getElementById("fileInput").click();
}


function RemoveBC1(){
	
 document.getElementById("BC1").innerText=""; fileName[16]=""; fileData[16]=null;
	fileName[16]=""
uploadFile[16]=99;
}


function openATPICTF1()
{
	dataType=18;
    document.getElementById("fileInput").click();
}


function RemoveATPICTF1(){
	
 document.getElementById("ATPICTF1").innerText=""; fileName[17]=""; fileData[17]=null;
	fileName[17]=""
uploadFile[17]=99;
}


function openCLDF1()
{
	dataType=19;
    document.getElementById("fileInput").click();
}


function RemoveCLDF1(){
	
 document.getElementById("CLDF1").innerText=""; fileName[18]=""; fileData[18]=null;
	fileName[18]=""
uploadFile[18]=99;
}


function openDOF1()
{
	dataType=20;
    document.getElementById("fileInput").click();
}


function RemoveDOF1(){
	
 document.getElementById("DOF1").innerText=""; fileName[19]=""; fileData[19]=null;
	fileName[19]=""
uploadFile[19]=99;
}


function openDCLWOTCLHP1()
{
	dataType=21;
    document.getElementById("fileInput").click();
}


function RemoveDCLWOTCLHP1(){
	
 document.getElementById("DCLWOTCLHP1").innerText=""; fileName[20]=""; fileData[20]=null;
fileName[20]=""
uploadFile[20]=99;	
}


function openDOE1()
{
	dataType=22;
    document.getElementById("fileInput").click();
}


function RemoveDOE1(){
	
 document.getElementById("DOE1").innerText=""; fileName[21]=""; fileData[21]=null;
fileName[21]=""
uploadFile[21]=99;		
}


function openCMSMC1()
{
	dataType=23;
    document.getElementById("fileInput").click();
}


function RemoveCMSMC1(){
	
 document.getElementById("CMSMC1").innerText=""; fileName[22]=""; fileData[22]=null;
	fileName[22]=""
uploadFile[22]=99;	
}


function openCMLDF1()
{
	dataType=24;
    document.getElementById("fileInput").click();
}


function RemoveCMLDF1(){
	
 document.getElementById("CMLDF1").innerText=""; fileName[23]=""; fileData[23]=null;
	fileName[23]=""
uploadFile[23]=99;	
}


















function openDUMMY1()
{
	dataType=31;
    document.getElementById("fileInput").click();
}


function RemoveDUMMY1(){
	
 document.getElementById("DUMMY1").innerText=""; fileName[30]=""; fileData[30]=null;
	fileName[30]=""
uploadFile[30]=99;	
}

function openDUMMY2()
{
	dataType=32;
    document.getElementById("fileInput").click();
}


function RemoveDUMMY2(){
	
 document.getElementById("DUMMY2").innerText=""; fileName[31]=""; fileData[31]=null;
	fileName[31]=""
uploadFile[31]=99;	
}

function openDUMMY3()
{
	dataType=33;
    document.getElementById("fileInput").click();
}


function RemoveDUMMY3(){
	
 document.getElementById("DUMMY3").innerText=""; fileName[32]=""; fileData[32]=null;
	fileName[32]=""
uploadFile[32]=99;	
}

function openDUMMY4()
{
	dataType=34;
    document.getElementById("fileInput").click();
}


function RemoveDUMMY4(){
	
 document.getElementById("DUMMY4").innerText=""; fileName[33]=""; fileData[33]=null;
	fileName[33]=""
uploadFile[33]=99;	
}


function openDUMMY5()
{
	dataType=35;
    document.getElementById("fileInput").click();
}


function RemoveDUMMY5(){
	
 document.getElementById("DUMMY5").innerText=""; fileName[34]=""; fileData[34]=null;
	fileName[34]=""
uploadFile[34]=99;	
}


function openDUMMY6()
{
	dataType=36;
    document.getElementById("fileInput").click();
}


function RemoveDUMMY6(){
	
 document.getElementById("DUMMY6").innerText=""; fileName[35]=""; fileData[35]=null;
	fileName[35]=""
uploadFile[35]=99;	
}




























function onloadset(){
	
	for (let i = 0; i < 100; i++) {
    uploadFile[i]=-1000;
}
	
}






function saveandcontinue() {
    const formData = new FormData();

    // 1. Identification
    formData.append('tag', currentTag);

    // 2. Text Inputs (Still dat1 through dat5)
	formData.append('imono', document.getElementById('imono').value);
	formData.append('vofno', document.getElementById('vofno').value);
	
	
	
const specialColumns = ["tax_clearance", "manning_liscense", "maritime_labour"];

for (let i = 0; i < specialColumns.length; i++) {
    let index = i + 50; // This targets index 50, 51, and 52 in your arrays
    let columnName = specialColumns[i];
    let updateFlag = uploadFile[index];

    // Send the update flag for the specific column
    formData.append(`update_flag_${columnName}`, updateFlag);

    if (updateFlag == 99) {
        // Append the actual file object
        formData.append(columnName, fileData[index]); 
        
        // Append the file name with the "_name" suffix
        formData.append(`${columnName}_name`, fileName[index] || "");
    }
}
	
	
	
    formData.append('dat1', document.getElementById('noc').value);
    formData.append('dat2', document.getElementById('nov').value);
    formData.append('dat3', document.getElementById('tov').value);
    formData.append('dat4', document.getElementById('grt').value);
    formData.append('dat5', document.getElementById('vrs').value);

    // 3. File Processing Loop
    // i = 0 refers to fileData[0], which belongs to column dat6
    // i = 12 refers to fileData[12], which belongs to column dat18
    for (let i = 0; i <= 12; i++) {
        let dbIndex = i + 5; 
        let updateFlag = uploadFile[i]; 
        
        formData.append(`update_flag_${dbIndex}`, updateFlag);

        if (updateFlag == 99) {
            // We pull from fileData[0] for dat6, fileData[1] for dat7, etc.
            formData.append(`dat${dbIndex}`, fileData[i]); 
            formData.append(`dat${dbIndex}_name`, fileName[i] || "");
        }
    }

    fetch('../php/jointwaiver.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status1 === "success") {
            if(result.newTag) currentTag = result.newTag;
            document.getElementById('formspace').style.display = "none";
            document.getElementById('formspace1').style.display = "block";
            window.scrollTo(0, 0);
        } else {
            alert("Error: " + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Server connection failed.");
    });
}


function saveandsubmit(){
		
	const formData = new FormData();

    // 1. Core Identification
    formData.append('tag', currentTag);

   
  for (let i = 12; i <= 35; i++) {
        let dbIndex = i+5;
        
        
       if(i>29){ dbIndex=i-2; }

        let updateFlag = uploadFile[i];
        formData.append(`update_flag_${dbIndex}`, updateFlag);

        if (updateFlag == 99) {
            // Append the actual File object if it exists, otherwise empty string
            formData.append(`dat${dbIndex}`, fileData[i] || "");
            formData.append(`dat${dbIndex}_name`, fileName[i] || "");
        }
    }
	
	
	
	 

	
	
            fetch('../php/foreign2.php', {
    method: 'POST',
    body: formData
})
.then(response => response.json()) // We expect JSON now
.then(result => {
   // alert(result.message);

     if (result.status1 === "success") {
	document.getElementById('formspace').style="display:block;";
	document.getElementById('formspace1').style="display:none;";
	  
	window.scrollTo(0, 0);
	

    }
})
.catch(error => console.error('Error:', error));
	

}



function saveandsubmit2(){
		
	const formData = new FormData();

    // 1. Core Identification
    formData.append('tag', currentTag);

      
  for (let i = 12; i <= 35; i++) {
        let dbIndex = i+5;
        
        
       if(i>29){ dbIndex=i-2; }

        let updateFlag = uploadFile[i];
        formData.append(`update_flag_${dbIndex}`, updateFlag);

        if (updateFlag == 99) {
            // Append the actual File object if it exists, otherwise empty string
            formData.append(`dat${dbIndex}`, fileData[i] || "");
            formData.append(`dat${dbIndex}_name`, fileName[i] || "");
        }
    }
	
	
	
	

	
            fetch('../php/foreign3.php', {
    method: 'POST',
    body: formData
})
.then(response => response.json()) // We expect JSON now
.then(result => {
   // alert(result.message);

    if (result.status1 === "success") {
     
	  window.location.href = "../pages/dashboard.php"; 
	  

    }
})
.catch(error => console.error('Error:', error));
	

}



function saveandfinish(){
const userConfirmed = confirm("Are you sure you want to submit and finish? \n\nWARNING: Application will enter Processing stage after submission.");

   
    if (userConfirmed) {
        saveandsubmit2();
    } else {
       
    }
	
}


function saveandback(){
	
	saveandsubmit();
	
	
	  
	  
	
	
	
}







