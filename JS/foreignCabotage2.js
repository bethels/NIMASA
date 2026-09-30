let fileName = "";
let fileData = null;

var dataType=0;

document.getElementById("fileInput").addEventListener("change", function(event)
{
    const file = event.target.files[0];

    if(file)
    {
        fileName = file.name;
        fileData = file;

        console.log("File Name:", fileName);
        console.log("File Data:", fileData);

        if(dataType==1){ document.getElementById("CWF").innerText = "---" + fileName + "---"; }
		else if(dataType==2){ document.getElementById("CAF").innerText = "---" + fileName + "---"; }
		else if(dataType==3){ document.getElementById("BOS").innerText = "---" + fileName + "---"; }
		else if(dataType==4){ document.getElementById("EOCNSRC").innerText = "---" + fileName + "---"; }
		else if(dataType==5){ document.getElementById("BC").innerText = "---" + fileName + "---"; }
		else if(dataType==6){ document.getElementById("ATPICTF").innerText = "---" + fileName + "---"; }
		else if(dataType==7){ document.getElementById("CLDF").innerText = "---" + fileName + "---"; }
		else if(dataType==8){ document.getElementById("DOF").innerText = "---" + fileName + "---"; }
		else if(dataType==9){ document.getElementById("DCLWOTCLHP").innerText = "---" + fileName + "---"; }
		else if(dataType==10){ document.getElementById("DOE").innerText = "---" + fileName + "---"; }
		else if(dataType==11){ document.getElementById("CMSMC").innerText = "---" + fileName + "---"; }
		else if(dataType==12){ document.getElementById("CMLDF").innerText = "---" + fileName + "---"; }
		else if(dataType==13){ document.getElementById("HCOD").innerText = "---" + fileName + "---"; }
		else if(dataType==14){ document.getElementById("CSROTIDFCFNIV").innerText = "---" + fileName + "---"; }
		else if(dataType==15){ document.getElementById("EPRBFSONMC").innerText = "---" + fileName + "---"; }
		else if(dataType==16){ document.getElementById("CLWCRWPF").innerText = "---" + fileName + "---"; }
		else if(dataType==17){ document.getElementById("EDNLANADS").innerText = "---" + fileName + "---"; }
		
    }
});





function openFilePicker()
{
	dataType=1;
    document.getElementById("fileInput").click();
}



function RemoveFile(){
	
 document.getElementById("CWF").innerText="";
	
}






function OpenCAF()
{
	dataType=2;
    document.getElementById("fileInput").click();
}


function RemoveCAF(){
	
 document.getElementById("CAF").innerText="";
	
}


function OpenBOS()
{
	dataType=3;
    document.getElementById("fileInput").click();
}


function RemoveBOS(){
	
 document.getElementById("BOS").innerText="";
	
}



function OpenEOCNSRC()
{
	dataType=4;
    document.getElementById("fileInput").click();
}


function RemoveEOCNSRC(){
	
 document.getElementById("EOCNSRC").innerText="";
	
}


function openBC()
{
	dataType=5;
    document.getElementById("fileInput").click();
}


function RemoveBC(){
	
 document.getElementById("BC").innerText="";
	
}


function openATPICTF()
{
	dataType=6;
    document.getElementById("fileInput").click();
}


function RemoveATPICTF(){
	
 document.getElementById("ATPICTF").innerText="";
	
}


function openCLDF()
{
	dataType=7;
    document.getElementById("fileInput").click();
}


function RemoveCLDF(){
	
 document.getElementById("CLDF").innerText="";
	
}


function openDOF()
{
	dataType=8;
    document.getElementById("fileInput").click();
}


function RemoveDOF(){
	
 document.getElementById("DOF").innerText="";
	
}


function openDCLWOTCLHP()
{
	dataType=9;
    document.getElementById("fileInput").click();
}


function RemoveDCLWOTCLHP(){
	
 document.getElementById("DCLWOTCLHP").innerText="";
	
}


function openDOE()
{
	dataType=10;
    document.getElementById("fileInput").click();
}


function RemoveDOE(){
	
 document.getElementById("DOE").innerText="";
	
}


function openCMSMC()
{
	dataType=11;
    document.getElementById("fileInput").click();
}


function RemoveCMSMC(){
	
 document.getElementById("CMSMC").innerText="";
	
}


function openCMLDF()
{
	dataType=12;
    document.getElementById("fileInput").click();
}


function RemoveCMLDF(){
	
 document.getElementById("CMLDF").innerText="";
	
}




function openHCOD()
{
	dataType=13;
    document.getElementById("fileInput").click();
}


function RemoveHCOD(){
	
 document.getElementById("HCOD").innerText="";
	
}

function openCSROTIDFCFNIV()
{
	dataType=14;
    document.getElementById("fileInput").click();
}


function RemoveCSROTIDFCFNIV(){
	
 document.getElementById("CSROTIDFCFNIV").innerText="";
	
}

function openEPRBFSONMC()
{
	dataType=15;
    document.getElementById("fileInput").click();
}


function RemoveEPRBFSONMC(){
	
 document.getElementById("EPRBFSONMC").innerText="";
	
}









function openCLWCRWPF()
{
	dataType=16;
    document.getElementById("fileInput").click();
}


function RemoveCLWCRWPF(){
	
 document.getElementById("CLWCRWPF").innerText="";
	
}

function openEDNLANADS()
{
	dataType=17;
    document.getElementById("fileInput").click();
}


function RemoveEDNLANADS(){
	
 document.getElementById("EDNLANADS").innerText="";
	
}

