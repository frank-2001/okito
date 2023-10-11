/*!
* Start Bootstrap - Shop Homepage v5.0.6 (https://startbootstrap.com/template/shop-homepage)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-homepage/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project
$.get("server/", {"houses-all":""},
    function (data, textStatus, jqXHR) {
        data.forEach(element => {
            content=   `
            <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <img class="card-img-top" src="server/images/`+element.image+`" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">`+element.adresse+`</h5>
                                    <!-- Product price-->
                                    Prix: `+element.prix+` <br>
                                    `+element.description+`
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                               
                                <div onclick="inter(`+element.id+`)" class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">En savoir plus</a></div>
                            </div>
                        </div>
                    </div>
            `
            
            $("#houses").append(content);
        });
         
    },
    "json"
);

function inter(id) {
    let number="+243970923033"
    let message="Salut je suis interesse par la maison ID "+id
    let url = "https://api.whatsapp.com/send/?phone=" + number + "&text=" + message + "&type=phone_number&app_absent=0"
    window.open(url);
}
$("#install_button").hide();
// Installation application
let deferredPrompt; // Allows to show the install prompt
const installButton = document.getElementById("install_button");
window.addEventListener("beforeinstallprompt", e => {
  // Prevent Chrome 76 and earlier from automatically showing a prompt
  e.preventDefault();
  // Stash the event so it can be triggered later.
  deferredPrompt = e;
  // Show the install button
  // installButton.hidden = false;
  $("#install_button").show(500);
  // installApp()
  installButton.addEventListener("click", installApp);
});
function installApp() {
  // Show the prompt
  deferredPrompt.prompt();
  installButton.disabled = true;

  // Wait for the user to respond to the prompt
  deferredPrompt.userChoice.then(choiceResult => {
    if (choiceResult.outcome === "accepted") {
      console.log("PWA setup accepted");
      installButton.hidden = true;
    } else {
      console.log("PWA setup rejected");
    }
    $("#install_button").hide(500);
    installButton.disabled = false;
    deferredPrompt = null;
  });
}

window.addEventListener("appinstalled", evt => {
  // $.toast({
  //   heading: 'Information',
  //   text: 'Application installee avec succes',
  //   icon: 'info',
  //   loader: true,        // Change it to false to disable loader
  //   loaderBg: '#131c7a',  // To change the background
  //   hideAfter: 5000
  // })

});