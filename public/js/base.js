const toastTriggerMsg = document.getElementById('liveToastBtnMsg')
	
	if (toastTriggerMsg) {
	toastTriggerMsg.addEventListener('click', () => {
		console.log("message clicked...")

		setIsShow();

		getMessage();

	})
	}
	const toastTriggerNotif = document.getElementById('liveToastBtnNotif')
	if (toastTriggerNotif) {
	toastTriggerNotif.addEventListener('click', () => {
		console.log("notification clicked...")
	})
	}
	const toastTriggerCart= document.getElementById('liveToastBtnCart')
	if (toastTriggerCart) {
	toastTriggerCart.addEventListener('click', () => {
		console.log("Cart clicked...")
	})
	}

	const toastTriggerProfil= document.getElementById('liveToastBtnProfil')
	const toastLiveProfil = document.getElementById('liveToastProfil')
	if (toastTriggerProfil) {
	toastTriggerProfil.addEventListener('click', () => {
		const toast = new bootstrap.Toast(toastLiveProfil)
		toast.show()
		
	})
	}

	const evtSourceNotif = new EventSource("/user/notifications");
	evtSourceNotif.onmessage = function(event) {
        const dataset = JSON.parse(event.data);
		if(dataset == 0){
			document.querySelector("#nb_notif").style.display = "none"
		}
		else if(dataset<=9){
			document.querySelector("#nb_notif").textContent = dataset
		}else{
			document.querySelector("#nb_notif").textContent = 9+"+"
		}
		//console.log(" dataset : "+dataset);
	}

	const evtSourceMsg = new EventSource("/user/messages");
	evtSourceMsg.onmessage = function(event) {
        const dataset = JSON.parse(event.data);
		if(dataset == 0){
			document.querySelector("#nb_message").style.display = "none"
		}
		else if(dataset<=9){
			document.querySelector("#nb_message").textContent = dataset
		}else{
			document.querySelector("#nb_message").textContent = 9+"+"
		}
		//console.log(" dataset : "+dataset);
	}

	const evtSourceCart = new EventSource("/user/cartes");
	evtSourceCart.onmessage = function(event) {
        const dataset = JSON.parse(event.data);
		if(dataset == 0){
			document.querySelector("#nb_carte").style.display = "none"
		}
		else if(dataset<=9){
			document.querySelector("#nb_carte").textContent = dataset
		}else{
			document.querySelector("#nb_carte").textContent = 9+"+"
		}
		//console.log(" dataset : "+dataset);
	}

	function getMessage(){
		fetch("/user/get_messages", {
			method: "GET",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			}
		}).then(response => response.json()).then(data=>{
			if(data=="Aucun message"){
				document.querySelector("#message_body").innerHTML = data;
			}else if(data.length>0){

				let res ="";
				for(let d of data){
					let isRead = d.isRead == 0 ? "bg-info-subtle" :"";
					if(d.content.length > 50){
						let substr = d.content.substring(0, 50)
						res +=`<div class="row message_box w-100 m-auto mb-2 ${isRead}"> <span onclick="openMsg(${d.id},${d.user_id})"><i class="col-2 bi bi-person-circle m-1 text-primary"> Elie Fenohasina</i><p class="col-10">${substr}...</p></span> </div>`
				
					}else{
						res +=`<div class="row message_box w-100 m-auto mb-2 ${isRead}"> <span onclick="openMsg(${d.id},${d.user_id})"><i class="col-2 bi bi-person-circle m-1 text-primary"> Elie Fenohasina</i><p class="col-10">${d.content}</p></span> </div>`
					}
				}
				document.querySelector("#message_body").innerHTML = `<div class="list-group">` +res + '</div>';
			}else{
				document.querySelector("#message_body").innerHTML = data;
			}
		})
	}

	function openMsg(id, user_id){

		console.log("receiver_id : "+user_id)

		setIsRead(user_id);

		const evtSourceMessage = new EventSource("/user/get_messages_by_id/"+user_id);
		evtSourceMessage.onmessage = function(event) {
			const message = JSON.parse(event.data);

			if(message.length>0){
				let res_me = ""
				let res_other = ""
				let total =""

				let user = "";

				for(let msg of message){
					user = msg.user_id;
					if(msg.isForMe == 1){
						res_me +=`<div class="d-flex flex-row justify-content-start">
							<img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp" alt="avatar 1" style="width: 45px; height: 100%;">
							<div id="my_message">
								<p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${msg.content}</p>
							</div>
						</div>`
					}else{
						res_me+=`<div class="d-flex flex-row justify-content-end mb-4 pt-1">
						<div id="other_message">
							<p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">${msg.content}</p>
						</div>
						<img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava4-bg.webp" alt="avatar 1" style="width: 45px; height: 100%;">
					</div>`

					}
					
				}

				document.querySelector("#user_id").value = user;

				document.querySelector("#all_message").innerHTML = res_me;
			}else{
				console.log("message : "+message)
			}
			
		}
		
		document.querySelector("#chat").style.display="block"

	}

	function quitMsg(){
		document.querySelector("#chat").style.display="none"
		
	}

	function setIsShow(){
		fetch(new Request("/user/setIsShow", {
			method: "POST",
			headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
			}
			})).then(req => req.json()).then(message => {
				console.log(message);
		})
	}

	function setIsRead(user_id){
		fetch(new Request("/user/setIsRead/"+user_id, {
			method: "POST",
			headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
			}
			})).then(req => req.json()).then(message => {
				console.log(message);
		})
	}

	let dropArea = document.getElementById("drop-area")
    if(dropArea){
        // Prevent default drag behaviors
        ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false)   
            document.body.addEventListener(eventName, preventDefaults, false)
            })
        
            // Highlight drop area when item is dragged over it
            ;['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false)
            })
        
            ;['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false)
            })
        
        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false)
    }

	

