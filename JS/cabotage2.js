//let fileName = "";
//let fileData = null;

let fileName = new Array(100);
let fileData = new Array(100);


var dataType=0;

document.getElementById("fileInput").addEventListener("change", function(event)
{
    const file = event.target.files[0];

    if(file)
    {
        fileName[0] = file.name;
        fileData[0] = file;

        console.log("File Name:", fileName[0]);
        console.log("File Data:", fileData[0]);

        if(dataType==11){ document.getElementById("CWF1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==12){ document.getElementById("CAF1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==13){ document.getElementById("BOS1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==14){ document.getElementById("EOCNSRC1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==15){ document.getElementById("BC1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==16){ document.getElementById("ATPICTF1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==17){ document.getElementById("CLDF1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==18){ document.getElementById("DOF1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==19){ document.getElementById("DCLWOTCLHP1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==110){ document.getElementById("DOE1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==111){ document.getElementById("CMSMC1").innerText = "---" + fileName[0] + "---"; }
		else if(dataType==112){ document.getElementById("CMLDF1").innerText = "---" + fileName[0] + "---"; }
		
    }
});





function openFilePicker1()
{
	
	dataType=11;
    document.getElementById("fileInput").click();
}



function RemoveFile1(){
	
 document.getElementById("CWF1").innerText="";
	
}






function OpenCAF1()
{
	dataType=12;
    document.getElementById("fileInput").click();
}


function RemoveCAF1(){
	
 document.getElementById("CAF1").innerText="";
	
}


function OpenBOS1()
{
	dataType=13;
    document.getElementById("fileInput").click();
}


function RemoveBOS1(){
	
 document.getElementById("BOS1").innerText="";
	
}



function OpenEOCNSRC1()
{
	dataType=14;
    document.getElementById("fileInput").click();
}


function RemoveEOCNSRC1(){
	
 document.getElementById("EOCNSRC1").innerText="";
	
}


function openBC1()
{
	dataType=15;
    document.getElementById("fileInput").click();
}


function RemoveBC1(){
	
 document.getElementById("BC1").innerText="";
	
}


function openATPICTF1()
{
	dataType=16;
    document.getElementById("fileInput").click();
}


function RemoveATPICTF1(){
	
 document.getElementById("ATPICTF1").innerText="";
	
}


function openCLDF1()
{
	dataType=17;
    document.getElementById("fileInput").click();
}


function RemoveCLDF1(){
	
 document.getElementById("CLDF1").innerText="";
	
}


function openDOF1()
{
	dataType=18;
    document.getElementById("fileInput").click();
}


function RemoveDOF1(){
	
 document.getElementById("DOF1").innerText="";
	
}


function openDCLWOTCLHP1()
{
	dataType=19;
    document.getElementById("fileInput").click();
}


function RemoveDCLWOTCLHP1(){
	
 document.getElementById("DCLWOTCLHP1").innerText="";
	
}


function openDOE1()
{
	dataType=110;
    document.getElementById("fileInput").click();
}


function RemoveDOE1(){
	
 document.getElementById("DOE1").innerText="";
	
}


function openCMSMC1()
{
	dataType=111;
    document.getElementById("fileInput").click();
}


function RemoveCMSMC1(){
	
 document.getElementById("CMSMC1").innerText="";
	
}


function openCMLDF1()
{
	dataType=112;
    document.getElementById("fileInput").click();
}


function RemoveCMLDF1(){
	
 document.getElementById("CMLDF1").innerText="";
	
}
