var Canceled = 0;

function fileQueueError(file, errorCode, message) {
	try {
		var imageName = "error.gif";
		var errorName = "";
		if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
			errorName = "You have attempted to queue too many files.";
		}

		if (errorName !== "") {
			alert("ERROR:2-"+errorName);
			return;
		}

		switch (errorCode) {
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
				imageName = "zerobyte.gif";
				break;
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
				imageName = "toobig.gif";
				break;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			default:
				alert("ERROR:3-"+message);
				break;
		}

		//addImage("/images/" + imageName);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadProgress(file, bytesLoaded) {

	try {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);

		var progress = new FileProgress(file,  this.customSettings.upload_target);
		progress.setProgress(percent);
		if (percent === 100) {
			Canceled =0;
			progress.setStatus("İşleniyor...");
			progress.toggleCancel(false, this);
		} else {
			progress.setStatus("Yükleniyor...");
			progress.toggleCancel(true, this);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	console.log("upload complete:");
	console.log(serverData);
	console.log(serverData.substring(0, 9));
	try {
		var progress = new FileProgress(file,  this.customSettings.upload_target);
		//alert(this.customSettings.upload_target+"---"+serverData);

		if (serverData.substring(0, 9) === "PROPIC:OK") {
			progress.setStatus("Tamam. Saklamayı unutmayın."); //Thumbnail Created
			progress.toggleCancel(false);

            jQuery("input[name='Picture']").val( "/site_veri/profile_temp/"+serverData.replace("PROPIC:OK:","") ); 

			var el=document.getElementById('image_file_preview'); 
			el.innerHTML='<img src=\'/slir/w180/site_veri/profile_temp/' + serverData.replace("PROPIC:OK:","") + '\'  style=\'width:180px;  padding:1px;   border:0px solid #000000;   background-color:#dddddd; \'>'
		} else

		if (serverData.substring(0, 9) === "PROBKG:OK") {
			progress.setStatus("Tamam. Saklamayı unutmayın."); //Thumbnail Created
			progress.toggleCancel(false);
			//alert("!");

			BackgroundImage = "/site_veri/temp_bg/" + serverData.replace("PROBKG:OK:","");
			BackgroundImageSmall = "/slir/w100-h70-c3.2/" + BackgroundImage;
            jQuery("input[name='form_BackgroundImage']").val( "/site_veri/temp_bg/" + serverData.replace("PROBKG:OK:","") ); 

			updateBackground();
		} else

		if (serverData.substring(0, 9) === "PROMP3:OK") {
			progress.setStatus("İşleniyor..."); //Processing.
			progress.toggleCancel(false);
            jQuery("input[name='mp3_file']").val( serverData.replace("PROMP3:OK:","") ); 
			ProcessMP3( serverData.replace("PROMP3:OK:","") );
		} else 

		if (serverData.substring(0, 9) === "ELOPIC:OK") {
			progress.setStatus("İşleniyor..."); //Processing.
			progress.toggleCancel(false);
            jQuery("input[name='picture_file']").val( serverData.replace("ELOPIC:OK:","") ); 
//			document.getElementById('picture_file2').innerHTML=serverData.replace("ELOPIC:OK:","");
		} else 

		{
			progress.setStatus("Hata oluştu.");
			progress.toggleCancel(false);
//			alert(serverData);
		}


	} catch (ex) {
		this.debug(ex);
	}
}

function uploadComplete(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			if (Canceled == 1) { progress.setStatus("İptal edildi."); }  else
			progress.setStatus("Tamam. Saklamayı unutmayın.");
			progress.toggleCancel(false);
		}
/*	
		this.fileProgressID = "divFileProgress";
		aaa = document.getElementById(this.fileProgressID);
		while ( aaa.firstChild ) aaa.removeChild( aaa.firstChild );
*/
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	var imageName =  "error.gif";
	var progress;
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("İptal edildi.");
				progress.toggleCancel(false);
			}
			catch (ex1) {
				this.debug(ex1);
			}
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Durduruldu");
				progress.toggleCancel(true);
			}
			catch (ex2) {
				this.debug(ex2);
			}
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			imageName = "uploadlimit.gif";
			break;
		default:
			alert("ERROR:4-"+message+" "+file);
			break;
		}

		//addImage("images/" + imageName);

	} catch (ex3) {
		this.debug(ex3);
	}

}




/* ******************************************
 *	FileProgress Object
 *	Control object for displaying file info
 * ****************************************** */

function FileProgress(file, targetID) {
	console.log("divFileProgress_"+targetID);
	this.fileProgressID = "divFileProgress_"+targetID; // file.id; //"divFileProgress";

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		Canceled = 0;
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progressContainer";

		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode(" "));

		var progressText = document.createElement("div");
		progressText.className = "progressName";
		progressText.appendChild(document.createTextNode(file.name));

		var progressBar = document.createElement("div");
		progressBar.className = "progressBarInProgress";

		var progressStatus = document.createElement("div");
		progressStatus.className = "progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";

		this.fileProgressElement.appendChild(progressCancel);
//		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);

		this.fileProgressWrapper.appendChild(this.fileProgressElement);

		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
		fadeIn(this.fileProgressWrapper, 0);

	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
//		this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
	}

	this.height = this.fileProgressWrapper.offsetHeight;

}



FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[2].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[2].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "progressContainer blue";
	this.fileProgressElement.childNodes[2].className = "progressBarComplete";
	this.fileProgressElement.childNodes[2].style.width = "";

};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progressContainer red";
	this.fileProgressElement.childNodes[2].className = "progressBarError";
	this.fileProgressElement.childNodes[2].style.width = "";

};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[2].className = "progressBarError";
	this.fileProgressElement.childNodes[2].style.width = "";

};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[1].innerHTML = status;
};

FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfuploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
//			alert(fileID);
			swfuploadInstance.cancelUpload("",true);
			Canceled = 1;
			return false;
		};
	}
};


function fadeIn(element, opacity) {
	var reduceOpacityBy = 5;
	var rate = 30;	// 15 fps


	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}

		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}

	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}