function preventDefaults (e) {
  e.preventDefault()
  e.stopPropagation()
}

function highlight(e) {
  dropArea.classList.add('highlight')
}

function unhighlight(e) {
  dropArea.classList.remove('active')
}

function handleDrop(e) {
  var dt = e.dataTransfer
  var files = dt.files

  handleFiles(files)
}

let uploadProgress = []
let progressBar = document.getElementById('progress-bar')

function initializeProgress(numFiles) {
  progressBar.value = 0
  uploadProgress = []

  for(let i = numFiles; i > 0; i--) {
    uploadProgress.push(0)
  }
}

function updateProgress(fileNumber, percent) {
  uploadProgress[fileNumber] = percent
  let total = uploadProgress.reduce((tot, curr) => tot + curr, 0) / uploadProgress.length
  console.debug('update', fileNumber, percent, total)
  progressBar.value = total
}

function handleFiles(files) {
  files = [...files]
  initializeProgress(files.length)
  //files.forEach(uploadFile)
  files.forEach(previewFile)
}

function previewFile(file) {
  let reader = new FileReader()
  reader.readAsDataURL(file)
  reader.onloadend = function() {
    let img = document.createElement('img')
    img.src = reader.result
    document.getElementById('gallery').appendChild(img)
  }
}


/*function uploadFile(file, i) {
  var url = 'https://api.cloudinary.com/v1_1/joezimim007/image/upload'
  var xhr = new XMLHttpRequest()
  var formData = new FormData()
  xhr.open('POST', url, true)
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')

  // Update progress (can be used to show progress indicator)
  xhr.upload.addEventListener("progress", function(e) {
    updateProgress(i, (e.loaded * 100.0 / e.total) || 100)
  })

  xhr.addEventListener('readystatechange', function(e) {
    if (xhr.readyState == 4 && xhr.status == 200) {
      updateProgress(i, 100) // <- Add this
    }
    else if (xhr.readyState == 4 && xhr.status != 200) {
      // Error. Inform the user
    }
  })

  formData.append('upload_preset', 'ujpu6gyk')
  formData.append('file', file)
  xhr.send(formData)
}*/
	function diff4humans(date){
		let dateEnd = new Date();
		let dateStart = new Date(date);

		var delta = Math.round((dateEnd- dateStart) / 1000); //en seconde

		var minute = 60,
			hour = minute * 60,
			day = hour * 24,
			week = day * 7;

		var fuzzy;

		if (delta < 30) {
			fuzzy = "à l'instant";
		} else if (delta < minute) {
			fuzzy = delta + ' sec';
		} else if (delta < 2 * minute) {
			fuzzy = '1 min'
		} else if (delta < hour) {
			fuzzy = Math.floor(delta / minute) + ' min';
		} else if (Math.floor(delta / hour) == 1) {
			fuzzy = '1 h'
		} else if (delta < day) {
			fuzzy = Math.floor(delta / hour) + ' h';
		} else if (delta < day * 2) {
			fuzzy = 'hier';
		}else{
			fuzzy = Math.floor(delta / day) + ' j';
		}

		return fuzzy;
	}

	setInterval(setDiff4Humans, 1000);

	function setDiff4Humans(){
		document.querySelectorAll(".diff4humans").forEach(diff=>{
			let data = diff.getAttribute("data-date");
			diff.textContent = diff4humans(data);
			//console.log("data date : "+ data)

			})
			document.querySelectorAll("#journal > div > small").forEach(diff=>{
				let data = diff.getAttribute("data-journal");
				diff.textContent = diff4humans(data);
				console.log("data date : "+ data)

		})
	
	}


	function addFriend(elem, user_id){
		if(elem.textContent.trim()==""){
			elem.classList = "btn btn-primary rounded-circle icon-md ms-auto";
			elem.innerHTML ="<i class='bi bi-person-check-fill'></i>";
		}else{
			elem.innerHTML ="<i class='bi bi-person-check-fill'></i> Suivi";
		}
		
		//console.log(elem.textContent)

		let data = {
			user_id : user_id,
		}

		fetch("/user/add_friend", {
			method: "POST",
			headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
			},
			body : JSON.stringify(data)
		})
		.then(res=>res.json())
		.then(message=>{
			console.log(message);
			if(message=="Success"){
				showAlert('Succès',"Votre demande d'amis est envoyée avec succès!", 'success');
			}else{
				showAlert('Erreur',"Une erreur se produite!", 'danger');
			}
		})
	}

	function openNotif(){

		fetch(new Request("/user/setIsShow_notif", {
			method: "POST",
			headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
			}
			})).then(req => req.json()).then(message => {
				console.log(message);
		})

		document.querySelector("#all_notification").innerHTML ="";

		fetch("/user/get_notifications").then(res => res.json())
		.then(result =>{
			let htm = "";
			if(result != "Null"){
				for(let res of result){
					htm+=`<div class="card mb-2" style="">
						<div class="card-body">
							<p class="card-text">${res.content}</p>
							<a href="#" class="card-link">Voir plus...</a>
						</div>
					</div>`
				}

				document.querySelector("#all_notification").innerHTML =htm;
				
			}
			
		})

	}

	function addToCard(pub_id,user_id, qtte){

		console.log("pub_id : "+pub_id+" , user_id : "+user_id)

		fetch("/user/get_product_by/"+pub_id+"/"+user_id).then(res => res.json())
		.then(product =>{
			//add to card
			let data = {
				product_id : product.id,
				user_id : user_id,
				quantity : qtte
			}
			fetch(new Request("/user/add_to_card", {
				method: "POST",
				headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
				},
				body : JSON.stringify(data)
				})).then(req => req.json()).then(message => {
					if(message=="Success"){
						showAlert('Succès',"Article bien enregistré dans votre panier!", 'success');
					}else{
						showAlert('Erreur',"Une erreur se produite!", 'danger');
					}
			})  
			//console.log(result.id)
		})

	}

	function setVendu(id){
		//console.log(id)
		fetch(new Request("/user/set_is_vendu/"+id, {
			method: "POST",
			headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
			}
			})).then(req => req.json()).then(message => {
				console.log(message);
				if(message=="Success"){
					showAlert('Succès',"Article bien marqué comme vendu!", 'success');
				}else{
					showAlert('Erreur',"Une erreur se produite!", 'danger');
				}
		})  
	}
	function expandImg(img){
		var modal = document.getElementById("myModalImage");
		var modalImg = document.getElementById("img01");
		var captionText = document.getElementById("caption");
		modal.style.display = "block";
		modalImg.src = img.src;
		captionText.innerHTML = img.alt;
	}

	function sponsoriser(id, user_id){
		fetch("/admin/add_sponsor/"+id+"/"+user_id).then(res=>res.json()).then(message=>{
			console.log(message);
			if(message=="Success"){
				showAlert('Succès',"Article bien sponsorisé avec succès!", 'success');
			}else{
				showAlert('Erreur',"Une erreur se produite!", 'danger');
			}
		})
	}

	function accepterInvitation(user_id){
		fetch(new Request("/user/accept_invitation/"+user_id, {
			method: "POST",
			headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
			}
			})).then(req => req.json()).then(message => {
				console.log(message);
				if(message=="Success"){
					showAlert('Succès',"Demande d'amis bien accepté!", 'success');
				}else{
					showAlert('Erreur',"Une erreur se produite!", 'danger');
				}
		})
	}

	function deleteFriend(user_id){
		fetch(new Request("/user/delete_friend/"+user_id, {
			method: "POST",
			headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
			}
			})).then(req => req.json()).then(message => {
				console.log(message);
				if(message=="Success"){
					showAlert('Succès',"Amis bien retiré avec succès!", 'success');
				}else{
					showAlert('Erreur',"Une erreur se produite!", 'danger');
				}
		})
	}


	const alertPlaceholder = document.getElementById('divAlert')

	function showAlert (titre, message, type){
		let wrapper = document.createElement('div')
		wrapper.innerHTML = [
			`<div class="alert alert-${type} alert-dismissible position-fixed end-0 bottom-0 me-2 mb-2 border border-2 border-${type}" role="alert"  style="z-index:9;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">`,
			`   <div><strong><i class="bi bi-check2-all"></i> ${titre}</strong></div>`,
			'   <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
			`  <hr><p class="h-100">${message}</p> `,
			'</div>'
		].join('')

		alertPlaceholder.append(wrapper)

		setTimeout(function reset() {
			wrapper.innerHTML="";
		},5000)
	}

	var modal = document.getElementById("myModalImage");

var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");


document.querySelectorAll(".img-fluid").forEach(img=>{
	img.onclick = function(){
	modal.style.display = "block";
	modalImg.src = this.src;
	captionText.innerHTML = this.alt;
	}
})

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}

